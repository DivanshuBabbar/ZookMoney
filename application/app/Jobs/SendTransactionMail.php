<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\SendTransactionEmailToUsers;
use Mail;
use File;
use Log;
use Illuminate\Support\Carbon;

class SendTransactionMail implements ShouldQueue
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

            $filename =  storage_path("user_docs/TransactionReport.csv");
            $file = fopen($filename, 'w');

            $columns = ['Name','Status','Order Id','Zook Txn Reference No','Unique Txn Reference No','Bank Reference No', 'MID', 'Currency','Type','Money Flow','Gross','MDR','Net','Balance', 'Payin Source', 'Vpa' ,'Currency Symbol','Created At'];
            
            fputcsv($file, $columns);

            Log::info('Writing data into csv.');
            
            foreach ($this->new_data as $key => $value) {
                
                if($value && $value->activity_title){
                    if ($value->activity_title == 'Sale') {
                        $activity_title = 'Upi';
                    }elseif ($value->activity_title == 'Manual Deposit From Dashboard') {
                       $activity_title = 'Wallet Deposit';
                    }else {
                        $activity_title = $value->activity_title;
                    }
                }else{
                    $activity_title = '';
                }

                if ($value->entity_id) {
                    $m_id = bin2hex($value->entity_id);
                    if(strlen($value->entity_id) == 2){
                      $mid = $m_id.'000000000'.$value->entity_id;
                    }elseif(strlen($value->entity_id) == 3){
                      $mid = $m_id.'0000000'.$value->entity_id;
                    }else{
                      $mid = $m_id.'00000'.$value->entity_id;
                    }
                    
                }else{
                    $mid = '' ;
                }

                if($value && $value->Deposits && $value->Deposits->qr_service_payload){
                    $json = json_decode($value->Deposits->qr_service_payload);
                    $payin_source =  $json->name;
                    $vpa =  $json->vpa;

                }else{
                    $payin_source = '';
                    $vpa = '';
                }
                
                $csv_arrray = [
                    "Name" => $value->User->name ?? '',
                    "Status"=> $value->Status->name ?? '',
                    "Order Id"=> $value->transactionable_id ?? '',
                    "Zook Txn Reference No"=> !empty($value->Deposits) && ($value->Deposits->unique_transaction_id) ? $value->Deposits->unique_transaction_id : 'N/A',
                    "Unique Txn Reference No"=> !empty($value->Requests) && ($value->Requests->ref) ? $value->Requests->ref : 'N/A',
                    "Bank Reference No"=> !empty($value->Deposits) && ($value->Deposits->ag_bank_reference_no) ? $value->Deposits->ag_bank_reference_no : 'N/A',
                    "MID"=> $mid ?? '',
                    "Currency"=> $value->Currencie->code ?? '',
                    "Type"=> $activity_title ?? '',
                    "Money Flow"=> $value->money_flow ?? '',
                    "Gross"=> $value->gross ?? '',
                    "MDR"=> $value->fee ?? '',
                    "Net"=> $value->net ?? '',
                    "Balance"=> $value->balance ?? '',
                    "Payin Source"=> $payin_source ?? '',
                    "Vpa"=> $vpa ?? '',
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
                        $message->to($value)->subject('TransactionReport');
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
