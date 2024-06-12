<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpressCheckoutOtp extends Model
{
    protected $fillable = ['id', 'email', 'otp', 'created_at', 'updated_at'];

    public function getIsExpiredAttribute()
    {
        return $this->created_at->addMinutes(config('app.express_checkout_otp_validity_in_min')) < now();
    }

}
