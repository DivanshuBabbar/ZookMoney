<?php
namespace App\Mail;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
class GiftCardEmail extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $mail_to,$card_number,$pin_code;
    public function __construct($mail,$card_number,$pin_code)
    {
        $this->mail_to = $mail;
        $this->card_number = $card_number;
        $this->pin_code = $pin_code;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.giftcard_email')->subject('Gift Card '. general_setting('site_name') .' Account')->with('card_number',$this->card_number)->with('pin_code',$this->pin_code)->to($this->mail_to);
    }
}
