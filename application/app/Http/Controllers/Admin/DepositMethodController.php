<?php

namespace App\Http\Controllers\Admin;

use \App\Models\DepositMethod;
use \App\Models\Currency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class DepositMethodController extends Controller
{
    public function index()
    {
        if (!request()->ajax()) {
            $data['active'] = 'deposit_method';
            $data['page_title'] = 'Deposit Method';
            return view('admin.deposit_method.deposit_method_list',$data);
        }

        $data = DepositMethod::query()->with('currency')
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

                $editRoute = route('admin.edit.deposit.method',['id'=>$data->id]);

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

    public function add()
    {
        $data['active'] = 'depoist_method';
        $data['page_title'] = 'Add Deposit Method';
        $data['currencies'] = Currency::all();
        return view('admin.deposit_method.add_method',$data);
    }

    public function save(Request $request)
    {
        $id = $request->id;
        if(isset($id) && $id !='')
        {
            $method = DepositMethod::findorFail($id);
            $method->name = isset($request->name) ? $request->name:'';
            $method->currency_id = isset($request->currency_id) ? $request->currency_id:'';
            $method->status = isset($request->status) ? $request->status:'';
            $method->detail = isset($request->detail) ? $request->detail:'';

            $method->is_eligible = isset($request->is_eligible) ? $request->is_eligible: 1;
            $method->sequence_no = isset($request->sequence_no) ? $request->sequence_no: 0;
            $method->transaction_receipt_ref_no_format = isset($request->transaction_receipt_ref_no_format) 
                                                            ? $request->transaction_receipt_ref_no_format: null;

            $method->update();
            flash('Update Successfully','success');
        }
        else
        {
            $method = new DepositMethod;
            $method->name = isset($request->name) ? $request->name:'';
            $method->currency_id = isset($request->currency_id) ? $request->currency_id:'';
            $method->status = isset($request->status) ? $request->status:'';
            $method->detail = isset($request->detail) ? $request->detail:'';

            $method->is_eligible = isset($request->is_eligible) ? $request->is_eligible: 1;
            $method->sequence_no = isset($request->sequence_no) ? $request->sequence_no: 0;
            $method->transaction_receipt_ref_no_format = isset($request->transaction_receipt_ref_no_format) 
                                                            ? $request->transaction_receipt_ref_no_format: null;

            $method->save();
            flash('Add Successfully','success');
        }
      
        return redirect()->route('admin.deposit.method.list');
    }
    public function edit($id =null)
    {
        //echo $id;die();
        $data['active'] = 'depoist_method';
        $data['page_title'] = 'Edit Deposit Method';
        $data['deposit_method']  = DepositMethod::findorFail($id);
        $data['currencies'] = Currency::all();
        return view('admin.deposit_method.add_method',$data);
    }
    public function delete($id =null)
    {
        
        $deposit_method = DepositMethod::findorFail($id);
        $deposit_method->delete();
        flash('Deleted Successfully','success');
        return back();
    }

    
}
