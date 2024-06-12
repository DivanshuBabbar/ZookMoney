<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticketcategory extends Model
{	
	protected $fillable = [
	    'name'
	];

	public function tickets()
	{
	    return $this->hasMany(Ticket::class);
	}
	public function User(){
        return $this->belongsTo(\App\User::class);
    }

	

}