<?php

namespace App\Http\Controllers\Admin;
use \Illuminate\Http\Request;
use \App\Http\Controllers\Controller;
use App\Models\WithdrawalMethod;
use App\Models\Currency;
use App\Models\Setting;
use Yajra\DataTables\Facades\DataTables;

class WithdrawMethodController extends Controller
{
    public function index()
    {
        if (!request()->ajax()) {
            $page_title ="Withdraw Method";
            $active ="withdraw_method";
            return view('admin.withdraw-method.index',compact('page_title','active'));
        }

        $data = WithdrawalMethod::query()->with('currencies')
                ->when(isset(request()->status) && is_numeric(request()->status), function ($query) {
                    return $query->where('status', request()->status);
                })
                ->when(isset(request()->is_eligible) && is_numeric(request()->is_eligible), function ($query) {
                    return $query->where('is_eligible', request()->is_eligible);
                });
        
        return DataTables::eloquent($data)
            ->addIndexColumn()
            ->editColumn('status', function ($data) {

                if($data->status == 1) {
                    $color = 'success';
                    $name = 'Active';
                } else {
                    $color = 'warning';
                    $name = 'Inactive';
                }
                return '<span class="badge badge-'.$color.'" >'.$name.'</span>';
            })
            ->editColumn('is_eligible', function ($data) {
                
                if($data->is_eligible == 1) {
                    $color = 'success';
                    $name = 'Yes';
                } else {
                    $color = 'warning';
                    $name = 'No';
                }
                return '<span class="badge badge-'.$color.'" >'.$name.'</span>';
            })
            
            ->addColumn('action', function ($data) {

                $editRoute = route('admin.withdraw.method.edit', $data->id);

                $html = '<div class="btn-group">';
                $html .= '<a href="'.$editRoute.'" class="btn btn-primary btn-sm">
                            <span class="hidden-xs hidden-sm">Edit</span>
                        </a>';
                if (auth()->user()->role_id == 1) {
                    $html .= '<a href="javascript:void(0)" data-id="'.$data->id.'" class="btn btn-danger btn-sm delete">Delete</a>';
                }
                if (auth()->user()->role_id == 4) {
                    $html .= '<a href="javascript:void(0)" class="btn btn-danger btn-sm"> You can\'t delete</a>';
                }
                $html .= '</div>';
                return $html;
            })
            ->rawColumns(['status', 'is_eligible','action'])
            ->toJson();
                    
    }


    public function create()
    {
        $page_title ="Create Withdraw Method";
        $active ="withdraw_method";
        $withdraw = WithdrawalMethod::all();
        $currency = Currency::all();
       
        return view('admin.withdraw-method.create',compact('page_title','active','currency'));
    }

    public function storeTerms(request $request)
    {
        $value = $request->all();
        $flag = 0;
        foreach($value['setting'] as $key => $value)
        {
            $setting = Setting::where('key',$key)->first();
            if(empty($setting))
            {
                $setting = new Setting();
            }
            $setting->value =  $value;
            $setting->key = $key;
            $setting->save();
            if($setting->id)
            {
                $flag = 1;
            }
        }
        if($flag == 1)
        {
            flash('Done Successfully!','success');
        }
        else
        {
            flash('Error Please try again!','error');
        }
        return redirect()->route('admin.withdraw.method.list');
    }

    public function store(Request $request){
        
        $id = $request->id;
        if(isset($id) && $id !=null)
        {
            $withdraw = WithdrawalMethod::findorFail($id);
            $withdraw->name = $request->input('name');
            $withdraw->currency_id = $request->input('currency_id');
            $withdraw->status = $request->input('status');

            $withdraw->is_eligible = isset($request->is_eligible) ? $request->is_eligible: 1;
            $withdraw->sequence_no = isset($request->sequence_no) ? $request->sequence_no: 0;

            $withdraw->update();
            flash('Updated Successfully!','success');
        }
        else
        {
            $withdraw = new WithdrawalMethod();
            $withdraw->name = $request->input('name');
            $withdraw->currency_id = $request->input('currency_id');
            $withdraw->status = $request->input('status');

            $withdraw->is_eligible = isset($request->is_eligible) ? $request->is_eligible: 1;
            $withdraw->sequence_no = isset($request->sequence_no) ? $request->sequence_no: 0;

            $withdraw->save();
            flash('Added Successfully!','success');
        }
        
       
       
      return redirect()->route('admin.withdraw.method.list');
    }

    public function edit($id =null)
    {
        $data['withdraw']  = WithdrawalMethod::findorFail($id);
         $data['page_title'] ="Edit Withdraw Method";
         $data['active'] ="withdraw_method";
         $data['currency'] = Currency::all();
        return view('admin.withdraw-method.create',$data);

    }


    public function update(){
      
        flash('Delete Successfully!','danger');
         return redirect()->route('admin.withdraw.method.list');
    }
     public function  delete($id = null)
    {     
         $withdraw = WithdrawalMethod::findorFail($id);
        //    dd($withdraw);
         $withdraw->delete();
         flash('Delete Successfully!','success');
         return redirect()->route('admin.withdraw.method.list');
    }
}