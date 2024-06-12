<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\VirtualCardDetails;

class VirtualCard extends Model
{   
    protected $table = 'virtualcards';
    
    protected $fillable = ['user_id','virtual_card_id','currency_id','virtual_card_issuer','created_at','updated_at'];

    public function User(){
        return $this->belongsTo(User::class);
    }

    public function Currency(){
        return $this->belongsTo(\App\Models\Currency::class);
    }

    public function Detail(){
    	return $this->hasOne(VirtualCardDetail::class, 'virtualcard_id');
    }
}
