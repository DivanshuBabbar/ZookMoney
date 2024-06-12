<?php

namespace App\Mail;

use App\User;
use App\Models\Merchant;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\PurchaseRequest;
use App\Models\Deposit;


class transactionCompletedUserNotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    
    public $user_id; 

    public function __construct(User $user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $reference = $this->user_id->token;
        $request = PurchaseRequest::where('ref',$reference)->first();
        $deposit = Deposit::where('request_id',$request->id)->first();
        $this->user_id['ag_bank_reference_no'] = $deposit->ag_bank_reference_no;
        $this->user_id['ag_payer_name'] = $deposit->ag_payer_name;
        $this->user_id['token'] = $reference;
        
        return $this->subject('Transaction completed')->view('email.transactionCompletedUserNotificationEmail')->with('user', $this->user_id)->to($this->user_id->email);
    }
}
