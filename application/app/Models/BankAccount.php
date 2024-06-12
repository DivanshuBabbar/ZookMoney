<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class BankAccount extends Model
{
    protected $table = 'bank_accounts';

    public function User() {
        return $this->belongsTo(User::class);
    }
}
