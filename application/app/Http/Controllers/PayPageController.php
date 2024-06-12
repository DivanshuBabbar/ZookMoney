<?php
namespace App\Http\Controllers;
use Auth;
use App\User;
use Mail;
use App\Models\Deposit;
use App\Models\TransferMethod;
use App\Mail\Deposit\depositCompletedUserNotificationEmail;
use App\Models\Wallet;
use App\Models\Currency;
use App\Models\Transaction;
use Illuminate\Http\Request;
class PayPageController extends Controller
{
    public function index()
    {
        $data = [];
    	return view('deposits.gatepay.Source.example.index',$data);
    }
}