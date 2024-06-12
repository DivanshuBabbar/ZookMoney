<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Auth;

class userPayoutBlockEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $mail_to;

    public function __construct($mail, $user)
    {
        $this->mail_to = $mail;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
       
        return $this->view('email.userPayoutBlockEmail')->subject('Payout Blocked for '. general_setting('site_name') .' Account')->with('user', $this->user)->to($this->mail_to);
    }
}
