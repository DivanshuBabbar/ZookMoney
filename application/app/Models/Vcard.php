<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Vcard extends Model{
    public function Transactions(){
        return $this->morphMany('App\Models\Transaction', 'Transactionable');
    }
}