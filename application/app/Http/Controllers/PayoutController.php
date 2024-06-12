<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Log;
use Mail;
use App\User;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\Payout;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use App\Jobs\SendPayoutMail;
use Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Bulk_Files;
use App\Models\Merchant;
use App\Models\Setting;

class PayoutController extends Controller
{
    public function index(Request $request)
    {
        $user_id = Auth::user()->id;
        $currency_id = Auth::user()->currency_id;
        $balance = Wallet::where('user_id',$user_id)->where('currency_id',$currency_id)->pluck('fiat')->first();
        $amount = $request->amount ?? '' ;
        $merchant_status = Merchant::where('user_id',$user_id)->pluck('time_status')->first();

        if ($balance == 0 ) {
            return response()->json(['message' => 'low balance' ,'status'=>'0']);
        }
        
        if($amount == 0){
            return response()->json(['message' => 'amount should be greater than zero' ,'status'=>'0']);
        }

        if ($amount > $balance ) {
            return response()->json(['message' => 'amount cannot be greater than available balance' ,'status'=>'0']);
        }

        $setting = Setting::where('key','time_status')->first();
        if($setting)
        {
           $value = $setting->value;
            if ($value == '24') {
               $merchant_status = '24';
           }elseif ($value == '48') {
               $merchant_status = '48';
           }elseif ($value == '72') {
               $merchant_status = '72';
           }else{
                $merchant_status = '0';
           }
        }
 

        if ($merchant_status == '24') {
            
            $get_todays_balance = Transaction::where('user_id',$user_id)->whereIn('activity_title',['Sale','Manual Deposit From Dashboard'])->where('created_at', '>=', Carbon::now()->subDay()->toDateTimeString())->get();

            $netBalance = 0;
            foreach($get_todays_balance as $key => $value){
                $netBalance += $value['net'];
            }

            $can_payout = $balance - $netBalance;

            if ($amount > $can_payout) {
                return response()->json(['message' => 'You can only payout upto '. $can_payout ,'status'=>'0']);
            }

        }

        if ($merchant_status == '48') {

            
            $get_todays_balance = Transaction::where('user_id',$user_id)->whereIn('activity_title',['Sale','Manual Deposit From Dashboard'])->where('created_at', '>=', Carbon::now()->subDay()->toDateTimeString(2))->get();


            $netBalance = 0;
            foreach($get_todays_balance as $key => $value){
                $netBalance += $value['net'];
            }
           
            $can_payout = $balance - $netBalance;
            
            if ($amount > $can_payout) {
                return response()->json(['message' => 'You can only payout upto '. $can_payout ,'status'=>'0']);
            }

        }

         if ($merchant_status == '72') {
            
            $get_todays_balance = Transaction::where('user_id',$user_id)->whereIn('activity_title',['Sale','Manual Deposit From Dashboard'])->where('created_at', '>=', Carbon::now()->subDay()->toDateTimeString(3))->get();

            $netBalance = 0;
            foreach($get_todays_balance as $key => $value){
                $netBalance += $value['net'];
            }

            $can_payout = $balance - $netBalance;

            if ($amount > $can_payout) {
                return response()->json(['message' => 'You can only payout upto '. $can_payout ,'status'=>'0']);
            }

        }
        
        $check_payout_balance = Payout::where('user_id',$user_id)->where('currency_id',$currency_id)->latest()->pluck('balance')->first();
        $total_payout_balance = $check_payout_balance + $amount;

        $deduct_from_wallet = Wallet::where('user_id',$user_id)->where('currency_id',$currency_id)->first();
        $wallet_balance = $deduct_from_wallet->fiat;
        $updated_wallet_balance = $wallet_balance - $amount;
        $wallet = Wallet::findOrFail($deduct_from_wallet->id);
        if($wallet) {
            $wallet->fiat = $updated_wallet_balance;
            $wallet->payout_fiat = $total_payout_balance;
            $wallet->save();
        }
        
        $user_save = User::where('id',$user_id)->first();
        if($user_save){
            $user_save->payout_balance = $total_payout_balance;
            $user_save->save();
        }

        $srting = strtoupper($this->random_strings(8));
        
        $check_trxn_balance = Transaction::where('user_id', $user_id)->latest()->pluck('balance')->first();
        if ($check_trxn_balance == 0) {
            $total_trxn_balance =  $amount;   
        } else {
            $total_trxn_balance = $check_trxn_balance - $amount;
            $total_trxn_balance = max(0, $total_trxn_balance);
        }

        $transaction = new Transaction();
        $transaction->user_id = $user_id;
        $transaction->entity_id = $user_id;
        $transaction->entity_name = Auth::user()->name;
        $transaction->transactionable_type = 'App\Models\Payout';
        $transaction->activity_title = 'Payout A/C';
        $transaction->money_flow = '-';
        $transaction->transaction_state_id = '1';
        $transaction->balance = $total_trxn_balance;
        $transaction->transactionable_id = $srting;
        $transaction->request_id = $srting;
        $transaction->gross = $amount;
        $transaction->fee = 0.00;
        $transaction->net = $amount;
        $transaction->currency_id = $wallet->currency_id;
        $transaction->currency_symbol = ($wallet->currency_id == '14') ? 'INR' : 'USD';
        $transaction->save();
        


        $payout = new Payout();
        $payout->user_id = $user_id;
        $payout->payout_id = $srting;
        $payout->transaction_state_id = '1';
        $payout->transactionable_type = 'Transferred';
        $payout->gross = $amount;
        $payout->fee = 0.00;
        $payout->net = $amount;
        $payout->balance = $total_payout_balance;
        $payout->money_flow = '+';
        $payout->currency_id = $wallet->currency_id;
        $payout->currency_symbol = ($wallet->currency_id == '14') ? 'INR' : 'USD';
        $payout->save();

        return response()->json(['message' => 'balance transfered successfully' ,'status'=>'1']);

    }

    public static function random_strings($length_of_string)
    {
        // md5 the timestamps and returns substring
        // of specified length
        return substr(md5(time()), 0, $length_of_string);
    }
              
    public function getPayoutData(Request $request)
    {
        $id = Auth::user()->id;
        $active = 'payout';
        $page_title = 'All Payout';
        $check_payout_balance = Auth::user()->payout_balance ?? 0;
        $todayStartUTC = now()->startOfDay()->setTimezone('UTC');
        $todayEndUTC = now()->endOfDay()->setTimezone('UTC');
    
        $transactions = Payout::whereBetween('created_at', [$todayStartUTC, $todayEndUTC])->where('transaction_state_id', 1)->get();
    
        $bulk_payout = $transactions->where('transactionable_type', 'Bulk Transfer')->sum('net');
        return view('payout.index', compact('active', 'page_title','check_payout_balance','bulk_payout'));
    }

    public function payout_list(Request $request)
    {
       
        $id = Auth::user()->id;

        if (!request()->ajax()) {
            $active = 'payout';
            $page_title = 'All Payout';
          
            return view('payout.index', compact('active', 'page_title'));
        }

       
        $data = Payout::query()->with('User')->where('user_id', '=' , $id)->orderBy('payout.id','desc')
             ->when(!empty(request()->transaction_state_id), function ($query) {
                return $query->where('transaction_state_id', request()->transaction_state_id);
            })
            ->when(!empty(request()->activity_type), function ($query) {
                return $query->where('transactionable_type', request()->activity_type);
            })
            ->when(!empty(request()->created_at_from), function ($query) {
                return $query->whereDate('created_at', '>=' ,date('Y-m-d', strtotime(request()->created_at_from)));
            })
            ->when(!empty(request()->created_at_to), function ($query) {
                return $query->whereDate('created_at', '<=' ,date('Y-m-d', strtotime(request()->created_at_to)));
            });
       

        if (request()->multiple_email) {
           $emails = request()->multiple_email;
        }

        if (request()->openEmailModal == '1') {
            $for_data = $data->get();
            $new_data = [];
            foreach ($for_data as $key => $value) {
                $new_data[] = $value;
            }
            $new_data['emails'] = $emails;
            try{
                dispatch(new SendPayoutMail($new_data));
                Log::debug('Job dispatched successfully.');
            }catch (Exception $e) {
                Log::error('Error dispatching job: ' . $e->getMessage());
            }
        }else{
            $data = $data->latest();
        }
       
        return DataTables::eloquent($data)
            ->addColumn('user_name', function ($data) {
                return $data->User->name;
            }) 
            ->addColumn('status', function ($data) {
                $badge = '';
                if(!empty($data->transaction_state_id)){
                    if($data->transaction_state_id == 1) {
                        $color = 'info';
                        $name = 'Completed';
                    } elseif ($data->transaction_state_id == 2) {
                        $color = 'danger';
                        $name = 'Canceled';
                    } elseif ($data->transaction_state_id == 4) {
                        $color = 'info';
                        $name = 'Partially Completed';
                    }else {
                        $color = 'warning';
                        $name = 'Pending';
                    }
                    $badge .= '<span class="badge badge-'.$color.'" >'.$name.'</span>';

                }
                return $badge;
            }) 
           ->editColumn('money_flow', function ($data) {
                $badge = '';
                if ($data->money_flow === '+') {
                    $badge .= '<span class="badge badge-info" >'.$data->money_flow.'</span>';
                } else {
                    $badge .= '<span class="badge badge-danger" >'.$data->money_flow.'</span>';
                }
                return $badge;
            })
            
            ->editColumn('created_at', function ($data) {
                return !empty($data->created_at) && ($data->created_at instanceof Carbon)? $data->created_at->timezone('Asia/Kolkata')->format('M j, Y, g:i A') : '-';

            })
            ->addIndexColumn()
            ->rawColumns(['status','money_flow'])
            ->toJson();

    }

        public function getpayoutgroupwise(Request $request){
            $payoutGroupId = $request->input('payout_group_id');
            $UserId = $request->input('user_id');
            $payout_id = $request->input('payout_id');
            $payouts = Payout::where('payout_group_id', $payoutGroupId)->where('user_id',$UserId)->get();
            $response = [
                'payouts' => $payouts,
                'payoutGroupId' => 'Payout Group ID :' . $payoutGroupId,
            ];
            return response()->json($response);
        }

        public function bulkuploadpayout(Request $request){
       
            $validator = Validator::make($request->all(), [
                'fileToUpload' => 'required|mimes:xlsx,xls|max:2048',
                'date_time' => 'required',
            ]);
        
            $date_time = $request->date_time;
            $current_time = Carbon::now();
        
            $diff = Carbon::parse($date_time)->isPast();
            $user_id = Auth::user()->id;
            $merchant = Merchant::where('user_id',$user_id)->first();
        
            if ($diff == true) {
                return redirect()->back()->withErrors(['date_time' => 'past date & time is not valid.'])->withInput();
            }
        
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        
            if ($request->hasFile('fileToUpload')) {
                $file = $request->file('fileToUpload');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('bulk_files', $fileName, 'public');
                $filePath = '/storage/app/public/'.$filePath;

                try {
                    $data =  Excel::load($filePath, 'UTF-8')->all();
                    $excel_heaing = $data->getHeading();
                    $totalAmount = 0;
                    foreach ($data as $row) {
                        $amount = $row['amount'];
                        if (is_numeric($amount)) { 
                            $totalAmount += $amount;
                        }else{
                            flash('Amount is not a number in row');
                            return redirect()->back();
                        }
                    }
                } catch (\Exception $e) {
                    return redirect()->back()->withErrors(['fileToUpload' => 'Error reading Excel file. Please make sure the file is valid.'])->withInput();
                }

                if ($merchant) {
                   
                    $min_payout = $merchant->min_payout;
                    $max_payout = $merchant->max_payout;

                    $setting_min_payout = Setting::where('key','min_payout')->first();
                    $setting_max_payout = Setting::where('key','max_payout')->first();

                    if ($min_payout == '') {
                        $min_payout = $setting_min_payout->value;
                    }
                    if ($max_payout == '') {
                        $max_payout = $setting_max_payout->value;
                    }

                }


                $payout_count = $data->count();
                $header = array_map('strtolower',$excel_heaing); 
                $expectedColumns = ['SRNO', 'BENENAME', 'NEFT', 'ACCOUNT_NUMBER', 'AMOUNT', 'IFSC_CODE', '91BENEMOBILE_NUMBER', 'DESCRIPTION', ""];
                $expectedColumns = array_map('strtolower', $expectedColumns); 
                $missingColumns = array_diff($expectedColumns, $header);
                $string = strtoupper($this->random_strings(8));

                if (empty($missingColumns)) {
                    $ext = $file->getClientOriginalExtension();

                    $bulkFile = new Bulk_Files();
                    $bulkFile->name = $fileName;
                    $bulkFile->type = $ext;
                    $bulkFile->path = $filePath;
                    $bulkFile->user_id = $user_id;
                    $bulkFile->data_time_schedular = $date_time;
                    $bulkFile->total_payout	= $payout_count;
                    $bulkFile->total_amount =  $totalAmount;
                    if ($totalAmount < $min_payout ) {
                        $bulkFile->status = '2';
                        $bulkFile->remarks = 'Payin Limit subceed';
                    }elseif ($totalAmount > $max_payout) {
                        $bulkFile->status = '2';
                        $bulkFile->remarks = 'Payout Limit exceed';
                    }else{
                        $bulkFile->status = '3';
                    }
                    $bulkFile->request_id = $string;
                    $bulkFile->save();



                    if ($bulkFile->id) {
                        $currency_id = Auth::user()->currency_id;
                        $payout_fiat = Wallet::where('user_id', $user_id)->where('currency_id', $currency_id)->value('payout_fiat');
                     
                        $payout = new Payout();


                        if ($min_payout != '') {
                            if ($totalAmount < $min_payout ) {
                                $payout->user_id = $user_id;
                                $payout->payout_id = $string;
                                $payout->transactionable_type = 'Bulk Transfer';
                                $payout->gross = $totalAmount;
                                $payout->fee = 0.00;
                                $payout->net = $totalAmount;
                                $payout->money_flow = '-';
                                $payout->currency_id = $currency_id;
                                $payout->currency_symbol = ($currency_id == '14') ? 'INR' : 'USD';
                                $payout->transaction_state_id = '2';
                                $payout->balance = $payout_fiat;
                                $payout->save();
                                 flash('Payin Out of limit','danger');
                                return redirect()->back();
                            }
                        }

                        if ($max_payout != '') {
                            if ($totalAmount > $max_payout ) {
                                $payout->user_id = $user_id;
                                $payout->payout_id = $string;
                                $payout->transactionable_type = 'Bulk Transfer';
                                $payout->gross = $totalAmount;
                                $payout->fee = 0.00;
                                $payout->net = $totalAmount;
                                $payout->money_flow = '-';
                                $payout->currency_id = $currency_id;
                                $payout->currency_symbol = ($currency_id == '14') ? 'INR' : 'USD';
                                $payout->transaction_state_id = '2';
                                $payout->balance = $payout_fiat;
                                $payout->save();

                                flash('Payout Out of limit','danger');
                                return redirect()->back();
                            }
                        }
                        if ($payout_fiat >= $totalAmount) {
                            
                            $updated_wallet_balance = $payout_fiat - $totalAmount;
                            $wallet = Wallet::where('user_id', $user_id)->where('currency_id', $currency_id)->first();
                            if($wallet) {
                                $wallet->payout_fiat = $updated_wallet_balance;
                                $wallet->save();
                            }
                            $user_save = User::where('id',$user_id)->first();
                            if($user_save){
                                $user_save->payout_balance = $updated_wallet_balance;
                                $user_save->save();
                            }
                            $deduct_amount = $payout_fiat - $totalAmount;

                            $payout->user_id = $user_id;
                            $payout->payout_id = $string;
                            $payout->transactionable_type = 'Bulk Transfer';
                            $payout->gross = $totalAmount;
                            $payout->fee = 0.00;
                            $payout->net = $totalAmount;
                            $payout->money_flow = '-';
                            $payout->currency_id = $currency_id;
                            $payout->currency_symbol = ($currency_id == '14') ? 'INR' : 'USD';
                            $payout->transaction_state_id = '3';
                            $payout->balance = $deduct_amount;
                       
                        }else {
                            $payout->user_id = $user_id;
                            $payout->payout_id = $string;
                            $payout->transactionable_type = 'Bulk Transfer';
                            $payout->gross = $totalAmount;
                            $payout->fee = 0.00;
                            $payout->net = $totalAmount;
                            $payout->money_flow = '-';
                            $payout->currency_id = $currency_id;
                            $payout->currency_symbol = ($currency_id == '14') ? 'INR' : 'USD';
                            $payout->transaction_state_id = '2';
                            $payout->balance = $payout_fiat;
                        }
                        $payout->save();
                    }else {
                        flash('Something went wrong');
                        return redirect()->back();
                    }     

                    flash('File Uploaded Successfully');
                    return redirect()->back();

                } else {
                    return redirect()->back()->withErrors(['fileToUpload' => 'The Excel file is missing columns: ' . implode(', ', $missingColumns) . '.'])->withInput();
                }
            }
            
            return redirect()->back()->withErrors(['fileToUpload' => 'No file uploaded.'])->withInput();
        }
        
}





   

