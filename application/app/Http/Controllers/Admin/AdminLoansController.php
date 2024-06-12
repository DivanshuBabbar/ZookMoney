<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Models\Loans;
use App\Models\RepayLoans;
class AdminLoansController extends Controller
{
    public function approved_loan()
    {
        $page_title = 'Approved Loan';
        $approved_loans = Loans::where('status', 1)->with(['user'])->paginate(15);
        $empty_message = 'No data available';
        return view('vendor.voyager.loan.approved_loan', compact('page_title', 'approved_loans','empty_message'));
    }
    public function pending_loan()
    {
        $page_title = 'Pending Loan';
        $pending_loans = Loans::where('status', 0)->with(['user'])->paginate(15);
        $empty_message = 'No data available';
        return view('vendor.voyager.loan.pending_loan', compact('page_title', 'pending_loans','empty_message'));
    }
    public function rejected_loan()
    {
        $page_title = 'Pending Loan';
        $rejected_loan = Loans::where('status', 2)->with(['user'])->paginate(15);
        $empty_message = 'No data available';
        return view('vendor.voyager.loan.rejected_loan', compact('page_title', 'rejected_loan','empty_message'));
    }
    public function accepted_loan()
    {
        $page_title = 'Accepted Loan';
        $loans = Loans::where('status', 3)->with(['user'])->paginate(15);
        $empty_message = 'No data available';
        return view('vendor.voyager.loan.accepted_loan', compact('page_title', 'loans','empty_message'));
    }
    public function declined_loan()
    {
        $page_title = 'Declined Loan';
        $loans = Loans::where('status', 4)->with(['user'])->paginate(15);
        $empty_message = 'No data available';
        return view('vendor.voyager.loan.declined_loan', compact('page_title', 'loans','empty_message'));
    }
    public function details($language=null,$id=null)
    {
        $page_title = 'Loan Detail';
        $loans_details ='';
        if (!empty($id))
        {
            $loans_details = Loans::with('user')->where(['id'=>$id])->first();
        }
        return view('vendor.voyager.loan.loan_preview', compact('page_title', 'loans_details'));
    }
    public function payment_detail($language=null,$id=null)
    {
        $page_title = 'Loan Detail';
        $loans_details ='';
        $model = '';
        $detail = '';
        if (!empty($id))
        {
            $model = Loans::find($id);
            $detail = RepayLoans::with('user')->where(['loan_id'=>$id])->get();
            
        }
        $loans = Loans::where('status', 4)->with(['user'])->paginate(15);
        $empty_message = 'No data available';
        return view('vendor.voyager.loan.payment_detail', compact('page_title', 'detail','model','loans','empty_message'));
    }
    public function approve(Request $request)
    {
        $request->validate(['id' => 'required|integer']);
        $id = $request->id;
        if($id)
        {
            $model = Loans::find($id);
            $model->status = 1;
            $model->save();
            flash(__('Request approved') , 'success');
            return redirect(route('loan.approved_loan',[app()->getLocale(),$id]));
        }
        flash(__('Error occurred try again!') , 'danger');
        return redirect(route('loan.preview',[app()->getLocale(),$id]));
    }
    public function reject(Request $request)
    {
        $request->validate(['id' => 'required|integer']);
        $request->validate(['reason' => 'required']);
        $id = $request->id;
        if($id)
        {
            $model = Loans::find($id);
            $model->status = 2;
            $model->reason = $request->reason;
            $model->save();
            flash(__('Request rejected') , 'success');
            return redirect(route('loan.rejected_loan',[app()->getLocale(),$id]));
        }
        flash(__('Error occurred try again!') , 'danger');
        return redirect(route('loan.preview',[app()->getLocale(),$id]));
    }
}
