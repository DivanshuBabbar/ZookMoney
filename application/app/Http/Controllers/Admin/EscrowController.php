<?php

namespace App\Http\Controllers\Admin;

use \App\Models\Escrow;
use \App\Models\Merchant;
use \App\Models\TransferMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EscrowController extends Controller
{
    public function index()
    {

    	$data['escrow'] = Escrow::latest()->paginate(10);
        $data['active'] = 'escrow';
         return view('admin.escrows.index',$data);
    }
    
}
