<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyStats extends Model
{
    protected $table = "monthly_stats";

    protected  $fillable =['month', 'value'];
}
