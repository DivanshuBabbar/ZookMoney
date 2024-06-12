<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class PaymentLink extends Model
{   
    protected $table = 'paymentlinks';
    
    protected $fillable = [
    	'user_id',
    	'paymentlink_id',
    	'currency_id',
    	'paymentlink_details',
    	'email',
    	'is_crypto',
    	'payment_status',
    	'link_status',
    	'link',
        'amount',
    	'ipn_url',
    	'name',
    	'created_at',
    	'updated_at'
    ];

    public function User(){
        return $this->belongsTo(User::class);
    }

    public function Currency(){
        return $this->belongsTo(\App\Models\Currency::class);
    }
}
