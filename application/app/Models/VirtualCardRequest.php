<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class VirtualCardRequest extends Model
{   
    protected $table = 'virtualcardrequests';
    
    protected $fillable = ['user_id','virtualcard_id', 'virtualcarddetail_id','currency_id','virtual_card_issuer', 'request_resolution','created_at','updated_at'];

    public function User(){
        return $this->belongsTo(User::class);
    }

    public function Currency(){
        return $this->belongsTo(\App\Models\Currency::class);
    }
}
