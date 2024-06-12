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

class SendMerchantList implements ShouldQueue
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
    public function handle(){

        try{

            Log::info('Job started successfully.');

            if (!File::exists(storage_path()."/user_docs")) {
                File::makeDirectory(storage_path() . "/user_docs");
            }

            $filename =  storage_path("user_docs/MerchantList.csv");
            $file = fopen($filename, 'w');

            $columns = ['Id','Name','Email','Mobile Number','Site Url','Status','Fixed Fee','Percentage Fee','Payout Fixed Fee','Payout Percentage Fee','Wire Transfer Fixed Fee','Wire Transfer Percentage Fee','Bulk Payout','White Label','Wire Transfer','Payout Status','Payin Status','Created At'];

            fputcsv($file, $columns);

            Log::info('Writing data into csv.');

            foreach ($this->new_data as $key => $value) {
               
                if (empty($value->User) || $value->User == '') {
                  continue;
                }

                if($value && $value->User){

                    $file_upload = (isset($value->User->file_upload)&& $value->User->file_upload == 1)? 'yes':'no';
                    $white_label_status = (isset($value->User->white_label_status)&& $value->User->white_label_status == 1)? 'yes':'no';
                    $wire_transfer_status = (isset($value->User->wire_transfer_status)&& $value->User->wire_transfer_status == 1)? 'yes':'no';
                    $payout_status = (isset($value->User->payout_status)&& $value->User->payout_status == 1)? 'yes':'no';
                    $payin_status = (isset($value->User->payin_status)&& $value->User->payin_status == 1)? 'yes':'no';
                }else{
                    $file_upload = ''; $white_label_status = ''; $wire_transfer_status = ''; $payout_status = ''; $payin_status = '';
                }

                $csv_arrray = [
                    "Id" => $value->id ?? '',
                    "Name"=> $value->name ?? '',
                    "Email"=> $value->User->email ?? '',
                    "Mobile Number" => $value->User->phonenumber ?? '',
                    "Site Url"=> $value->site_url ?? '',
                    "Status"=>  $value->status ?? '',
                    "Fixed Fee"=> $value->merchant_fixed_fee ?? '',
                    "Percentage Fee"=>$value->merchant_percentage_fee ?? '',
                    "Payout Fixed Fee"=>$value->payout_fixed_fee ?? '',
                    "Payout Percentage Fee"=>$value->payout_percentage_fee ?? '',
                    "Wire Transfer Fixed Fee"=>$value->wire_transfer_fixed_fee ?? '',
                    "Wire Transfer Percentage Fee"=>$value->wire_transfer_percentage_fee ?? '',
                    "Bulk Payout"=>  $file_upload ?? '',
                    "White Label"=> $white_label_status ?? '',
                    "Wire Transfer"=> $wire_transfer_status ?? '',
                    "Payout Status"=> $payout_status ?? '',
                    "Payin Status"=> $payin_status ?? '',
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
                        $message->to($value)->subject('MerchantList');
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