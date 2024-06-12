<?php
namespace App\Http\Controllers;
use App\User;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\AirtimeTransaction;
use App\Models\Transaction;
use Session;
use Image;
use File;
use Validator;
use App\Models\Currency;
use App\Models\Wallet;
use App\Helpers\Money;
class BillpageController extends Controller
{
    public function __construct()
    {
        $this->currency = "ngn";
    }
    public function index()
    {
        $data['page_title'] = "Bill Payments";
        return view('service.index', $data);
    }

}