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

class SendDepositMail implements ShouldQueue
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

            $filename =  storage_path("user_docs/DepositList.csv");
            $file = fopen($filename, 'w');

            $columns = ['User ID','Name','Gross','Fee','Net','Transaction Receipt Number','Currency Symbol','Message','Deposit Method','Wallet','Transaction ID','Transaction State','Created At'];
            
            fputcsv($file, $columns);

            Log::info('Writing data into csv.');
            
            foreach ($this->new_data as $key => $value) {
                $csv_arrray = [
                    "User ID" => $value->user_id ?? '',
                    "Name" => $value->User->name ?? '',
                    "Gross"=> $value->gross ?? '',
                    "Fee"=> $value->fee ?? '',
                    "Net"=> $value->net ?? '',
                    "Transaction Receipt Number"=> $value->transaction_receipt_ref_no ?? '',
                    "Currency Symbol"=> $value->currency_symbol ?? '',
                    "Message"=> $value->message ?? 'N/A',
                    "Deposit Method"=> $value->Method->name ?? '',
                    "Wallet"=> $value->wallet_id ?? '',
                    "Transaction ID"=> $value->unique_transaction_id ?? '',
                    "Transaction State"=> $value->Status->name ?? '',
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
                        $message->to($value)->subject('DepositReport');
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
