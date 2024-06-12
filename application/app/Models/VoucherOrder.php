<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class VoucherOrder extends Model
{
	protected $table = 'voucherorders';

    protected $fillable = ['user_id','voucher_id','currency_id','amount','fee','total','is_crypto','order_status','payment_method','out_transaction_id','state', 'vorderid'];


}
