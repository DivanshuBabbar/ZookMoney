<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class VirtualCardDetail extends Model
{   
    protected $table = 'virtualcarddetails';
    
    protected $fillable = ['virtualcard_id','card_id','hash','pan','masked_pan','city','state','address','address_2','account_id','amount','name_on_card','zip_code','cvv','expiration','card_created_at','send_to','bin_check_name','card_type','is_active','card_is_active','callback_url'];

    public function VirtualCard(){
        return $this->belongsTo(\App\Models\VirtualCard::class);
    }

}
