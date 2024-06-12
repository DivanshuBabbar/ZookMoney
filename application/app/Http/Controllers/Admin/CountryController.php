<?php

namespace App\Http\Controllers\Admin;
use \Illuminate\Http\Request;
use \App\Http\Controllers\Controller;
use App\Models\Country;


class CountryController extends Controller
{
	public function index(){
        $country = Country::query()->get();
        $active = 'countries';
        return view('admin.countries.index',compact('country','active'));
    }

    public function create(){
        
        $page_title ="Add Country ";
        $active = 'countries';
        return view('admin.countries.create',compact('page_title','active'));
    }



    public function store(Request $request){

        $countries = new Country();
        $countries->code = $request->input('code');
        $countries->name = $request->input('name');
        $countries->nicename = $request->input('nicename');
        $countries->iso3 = $request->input('iso3');
        $countries->numcode = $request->input('numcode');
        $countries->prefix = $request->input('prefix');
        $countries->save();
        flash('Added Successfully!','success');
        return redirect()->route('admin.countries.list');

    }

    public function edit($id){
        $page_title ="Edit Country  ";
        $countries = Country::find($id);
        $active = 'countries';
        return view('admin.countries.edit',compact('countries','page_title','active'));
    }

    public function update(Request $request, $id){

                $country = Country::find($id);
                $country->code = $request->code;
                $country->name = $request->name;
                $country->nicename = $request->nicename;
                $country->iso3 = $request->iso3;
                $country->numcode = $request->numcode;
                $country->prefix = $request->prefix;

                
                $country->update();
                flash('Update Successfully!','success');
                return redirect()->route('admin.countries.list');        
    }

    public function  delete($id = null)
    {
         $countries = Country::find($id);
        //  dd($countries);
         $countries->delete();
         flash('Delete Successfully!','danger');
         return redirect()->route('admin.countries.list');
    }
}