@extends('voyager::master')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop
@section('page_header')
    <h1 class="page-title">
        Pending Loan
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
                    <table class="table table--light style--two">
                        <thead>
                        <tr>
                            <th scope="col">@lang('Sr.No')</th>
                            <th scope="col">@lang('User Name')</th>
                            <th scope="col">@lang('Loan Amount')</th>
                            <th scope="col">@lang('Loan Tenuer')</th>
                            <th scope="col">@lang('Payback')</th>
                            <th scope="col">@lang('Date')</th>
                            <th scope="col">@lang('Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($pending_loans as $key=>$data)
                        <tr>
                             <td>{{$key+1}}</td>
                            <td data-label="@lang('Username')">{{$data->user->name}}</td>
                            <td data-label="@lang('Loan Amount')">@isset($data->loan_amount){{$data->loan_amount}}@endisset</td>
                            <td data-label="@lang('Loan Tenuer')">
                                @isset($data->loan_tenure){{$data->loan_tenure}}@endisset
                                @isset($data->tenure_type){{$data->tenure_type}}@endisset
                            </td>
                            <td data-label="@lang('Payback')"> @isset($data->payback){{ucfirst($data->payback)}}@endisset</td>
                            <td data-label="@lang('Date')"> @isset($data->created_at){{date('M d,Y',strtotime($data->created_at))}}@endisset</td>
                            <td data-label="@lang('Action')">
                               <a href="{{route('loan.preview',[app()->getLocale(),$data->id])}}" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i>View</a>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td class="text-muted text-center" colspan="100%">{{ __($empty_message) }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    @if($pending_loans->LastPage() != 1)
                      <div class="card-footer py-4">
                          {{$pending_loans->links()}}
                      </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- End Delete File Modal -->
@stop
@section('javascript')
    
@stop
