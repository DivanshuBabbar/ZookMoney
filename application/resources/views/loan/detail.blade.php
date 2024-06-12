@extends('layouts.app')
@section('content')
<div class="row">
  @include('partials.sidebar')
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
  <div class="col-lg-9 col-md-12">
    <div class="row">
      <div class="col"  id="#sendMoney">
        @include('flash')
        <div class="card">
          <div class="header">
            <h2><strong>{{__('Loan Detail')}}</strong></h2>
          </div>
          <div class="body">
            <div class="card">
              <h5 class="card-header">@lang('Loan Detail')</h5>
              <div class="card-body">
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
            </div>
            <div class="row" style="margin-top:10px">
              <div class="col-sm-4">
                <div class="card">
                  <h5 class="card-header">@lang('Customer Detail')</h5>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>BVN:</label>
                          <span>
                              @isset($model->bvn)
                                  {{$model->bvn}}
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
                              @isset($model->id_type_number)
                                  {{$model->id_type_number}}
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
                              @isset($model->full_name)
                                  {{$model->full_name}}
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
                              @isset($model->residential_address)
                                  {{$model->residential_address}}
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
                              @isset($model->account_number)
                                  {{$model->account_number}}
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
                              @isset($model->contact)
                                  {{$model->contact}}
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
                              @isset($model->home_address)
                                  {{$model->home_address}}
                              @endisset
                          </span>
                        </div>
                      </div>
                    </div>
                    @if(isset($model->selfie_passport))
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
                              <a download="{{$model->selfie_passport}}" href="{{url('assets/images/loan/'.$model->selfie_passport)}}">
                                <img class="thumb_img" src="{{url('assets/images/loan/'.$model->selfie_passport)}}" alt="image">
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
                  <h5 class="card-header">@lang('Guarantors') (Civil servant)</h5>
                  <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>BVN:</label>
                                <span>
                                    @isset($model->c_bvn)
                                        {{$model->c_bvn}}
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
                              @isset($model->c_id_type_number)
                                  {{$model->c_id_type_number}}
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
                              @isset($model->c_full_name)
                                  {{$model->c_full_name}}
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
                              @isset($model->c_residential_address)
                                  {{$model->c_residential_address}}
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
                              @isset($model->c_contact)
                                  {{$model->c_contact}}
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
                              @isset($model->c_business_address)
                                  {{$model->c_business_address}}
                              @endisset
                          </span>
                        </div>
                      </div>
                    </div>
                    @if(isset($model->c_upload_passport))
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
                              <a download="{{$model->c_upload_passport}}" href="{{url('assets/images/loan/'.$model->c_upload_passport)}}">
                                <img class="thumb_img" src="{{url('assets/images/loan/'.$model->c_upload_passport)}}" alt="image">
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
                  <h5 class="card-header">@lang('Guarantors') (Business man)</h5>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label>BVN:</label>
                          <span>
                              @isset($model->b_bvn)
                                  {{$model->b_bvn}}
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
                              @isset($model->b_id_type_number)
                                  {{$model->b_id_type_number}}
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
                              @isset($model->b_full_name)
                                  {{$model->b_full_name}}
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
                              @isset($model->b_residential_address)
                                  {{$model->b_residential_address}}
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
                              @isset($model->b_contact)
                                  {{$model->b_contact}}
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
                              @isset($model->b_business_address)
                                  {{$model->b_business_address}}
                              @endisset
                          </span>
                        </div>
                      </div>
                    </div>
                    @if(isset($model->b_upload_passport))
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
                              <a download="{{$model->b_upload_passport}}" href="{{url('assets/images/loan/'.$model->b_upload_passport)}}">
                                <img class="thumb_img" src="{{url('assets/images/loan/'.$model->b_upload_passport)}}" alt="image">
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
        </div>
      </div>
    </div>
    @if(isset($model->status) && $model->status == 1)
      <div class="row" style="margin-top:10px">
        <div class="col-sm-12">
          <a href="javascript:void(0)" data-id="{{$model->id}}" data-amount="{{$model->loan_amount}}" class="btn btn-primary approveBtn">Accept</a>
          <a href="javascript:void(0)" data-id="{{$model->id}}" data-amount="{{$model->loan_amount}}" class="btn btn-danger rejectBtn">Decline</a>
        </div>
      </div>
    @endisset
  </div>
</div>
{{-- Accept MODAL --}}
<div id="approveModal" class="modal fade" tabindex="-1" role="dialog" style="z-index:9999;margin-top: 269px;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Accept Loan Confirmation')</h5>
                <button type="button" class="close modal_close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('loan.accept_loan',app()->getLocale())}}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Are you sure to') <span class="font-weight-bold">@lang('accept')</span> <span class="font-weight-bold amount text-success"></span> @lang('loan') <span class="font-weight-bold username"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark modal_close" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--success">@lang('Accept')</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- DECLINE MODAL --}}
<div id="rejectModal" class="modal fade" tabindex="-1" role="dialog" style="z-index:9999;margin-top: 269px;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Decline Loan Confirmation')</h5>
                <button type="button" class="close modal_close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('loan.decline_loan',app()->getLocale())}}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Are you sure to') <span class="font-weight-bold">@lang('decline')</span> <span class="font-weight-bold amount text-success"></span> @lang('loan') <span class="font-weight-bold username"></span>?</p>

                    <div class="form-group">
                        <label class="font-weight-bold mt-2">@lang('Reason for Rejection')</label>
                        <textarea name="reason" id="message" placeholder="@lang('Reason for Rejection')" class="form-control" rows="5"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark modal_close" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--danger">@lang('Decline')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<script type="text/javascript">
  document.addEventListener('DOMContentLoaded',function(){
    $('.approveBtn').on('click', function () {
      var modal = $('#approveModal');
      modal.find('input[name=id]').val($(this).data('id'));
      modal.find('.amount').text($(this).data('amount'));
      modal.modal('show');
    });
    $('.rejectBtn').on('click', function () {
      var modal = $('#rejectModal');
      modal.find('input[name=id]').val($(this).data('id'));
      modal.find('.amount').text($(this).data('amount'));
      modal.modal('show');
    });
    $('body').on('click','.modal_close',function(event){
      event.preventDefault();
      var modal = $(this).closest('.modal');
      modal.modal('hide');
    });
  });
</script>
<script>
$( "#currency" )
  .change(function () {
    $( "#currency option:selected").each(function() {
      window.location.replace("{{url('/')}}/{{app()->getLocale()}}/wallet/"+$(this).val());
  });
})
</script>
@endsection
@section('footer')
  @include('partials.footer')
@endsection
