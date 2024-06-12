<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \App\User;
use \App\Models\Payout;
use Mail;
use File;
use Log;
use Illuminate\Support\Carbon;


class PayoutReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payout:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send payout report to merchant';

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

                        $email = $user[$key]['email'];
                        $data = Payout::where('user_id', '=' , $user_value['id'])->whereBetween('created_at', [$start->format('Y-m-d')." 00:00:00", $end->format('Y-m-d')." 23:59:59"])->get();


                        if($data->count() === 0){
                            continue;
                        }
                        
                        $filename =  storage_path("user_docs/".$user_value['name']."_PayoutReport.csv");
                        $file = fopen($filename, 'w');
                        
                        $columns = ['Name','Status','Payout ID','Currency','Money Flow','Gross','Fee','Net','Balance','Currency Symbol','Created At'];

                        fputcsv($file, $columns);

                        foreach ($data as $key => $value) {
                            $csv_arrray = [
                                "Name" => $user_value['name'] ?? '',
                                "Status"=> $value->transaction_state_id == 1 ? 'Completed' : (($value->transaction_state_id == 2) ? 'Canceled' : 'Pending'),
                                "Payout ID"=> $value->payout_id ?? '',
                                "Currency"=> $value->currency_symbol ?? '',
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
                                $message->to($email)->subject('PayoutReport');
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
// 30 23 28-31 * * [ "$(date +\%m -d tomorrow)" != "$(date +\%m)" ] && php artisan payout:report