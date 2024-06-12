<?php

namespace App\Http\Controllers\Admin;

use \App\Models\Currency;
use \App\Models\TransferMethod;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CurrencyController extends Controller
{
    public function list (Request $request){
    	return view('notus.currencies.currencies')->with('currencies', Currency::paginate(10));
    }

    public function add(Request $request){
        return view('notus.currencies.add');
    }

    public function create (Request $request){
   
    	$this->validate($request,[
    		'name' => 'required',
    		'type'	=>	'required',
    		'code'	=> 'required',
    		'symbol'	=>	'required',
    	]);

        $currency = Currency::create([
            'name' => $request->name,
            'is_crypto' =>  $request->is_crypto,
            'code'  =>  $request->code,
            'symbol'    =>  $request->symbol
        ]);

        $transferMethod = TransferMethod::create([
            'currency_id' => $currency->id,
            'name' => 'System_'.$currency->name,
            'days_to_process_transfer',
            'is_active'  => 0,
            'is_hidden' => 1,
            'is_system' => 1
        ]);

    	return redirect(url('/').'/administrator/currencies');

    }
    public function currency_list()
    {
        $data['currency'] = Currency::latest()->paginate(10);
        $data['active'] = 'currency';
        return view('admin.currency.currency_list',$data);
    }
    public function add_currency()
    {
        $data['active'] = 'currency';
        return view('admin.currency.add_currency',$data);
    }
    public function store(Request $request)
    {
        $currency = new Currency;
        $currency->name = isset($request->name) ? $request->name:'';
        $currency->symbol = isset($request->symbol) ? $request->symbol:'';
        $currency->code = isset($request->code) ? $request->code:'';
        $currency->is_crypto = isset($request->is_crypto) ? $request->is_crypto:'';
        $currency->thumb = isset($request->thumb) ? $request->thumb:'';
        $currency->save();
        flash('Add Successfully!','success');
        return redirect()->route('admin.currency.list');
    }
    public function edit($id = null)
    {
        $data['active'] = 'currency';
        $data['currency'] = Currency::findorFail($id);
        return view('admin.currency.edit_currency',$data);
    }
     public function update(Request $request)
    {
        $currency = Currency::find($request->id);
        $currency->name = isset($request->name) ? $request->name:'';
        $currency->symbol = isset($request->symbol) ? $request->symbol:'';
        $currency->code = isset($request->code) ? $request->code:'';
        $currency->is_crypto = isset($request->is_crypto) ? $request->is_crypto:'';
        $currency->thumb = isset($request->thumb) ? $request->thumb:'';
        $currency->update();
        flash('Updated Successfully!','success');
        return redirect()->route('admin.currency.list');
    }
    public function delete($id = null)
    {
        $currency = Currency::findorFail($id);
        $currency->delete();
        flash('Deleted Successfully!','danger');
        return redirect()->back();
    }
}
