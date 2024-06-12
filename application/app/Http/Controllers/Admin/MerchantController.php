<?php

namespace App\Http\Controllers\Admin;

use \App\Models\Currency;
use \App\Models\Merchant;
use \App\Models\TransferMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Yajra\DataTables\Facades\DataTables;
use \App\User;
use Mail;
use App\Mail\userPayoutBlockEmail;
use App\Jobs\SendMerchantList;
use Log;


class MerchantController extends Controller
{
    public function index()
    {
        if (!request()->ajax()) {
            
            $page_title ="Merchant";
            $active = 'merchant';
            $currencies = Currency::query()->pluck('name', 'id');
            
            return view('admin.merchant.index',compact('active', 'page_title', 'currencies'));
        }

        $data = Merchant::query()->with('User','Currency')
            ->when(!empty(request()->currency_id), function ($query) {
                return $query->where('currency_id', request()->currency_id);
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
                dispatch(new SendMerchantList($new_data));
                Log::debug('Job dispatched successfully.');
            }catch (Exception $e) {
                Log::error('Error dispatching job: ' . $e->getMessage());
            }
        }else{
            $data = $data->latest();
        }
            

        return DataTables::eloquent($data)
            ->addIndexColumn()
            ->editColumn('currency_id', function ($data) {
                return $data->Currency->name;
            })
            ->editColumn('user_id', function ($data) {
                return $data->User->name;
            })
            ->editColumn('user_email', function ($data) {
                return $data->User->email;
            })
            ->editColumn('user_number', function ($data) {
                return $data->User->phonenumber;
            })
            ->editColumn('created_at', function ($data) {
                return !empty($data->created_at) && ($data->created_at instanceof Carbon)? $data->created_at->timezone('Asia/Kolkata')->format('M j, Y, g:i A') : '-';

            })
            ->editColumn('logo', function ($data) {
                return '<img src="'.$data->logo.'" height="40px" width="40px">';
            })
            
            ->addColumn('action', function ($data) {

                $editRoute = route('admin.edit.merchant',['id'=>$data->id]);
                $viewRoute = route('admin.merchant.detail',['id'=>$data->id]);

                $html = '<div class="btn-group">';
                $html .= '<a href="'.$editRoute.'" class="btn btn-primary btn-sm">Edit</a>';
                $html .= '<a href="javascript:void(0)" data-id = "'.$data->id.'" class="btn btn-danger btn-sm delete">Delete</a>';
                $html .= '<a href="'.$viewRoute.'" class="btn btn-warning btn-sm">View</a>';
                $html .= '</div>';

                return $html;
            })

            ->editColumn('file_upload', function ($data) {
                $isChecked = $data->User->file_upload == 1 ? 'checked' : ''; 
                $html = '
                        <label class="switch" for="checkMeOut_'.$data->id.'" style="transform: scale(0.9);">
                        <input type="checkbox" class="merchant_data_toggle" data-type="forFile" id="checkMeOut_'.$data->id.'" data-id='.$data->User->id.' '.$isChecked.'>
                        <span class="slider round"></span>
                    </label>';
              
                return $html;
            })

            ->editColumn('white_label', function ($data) {
                $isLabelChecked = $data->User->white_label_status == 1 ? 'checked' : ''; 
                $html = '
                        <label class="switch" for="checkForLabel_'.$data->id.'" style="transform: scale(0.9);">
                        <input type="checkbox" class="merchant_data_toggle" data-type="forLabel" id="checkForLabel_'.$data->id.'" data-id='.$data->User->id.' '.$isLabelChecked.'>
                        <span class="slider round"></span>
                    </label>';
              
                return $html;
            })

            ->editColumn('wire_transfer', function ($data) {
                $isWireChecked = $data->User->wire_transfer_status == 1 ? 'checked' : '';
                $html = '
                        <label class="switch" for="checkForWire_'.$data->id.'" style="transform: scale(0.9);">
                        <input type="checkbox" class="merchant_data_toggle" data-type="forWire" id="checkForWire_'.$data->id.'" data-id='.$data->User->id.' '.$isWireChecked.'>
                        <span class="slider round"></span>
                    </label>';
              
                return $html;
            })

            ->editColumn('payout_status', function ($data) {
                $payoutChecked = $data->User->payout_status == 1 ? 'checked' : '';
                $html = '
                        <label class="switch" for="payout_'.$data->id.'" style="transform: scale(0.9);">
                        <input type="checkbox" class="merchant_data_toggle" data-type="forPayout" id="payout_'.$data->id.'" data-id="'.$data->User->id.'" '.$payoutChecked.'>
                        <span class="slider round"></span>
                    </label>';
              
                return $html;
            })

            ->editColumn('payin_status', function ($data) {
                $payinChecked = $data->User->payin_status == 1 ? 'checked' : '';
                $html = '
                        <label class="switch" for="payin_'.$data->id.'" style="transform: scale(0.9);">
                        <input type="checkbox" class="merchant_data_toggle" data-type="forPayin" id="payin_'.$data->id.'" data-id="'.$data->User->id.'" '.$payinChecked.'>
                        <span class="slider round"></span>
                    </label>';
              
                return $html;
            })

            ->editColumn('ftd_status', function ($data) {
                $ftdChecked = $data->User->ftd_status == 1 ? 'checked' : '';
                $html = '
                        <label class="switch" for="ftd_'.$data->id.'" style="transform: scale(0.9);">
                        <input type="checkbox" class="merchant_data_toggle" data-type="forftd" id="ftd_'.$data->id.'" data-id="'.$data->User->id.'" '.$ftdChecked.'>
                        <span class="slider round"></span>
                    </label>';
              
                return $html;
            })

           ->addColumn('perform_actions', function ($data) {
                $isChecked = $data->User->file_upload == 1 ? 'checked' : ''; 
                $isLabelChecked = $data->User->white_label_status == 1 ? 'checked' : ''; 
                $isWireChecked = $data->User->wire_transfer_status == 1 ? 'checked' : '';
                $payoutChecked = $data->User->payout_status == 1 ? 'checked' : '';
                $payinChecked = $data->User->payin_status == 1 ? 'checked' : '';
                $ftdChecked = $data->User->ftd_status == 1 ? 'checked' : '';
            
                $html = '<div class="dropdown">';
                $html .= '<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                $html .= '...';
                $html .= '</button>';
                $html .= '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                
                // File Upload Switch
                $html .= '<div class="dropdown-item">';
                $html .= '<label for="checkMeOut_'.$data->id.'" style="margin-right: 5px;">File Upload</label>';
                $html .= '<label class="switch" for="checkMeOut_'.$data->id.'" style="transform: scale(0.6);margin-left:14px">';
                $html .= '<input type="checkbox" class="merchant_data_toggle" data-type="forFile" id="checkMeOut_'.$data->id.'" data-id='.$data->User->id.' '.$isChecked.'>';
                $html .= '<span class="slider round"></span>';
                $html .= '</label>';
                $html .= '</div>';
                
                //White label
                $html .= '<div class="dropdown-item">';
                $html .= '<label for="checkForLabel_'.$data->id.'" style="margin-right: 5px;">White Label</label>';
                $html .= '<label class="switch" for="checkForLabel_'.$data->id.'" style="transform: scale(0.6);margin-left:11px;">';
                $html .= '<input type="checkbox" class="merchant_data_toggle" data-type="forLabel" id="checkForLabel_'.$data->id.'" data-id='.$data->User->id.' '.$isLabelChecked.'>';
                $html .= '<span class="slider round"></span>';
                $html .= '</label>';
                $html .= '</div>';

                //wire transfer
                $html .= '<div class="dropdown-item">';
                $html .= '<label for="checkForWire_'.$data->id.'" style="margin-right: 5px;">Wire Transfer</label>';
                $html .= '<label class="switch" for="checkForWire_'.$data->id.'" style="transform: scale(0.6);">';
                $html .= '<input type="checkbox" class="merchant_data_toggle" data-type="forWire" id="checkForWire_'.$data->id.'" data-id='.$data->User->id.' '.$isWireChecked.'>';
                $html .= '<span class="slider round"></span>';
                $html .= '</label>';
                $html .= '</div>';


                // Payout Switch
                $html .= '<div class="dropdown-item">';
                $html .= '<label for="payout_'.$data->id.'" style="margin-right: 5px; ">Payout</label>';
                $html .= '<label class="switch" for="payout_'.$data->id.'" style="transform: scale(0.6); margin-left:44px;">';
                $html .= '<input type="checkbox" class="merchant_data_toggle" data-type="forPayout" id="payout_'.$data->id.'" data-id="'.$data->User->id.'" '.$payoutChecked.'>';
                $html .= '<span class="slider round"></span>';
                $html .= '</label>';
                $html .= '</div>';
            
                // Payin Switch
                $html .= '<div class="dropdown-item">';
                $html .= '<label for="payin_'.$data->id.'" style="margin-right: 5px;">Payin</label>';
                $html .= '<label class="switch" for="payin_'.$data->id.'" style="transform: scale(0.6);margin-left:55px;">';
                $html .= '<input type="checkbox" class="merchant_data_toggle" data-type="forPayin" id="payin_'.$data->id.'" data-id="'.$data->User->id.'" '.$payinChecked.'>';
                $html .= '<span class="slider round"></span>';
                $html .= '</label>';
                $html .= '</div>';

                $html .= '<div class="dropdown-item">';
                $html .= '<label for="ftd_'.$data->id.'" style="margin-right: 5px;">FTD</label>';
                $html .= '<label class="switch" for="ftd_'.$data->id.'" style="transform: scale(0.6);margin-left:55px;">';
                $html .= '<input type="checkbox" class="merchant_data_toggle" data-type="forftd" id="ftd_'.$data->id.'" data-id="'.$data->User->id.'" '.$ftdChecked.'>';
                $html .= '<span class="slider round"></span>';
                $html .= '</label>';
                $html .= '</div>';

            
                $html .= '</div>'; 
                $html .= '</div>'; 
            
                return $html;
            })
            ->rawColumns(['logo','action','perform_actions','user_email','file_upload' ,'white_label','wire_transfer','payout_status','payin_status','ftd_status','user_number'])
            ->toJson();
    }
    public function edit($id =null)
    {
        $data['merchant'] = Merchant::findorFail($id);
        $data['active'] = 'merchant';
        $data['status'] = ['In-principle Approval'=>'In-principle Approval','Approved'=>'Approved','Rejected'=>'Rejected'];
        $data['time_status'] = ['T+0'=>'0','T+1'=>'24','T+2'=>'48','T+3'=>'72'];
         return view('admin.merchant.edit_merchant',$data);
    }
    public function update(Request $request)
    {
        $merchant = Merchant::find($request->id);
        $merchant->currency_id = isset($request->currency_id) ? $request->currency_id:'';
        $merchant->merchant_Key = isset($request->merchant_Key) ? $request->merchant_Key:'';
        $merchant->site_url = isset($request->site_url) ? $request->site_url:'';
        $merchant->success_link = isset($request->success_link) ? $request->success_link:'';
        $merchant->fail_link = isset($request->fail_link) ? $request->fail_link:'';
        $merchant->logo = isset($request->logo) ? $request->logo:'';
        $merchant->name = isset($request->name) ? $request->name:'';
        $merchant->description = isset($request->description) ? $request->description:'';
        $merchant->json_data = isset($request->json_data) ? $request->json_data:'';
        $merchant->thumb = isset($request->thumb) ? $request->thumb:'';
        $merchant->user_id = isset($request->user_id) ? $request->user_id:'';
        $merchant->merchant_fixed_fee = isset($request->merchant_fixed_fee) ? $request->merchant_fixed_fee:'';
        $merchant->merchant_percentage_fee = isset($request->merchant_percentage_fee) ? $request->merchant_percentage_fee:'';
        $merchant->status = isset($request->merchant_status) ? $request->merchant_status :'';
        $merchant->payout_fixed_fee = isset($request->payout_fixed_fee) ? $request->payout_fixed_fee:'';
        $merchant->payout_percentage_fee = isset($request->payout_percentage_fee) ? $request->payout_percentage_fee:'';
        $merchant->wire_transfer_fixed_fee = isset($request->wire_transfer_fixed_fee) ? $request->wire_transfer_fixed_fee:'';
        $merchant->wire_transfer_percentage_fee = isset($request->wire_transfer_percentage_fee) ? $request->wire_transfer_percentage_fee:'';
        $merchant->min_payin = isset($request->min_payin) ? $request->min_payin:'';
        $merchant->max_payin = isset($request->max_payin) ? $request->max_payin:'';
        $merchant->min_payout = isset($request->min_payout) ? $request->min_payout:'';
        $merchant->max_payout = isset($request->max_payout) ? $request->max_payout:'';
        $merchant->min_ftd_count = isset($request->min_ftd_count) ? $request->min_ftd_count:'';



        $count = Merchant::where('user_id',$merchant->user_id)->count();
        if ($count >= 1) {
           Merchant::where('user_id', $merchant->user_id)
           ->update([
               'time_status' => isset($request->time_status) ? $request->time_status:''
            ]);
        }
        $merchant->update();
       
        flash('Updated Successfully','success');
        return redirect()->route('admin.merchant.list');
    }
    public function delete($id = null)
    {
        $merchant = Merchant::findorFail($id);
        $merchant->delete();
        flash('Deleted Successfully','danger');
        return redirect()->back();
    }
    public function detail($id =null)
    {
       $data['merchant'] = Merchant::findorFail($id);
       $data['active'] = 'merchant';
        return view('admin.merchant.detail_view',$data);
    }

    public function bulkfileupload(Request $request)
    {
        $merchant_id = $request->merchant_id;
        $checked = $request->checked;
        $type = $request->type;

        $user = User::findorFail($merchant_id);
        if ($user) {
             if ($type == 'forLabel') {
                $user->white_label_status = $checked;
            }elseif ($type == 'forFile') {
                $user->file_upload = $checked;
            }elseif ($type == 'forftd') {
                $user->ftd_status = $checked;
            }elseif ($type == 'forWire') {
                $user->wire_transfer_status = $checked;
            }elseif ($type == 'forPayout') {
                $user->payout_status = $checked;
                if ($checked == 0) {
                    $value = 'alliance@zookpe.com';
                     Mail::send(new userPayoutBlockEmail($value, $user));
                }
            }elseif ($type == 'forPayin') {
                $user->payin_status = $checked;
            }
            
            $user->update(); 
            return response()->json(['message'=>'success'],200);
        }else{
            return response()->json(['message'=>'user not found'],404);
        }
    }
    
}
