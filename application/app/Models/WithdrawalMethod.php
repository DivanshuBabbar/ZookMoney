<?php

namespace App\Models;
use App\Models\Currency;
use Storage;
use Illuminate\Database\Eloquent\Model;

class WithdrawalMethod extends Model
{
    
    protected $fillable = ['name','percentage_fee','fixed_fee','json_data','created_at','updated_at','thumb', 'is_eligible', 'sequence_no'];
    
    public function getThumbAttribute($value){
    	if ($value) {
    		return $value;
    	}

    	return Storage::url('users/default.png');
    }

	public function currencies(){
		return $this->belongsTo(Currency::class,'currency_id');
	}
}