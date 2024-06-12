<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Currency;
class ExchangeTransactions extends Model
{
    protected $table = 'exchange_transactions';
   
   public function first_currency_name()
   {
      return $this->belongsTo(Currency::class,'from_currency_id');
   }

   public function second_currency_name()
   {
      return $this->belongsTo(Currency::class,'to_currency_id');
   }
}