@extends('voyager::master')
@section('css')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop
@section('page_header')
    <h1 class="page-title">
        Loan Preview
    </h1>
    @include('voyager::multilingual.language-selector')
@stop
@section('content')
<style type="text/css">
    .modal-backdrop{
      z-index: 0 !important; 
    }
    .thumb_img {
      border: 1px solid #ddd; /* Gray border */
      border-radius: 4px;  /* Rounded border */
      padding: 5px; /* Some padding */
      width: 150px; /* Set a small width */
    }

    /* Add a hover effect (blue shadow) */
    .thumb_img:hover {
      box-shadow: 0 0 2px 1px rgba(0, 140, 186, 0.5);
    }
  </style>
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
                                                    @isset($loans_details->loan_amount)
                                                        {{$loans_details->loan_amount}}
                                                    @endisset
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label>Loan tenure:</label>
                                                <span>
                                                    @isset($loans_details->loan_tenure)
                                                        {{$loans_details->loan_tenure}}
                                                    @endisset
                                                    @isset($loans_details->tenure_type)
                                                        {{$loans_details->tenure_type}}
                                                    @endisset
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label>Payback:</label>
                                                <span>
                                                    @isset($loans_details->payback)
                                                        {{$loans_details->payback}}
                                                    @endisset
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label>Total Repay:</label>
                                                <span>
                                                    @isset($loans_details->repay_amount)
                                                        {{$loans_details->repay_amount}}
                                                    @endisset
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Repay every {{$loans_details->payback}}:</label>
                                                <span>
                                                    @isset($loans_details->return_amount)
                                                        {{$loans_details->return_amount}}
                                                    @endisset
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card b-radius--10 ">
                                <div class="card-body p-0">
                                    <div class="row" >
                                        <div class="col-sm-4">
                                            <div class="card">
                                                <h5 class="card-header">@lang('Customer Detail')</h5>
                                                <div class="card-body bg-white">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>BVN:</label>
                                                                <span>
                                                                    @isset($loans_details->bvn)
                                                                        {{$loans_details->bvn}}
                                                                    @endisset
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>ID type and the number ( NIN and DRIVER'S LICENSE ONLY):</label>
                                                                <span>
                                                                    @isset($loans_details->id_type_number)
                                                                        {{$loans_details->id_type_number}}
                                                                    @endisset
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Full Name:</label>
                                                                <span>
                                                                    @isset($loans_details->full_name)
                                                                        {{$loans_details->full_name}}
                                                                    @endisset
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Residential address:</label>
                                                                <span>
                                                                    @isset($loans_details->residential_address)
                                                                        {{$loans_details->residential_address}}
                                                                    @endisset
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Account Number:</label>
                                                                <span>
                                                                    @isset($loans_details->account_number)
                                                                        {{$loans_details->account_number}}
                                                                    @endisset
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Contact:</label>
                                                                <span>
                                                                    @isset($loans_details->contact)
                                                                        {{$loans_details->contact}}
                                                                    @endisset
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Home address:</label>
                                                                <span>
                                                                    @isset($loans_details->home_address)
                                                                        {{$loans_details->home_address}}
                                                                    @endisset
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if(isset($loans_details->selfie_passport))
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                              <div class="form-group">
                                                                <label>Selfie or passport:</label>
                                                              </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                              <div class="form-group">
                                                                <span>
                                                                  <a download="{{$loans_details->selfie_passport}}" href="{{url('assets/images/loan/'.$loans_details->selfie_passport)}}">
                                                                    <img class="thumb_img" src="{{url('assets/images/loan/'.$loans_details->selfie_passport)}}" alt="image">
                                                                  </a>
                                                                </span>
                                                              </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="card">
                                                <h5 class="card-header">@lang('Guarantors') (First)</h5>
                                                <div class="card-body bg-white">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>BVN:</label>
                                                                <span>
                                                                    @isset($loans_details->c_bvn)
                                                                        {{$loans_details->c_bvn}}
                                                                    @endisset
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>ID type and the number (voters card not acceptable):</label>
                                                                <span>
                                                                    @isset($loans_details->c_id_type_number)
                                                                        {{$loans_details->c_id_type_number}}
                                                                    @endisset
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Full Name:</label>
                                                                <span>
                                                                    @isset($loans_details->c_full_name)
                                                                        {{$loans_details->c_full_name}}
                                                                    @endisset
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Residential address:</label>
                                                                <span>
                                                                    @isset($loans_details->c_residential_address)
                                                                        {{$loans_details->c_residential_address}}
                                                                    @endisset
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Contact:</label>
                                                                <span>
                                                                    @isset($loans_details->c_contact)
                                                                        {{$loans_details->c_contact}}
                                                                    @endisset
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Business address:</label>
                                                                <span>
                                                                    @isset($loans_details->c_business_address)
                                                                        {{$loans_details->c_business_address}}
                                                                    @endisset
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if(isset($loans_details->c_upload_passport))
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                              <div class="form-group">
                                                                <label>Upload passport:</label>
                                                              </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                              <div class="form-group">
                                                                <span>
                                                                  <a download="{{$loans_details->c_upload_passport}}" href="{{url('assets/images/loan/'.$loans_details->c_upload_passport)}}">
                                                                    <img class="thumb_img" src="{{url('assets/images/loan/'.$loans_details->c_upload_passport)}}" alt="image">
                                                                  </a>
                                                                </span>
                                                              </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="card">
                                                <h5 class="card-header">@lang('Guarantors') (Second)</h5>
                                                <div class="card-body bg-white">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>BVN:</label>
                                                                <span>
                                                                    @isset($loans_details->b_bvn)
                                                                        {{$loans_details->b_bvn}}
                                                                    @endisset
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>ID type and the number (voters card not acceptable):</label>
                                                                <span>
                                                                    @isset($loans_details->b_id_type_number)
                                                                        {{$loans_details->b_id_type_number}}
                                                                    @endisset
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Full Name:</label>
                                                                <span>
                                                                    @isset($loans_details->b_full_name)
                                                                        {{$loans_details->b_full_name}}
                                                                    @endisset
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Residential address:</label>
                                                                <span>
                                                                    @isset($loans_details->b_residential_address)
                                                                        {{$loans_details->b_residential_address}}
                                                                    @endisset
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Contact:</label>
                                                                <span>
                                                                    @isset($loans_details->b_contact)
                                                                        {{$loans_details->b_contact}}
                                                                    @endisset
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Business address:</label>
                                                                <span>
                                                                    @isset($loans_details->b_business_address)
                                                                        {{$loans_details->b_business_address}}
                                                                    @endisset
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @if(isset($loans_details->b_upload_passport))
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                              <div class="form-group">
                                                                <label>Upload passport:</label>
                                                              </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                              <div class="form-group">
                                                                <span>
                                                                  <a download="{{$loans_details->b_upload_passport}}" href="{{url('assets/images/loan/'.$loans_details->b_upload_passport)}}">
                                                                    <img class="thumb_img" src="{{url('assets/images/loan/'.$loans_details->b_upload_passport)}}" alt="image">
                                                                  </a>
                                                                </span>
                                                              </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- card end -->
                        </div>
                    </div>
                    <div class="row">
                        @if(isset($loans_details->status) && $loans_details->status == 0)
                            <div class="col-sm-2">
                                <a href="javascript:void(0)" data-id="{{$loans_details->id}}" data-amount="{{$loans_details->loan_amount}}" data-username="{{$loans_details->user->username }}" class="btn btn-info approveBtn">
                                    Approve
                                </a>
                                <a href="javascript:void(0)" data-id="{{$loans_details->id}}" data-amount="{{$loans_details->loan_amount}}" data-username="{{$loans_details->user->username }}" class="btn btn-danger rejectBtn">
                                    Reject
                                </a>
                            </div>
                        @endif
                        @if(isset($loans_details->status) && $loans_details->status == 1)
                            <a href="javascript:void(0)"class="btn btn-success">
                                Approved
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- APPROVE MODAL --}}
<div id="approveModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Approve Loan Confirmation')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('loan.approve',app()->getLocale())}}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Are you sure to') <span class="font-weight-bold">@lang('approve')</span> <span class="font-weight-bold amount text-success"></span> @lang('loan of') <span class="font-weight-bold username"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--success">@lang('Approve')</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- REJECT MODAL --}}
<div id="rejectModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Reject Loan Confirmation')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('loan.reject',app()->getLocale())}}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Are you sure to') <span class="font-weight-bold">@lang('reject')</span> <span class="font-weight-bold amount text-success"></span> @lang('loan of') <span class="font-weight-bold username"></span>?</p>
                    <div class="form-group">
                        <label class="font-weight-bold mt-2">@lang('Reason for Rejection')</label>
                        <textarea name="reason" id="message" placeholder="@lang('Reason for Rejection')" class="form-control" rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--danger">@lang('Reject')</button>
                </div>
            </form>
        </div>
    </div>
</div>
    <!-- End Delete File Modal -->
@stop
@section('javascript')
    <script>
    (function($){
        "use strict";
        $('.approveBtn').on('click', function () {
            var modal = $('#approveModal');
            modal.find('input[name=id]').val($(this).data('id'));
            modal.find('.amount').text($(this).data('amount'));
            modal.find('.username').text($(this).data('username'));
            modal.modal('show');
        });
        $('.rejectBtn').on('click', function () {
            var modal = $('#rejectModal');
            modal.find('input[name=id]').val($(this).data('id'));
            modal.find('.amount').text($(this).data('amount'));
            modal.find('.username').text($(this).data('username'));
            modal.modal('show');
        });
        })(jQuery);
    </script>
@stop
