<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Bulk_Files extends Model
{
	protected $table = 'bulk_files';

	public function User(){
        return $this->belongsTo(\App\User::class);
    }
}