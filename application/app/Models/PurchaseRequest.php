<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
    protected $table = 'requests';

    protected $fillable = ['merchant_key','ref','data', 'is_expired', 'currency_code', 'currency_id', 'email', 'login_required','ftd_details'];

    public function getDataAttribute($value){
        return json_decode($value);
    }

    public function Transaction(){
        return $this->hasOne(Transaction::class, 'request_id');
    }

    public function Transactions(){
        return $this->hasMany(Transaction::class, 'request_id');
    }

    public function purchaseTransaction(){
        return $this->hasOne(Transaction::class, 'request_id')->where('transactionable_type', Purchase::class);
    }

    public function saleTransaction(){
        return $this->hasOne(Transaction::class, 'request_id')->where('transactionable_type', Sale::class);
    }

    public function depositTransaction(){
        return $this->hasOne(Transaction::class, 'request_id')->where('transactionable_type', Deposit::class);
    }

    public function Currency(){
        return $this->belongsTo(Currency::class);
    }

}