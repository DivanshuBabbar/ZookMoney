<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Mail;
use File;
use Log;
use Illuminate\Support\Carbon;

class SendUserDepositMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $new_data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($new_data)
    {
        $this->new_data = $new_data;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {    
        try{

            Log::info('Job started successfully.');

            if (!File::exists(storage_path()."/user_docs")) {
                File::makeDirectory(storage_path() . "/user_docs");
            }

            $filename =  storage_path("user_docs/UserDepositList.csv");
            $file = fopen($filename, 'w');

            $columns = ['Name','Order Id','Zook Txn Reference No','Unique Txn Reference No','Bank Reference No','Currency','Type','Gross','MDR','Net','Balance','Currency Symbol','Created At'];
            
            fputcsv($file, $columns);

            Log::info('Writing data into csv.');
            
            foreach ($this->new_data as $key => $value) {
                
                if (isset($value->activity_title) && $value->activity_title == 'Manual Deposit' ) {
                    $activity_title = 'Upi';
                }elseif (isset($value->activity_title) && $value->activity_title == 'Manual Deposit From Dashboard') {
                    $activity_title = 'Wallet Deposit';
                }else{
                    $activity_title = '';
                }
               

                $csv_arrray = [
                    "Name" => $value->User->name ?? '',
                    "Order Id"=> $value->transactionable_id ?? '',
                    "Zook Txn Reference No"=> !empty($value->Deposits) && ($value->Deposits->unique_transaction_id) ? $value->Deposits->unique_transaction_id : 'N/A',
                    "Unique Txn Reference No"=> !empty($value->Requests) && ($value->Requests->ref) ? $value->Requests->ref : 'N/A',
                    "Bank Reference No"=> !empty($value->Deposits) && ($value->Deposits->ag_bank_reference_no) ? $value->Deposits->ag_bank_reference_no : 'N/A',
                    "Currency"=> $value->Currencie->code ?? '',
                    "Type"=> $activity_title,
                    "Gross"=> $value->gross ?? '',
                    "MDR"=> $value->fee ?? '',
                    "Net"=> $value->net ?? '',
                    "Balance"=> $value->balance ?? '',
                    "Currency Symbol"=> $value->currency_symbol ?? '',
                    "Created At"=>!empty($value->created_at) && ($value->created_at instanceof Carbon)? $value->created_at->timezone('Asia/Kolkata')->format('M j, Y, g:i A') : '-',
                ];

                fputcsv($file, $csv_arrray);
            }
           
            fclose($file);
            
            Log::info('Reading data done.');

            $explode = explode(',',$this->new_data['emails']);

            Log::info('started sending mail.');

            foreach ($explode as $value) {
               try {
                    Mail::send('email.send-report', [], function($message) use ($filename,$value)
                    {
                        $message->to($value)->subject('UserDepositReport');
                        $message->attach($filename);
                    });
                } catch (\Exception $e) {
                    Log::error('Error Mailing job: ' . $e->getMessage());
                }
            }

            Log::info('End sending mail.');
            unlink($filename);

        }catch (\Exception $e) {
             Log::error('Error dispatching job: ' . $e->getMessage());
        }
        
    }
}
