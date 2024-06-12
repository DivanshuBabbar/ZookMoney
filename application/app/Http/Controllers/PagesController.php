<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class PagesController extends Controller
{
    public function welcome() {
        return view('pages.welcome');
    }

    public function aboutUs() {
        return view('pages.about_us');
    }

    public function service(){
        return view('pages.service');
    }

    public function contactUs(){
        return view('pages.contact_us');
    }
    
    public function termsOfUse(){
        return view('pages.terms_of_use');
    }

    // public function tutorials (){
    //     return view('pages.tutorials');
    // }

    public function privacyPolicy (){
        return view('pages.privacy_policy');
    }

    public function faq (){
        return view('pages.faq');
    }
}
