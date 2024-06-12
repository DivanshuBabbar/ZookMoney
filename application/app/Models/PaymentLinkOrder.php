<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\PaymentLink;

class PaymentLinkOrder extends Model
{   
    protected $table = 'paymentlinkorders';
    
    protected $fillable = [
    	'paymentlink_id',
    	'paymentlink_reference',
    	'link_paid_by_platform',
    	'email',
    	'json_data',
    	'order_state',
    	'order_status',
    	'created_at',
    	'updated_at',
    ];

    public function PaymentLink(){
        return $this->belongsTo(\App\Models\PaymentLink::class);
    }


    public function Transactions(){
        return $this->morphMany('App\Models\Transaction', 'Transactionable');
    }

}
