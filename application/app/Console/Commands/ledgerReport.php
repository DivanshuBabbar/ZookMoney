<?php
 
namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\User;
use \App\Models\Transaction;
use Mail;
use File;
use Log;
use Illuminate\Support\Carbon;


class ledgerReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ledger:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send ledger report to merchant';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {   
        try {
            
            Log::info('cron job started.');

            if (!File::exists(storage_path()."/user_docs")) {
                File::makeDirectory(storage_path() . "/user_docs");
            }

            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now();
         
            $users = User::where('is_merchant', '1')->get()->toArray();
            $chunks = array_chunk($users, 10);
            
            if ($chunks) {
                $email = '';
                $csv_arrray = [];
                foreach ($chunks as $key => $user) {
                    foreach ($user as $key => $user_value) {
                        
                        Log::info('cron job started for user.' .$user_value['email']);

                        $a = ['Purchase','Manual Deposit'];

                        $email = $user[$key]['email'];
                        $data = Transaction::with('Deposits','Requests')->where('user_id', '=' , $user_value['id'])->whereBetween('created_at', [$start->format('Y-m-d')." 00:00:00", $end->format('Y-m-d')." 23:59:59"])->whereNotIn('activity_title',$a)->get();

                        if($data->count() === 0){
                            continue;
                        }

                        $filename =  storage_path("user_docs/".$user_value['name']."_ledgerReport.csv");
                        $file = fopen($filename, 'w');
                        
                        $columns = ['Name','Status','Order Id','Zook Txn Reference No','Unique Txn Reference No','Bank Reference No','Currency','Type','Money Flow','Gross','MDR','Net','Balance','Currency Symbol','Created At'];

                        fputcsv($file, $columns);


                        foreach ($data as $key => $value) {
                            if ($value->activity_title == 'Sale') {
                                $activity_title = 'Upi';
                            }elseif ($value->activity_title == 'Manual Deposit From Dashboard') {
                               $activity_title = 'Wallet Deposit';
                            }else {
                                $activity_title = $value->activity_title;
                            }

                            $csv_arrray = [
                                "Name" => $user_value['name'] ?? '',
                                "Status"=> $value->transaction_state_id == 1 ? 'Completed' : (($value->transaction_state_id == 2) ? 'Canceled' : 'Pending'),
                                "Order ID"=> $value->transactionable_id ?? '',
                                "Zook Txn Reference No"=> !empty($value->Deposits) && ($value->Deposits->unique_transaction_id) ? $value->Deposits->unique_transaction_id : 'N/A',
                                "Unique Txn Reference No"=> !empty($value->Requests) && ($value->Requests->ref) ? $value->Requests->ref : 'N/A',
                                "Bank Reference No"=> !empty($value->Deposits) && ($value->Deposits->ag_bank_reference_no) ? $value->Deposits->ag_bank_reference_no : 'N/A',
                                "Currency"=> $value->currency_symbol ?? '',
                                "Type"=> $activity_title ?? '',
                                "Money Flow"=> $value->money_flow ?? '',
                                "Gross"=> $value->gross ?? '',
                                "Fee"=> $value->fee ?? '',
                                "Net"=> $value->net ?? '',
                                "Balance"=> $value->balance ?? '',
                                "Currency Symbol"=> $value->currency_symbol ?? '',
                                "Created At"=>!empty($value->created_at) && ($value->created_at instanceof Carbon)? $value->created_at->timezone('Asia/Kolkata')->format('M j, Y, g:i A') : '-',
                            ];
               
                            fputcsv($file, $csv_arrray);
                        }

                        Log::info('data logged for user.'.$user_value['email']);

                        fclose($file);

                        try {

                            Log::info('mail sent to user.'.$user_value['email']);
                            
                            Mail::send('email.send-report', [], function($message) use ($filename,$email)
                            {
                                $message->to($email)->subject('LederReport');
                                $message->attach($filename);
                            });
                        } catch (\Exception $e) {
                            Log::error('Error Mailing job: ' . $e->getMessage());
                        }

                        unlink($filename);
                    }
                }
            }
        } catch (Exception $e) {
            Log::error('Error Mailing job: ' . $e->getMessage());
            
        }
    }
}
// 30 23 28-31 * * [ "$(date +\%m -d tomorrow)" != "$(date +\%m)" ] && php artisan ledger:report