@extends('voyager::master')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop
@section('page_header')
    <h1 class="page-title">
        Payment Detail
    </h1>
    @include('voyager::multilingual.language-selector')
@stop
@section('content')
<div class="page-content edit-add container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-push-1">
        <div class="clearfix"></div>
            @include('flash')
        </div>
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card b-radius--10 ">
                                <div class="card-body p-0">
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label>Amount:</label>
                                                <span>
                                                    @isset($model->loan_amount)
                                                        {{$model->loan_amount}}
                                                    @endisset
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label>Loan tenure:</label>
                                                <span>
                                                    @isset($model->loan_tenure)
                                                        {{$model->loan_tenure}}
                                                    @endisset
                                                    @isset($model->tenure_type)
                                                        {{$model->tenure_type}}
                                                    @endisset
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label>Payback:</label>
                                                <span>
                                                    @isset($model->payback)
                                                        {{$model->payback}}
                                                    @endisset
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label>Total Repay:</label>
                                                <span>
                                                    @isset($model->repay_amount)
                                                        {{$model->repay_amount}}
                                                    @endisset
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Repay every {{$model->payback}}:</label>
                                                <span>
                                                    @isset($model->return_amount)
                                                        {{$model->return_amount}}
                                                    @endisset
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- card end -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card b-radius--10 ">
                                <div class="card-body p-0">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table mb-0">
                                                  <thead>
                                                    <tr>
                                                        <th>@lang('Sr.No')</th>
                                                        <th>@lang('Amount')</th>
                                                        <th>@lang('Due Date')</th>
                                                        <th>@lang('Return Date')</th>
                                                        <th>@lang('Status')</th>
                                                    </tr>
                                                  </thead>
                                                    <tbody>
                                                        @if(!empty($detail))
                                                            @foreach($detail as $key=>$data)
                                                                <tr>
                                                                    <td>{{$key+1}}</td>
                                                                    <td>
                                                                        @isset($data->amount){{$data->amount}}@endisset
                                                                    </td>
                                                                    <td>
                                                                        @isset($data->due_date){{date('M d,Y',strtotime($data->due_date))}}@endisset
                                                                    </td>
                                                                    <td> 
                                                                        @isset($data->repay_date){{date('M d,Y',strtotime($data->repay_date))}}@endisset
                                                                    </td>
                                                                    <td>
                                                                       @if(isset($data->status) && $data->status == 0)
                                                                            <a href="#" class="btn btn-danger btn-xs">
                                                                                Unpaid
                                                                            </a>
                                                                       @endif
                                                                       @if(isset($data->status) && $data->status == 1)
                                                                            <a href="#" class="btn btn-success btn-xs">
                                                                                Paid
                                                                            </a>
                                                                       @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- card end -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- End Delete File Modal -->
@stop
@section('javascript')
    
@stop
