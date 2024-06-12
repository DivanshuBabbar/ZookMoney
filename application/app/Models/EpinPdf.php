<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\User;
class EpinPdf extends Model
{
    protected $table = 'epin_pdf';
    public function user()
    {
        return $this->belongsTo(User::class);
    } 
}