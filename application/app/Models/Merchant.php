<?php

namespace App\Models;
use App\User;
use Storage;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    protected  $fillable =[
        'user_id', 
        'thumb', 
        'name', 
        'site_url', 
        'success_link', 
        'fail_link', 
        'logo', 
        'description', 
        'json_data', 
        'merchant_key', 
        'currency_id', 
        'ipn_url', 
        'merchant_fixed_fee', 
        'merchant_percentage_fee',
        'payout_fixed_fee',
        'payout_percentage_fee',
        'wire_transfer_fixed_fee',
        'wire_transfer_percentage_fee',
        
    ];

    public function User() {
        return $this->belongsTo(User::class);
    }

    public function Currency() {
        return $this->belongsTo(Currency::class);
    }

    public function getLogoAttribute($value){
    	if( $value ) return $value ;

    	return Storage::url('users/default.png') ; 
    }

    public function getMerchantFixedFeeAttribute($value) {

        if (empty($value)) return general_setting('merchant_fixed_fee');
        
        return $value;
    }
    public function getMerchantPercentageFeeAttribute($value) {
        
        if (empty($value)) return general_setting('merchant_percentage_fee');
        
        return $value;
    }
}
