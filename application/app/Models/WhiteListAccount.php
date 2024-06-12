<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class WhiteListAccount extends Model
{
    protected $table = 'white_list_accounts';

    public function User() {
        return $this->belongsTo(User::class);
    }
}
