<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionLogs extends Model
{   
    protected $table = 'transaction_logs';
    
    protected $fillable = [
    	'user_id',
    	'ip',
    	'user_agent',
    	'referrer',
    	'page_link',
    	'amount',
    	'action',
    	'message',
    	'created_at',
    	'updated_at'
    ];

}
