<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{	
	protected $table = 'payout';
    
    protected $fillable = [
        'user_id',
        'payout_id',
        'transaction_state_id',
        'transactionable_type',
        'gross',
        'fee',
        'net',
        'balance',
        'money_flow',
        'currency_id',
        'currency_symbol'
    ];

    public function User(){
        return $this->belongsTo(\App\User::class);
    }

}
