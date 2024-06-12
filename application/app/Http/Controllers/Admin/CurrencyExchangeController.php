<?php

namespace App\Http\Controllers\Admin;
use \Illuminate\Http\Request;
use \App\Http\Controllers\Controller;
use App\Models\CurrencyExchangeRate;
use App\Models\Currency;

class CurrencyExchangeController extends Controller
{
	public function index(){

        $exchange = CurrencyExchangeRate::with('firstCurrency','secondCurrency')->latest()->paginate(10);
        $active = 'currency_exchange';
        return view('admin.currency-exchange.index',compact('exchange','active'));
    }

    public function create(){
       $page_title ="Add Currency Exchange  ";
         $currency = Currency::all();
         $active = 'currency_exchange';
        return view('admin.currency-exchange.create',compact('currency','active' ,'page_title'));
    }



    public function store(Request $request){

         $request->validate([
            'first_currency_id'  => 'required',
            'second_currency_id'  => 'required',
            'exchanges_to_second_currency_value'  => 'required',
           

        ]);

        
        $exchangerate = new CurrencyExchangeRate();
        $exchangerate->first_currency_id = $request->input('first_currency_id');
        $exchangerate->second_currency_id = $request->input('second_currency_id');
        $exchangerate->exchanges_to_second_currency_value = $request->input('exchanges_to_second_currency_value');
       $active = 'currency_exchange';
        $exchangerate->save();
       flash('Added Successfully!','success');
        return redirect()->route('admin.exchange-rate.list');

    }

    public function edit($id){
       $page_title ="Currency Exchange  ";
        $currency = Currency::all();
        $exchangerate = CurrencyExchangeRate::find($id);
         $active = 'currency_exchange';
        return view('admin.currency-exchange.edit',compact('exchangerate','currency','active','page_title'));

    }


    public function update(Request $request, $id){

        $request->validate([
                    'first_currency_id'  => 'required',
                    'second_currency_id'  => 'required',
                    'exchanges_to_second_currency_value'  => 'required',
                

                ]);

                $exchange = CurrencyExchangeRate::find($id);
                $exchange->first_currency_id = $request->first_currency_id;
                $exchange->second_currency_id= $request->second_currency_id;
                $exchange->exchanges_to_second_currency_value= $request->exchanges_to_second_currency_value;
                $exchange->update();
                flash('Updated Successfully!','success');
                return redirect()->route('admin.exchange-rate.list');
    }



    public function  delete($id = null)
    {
         $exchange = CurrencyExchangeRate::find($id);
         $exchange->delete();
         flash('Deleted Successfully!','danger');
         return redirect()->route('admin.exchange-rate.list');
    }
}