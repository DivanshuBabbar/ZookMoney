@extends('layouts.app')
@section('content')
<div class="row">
  @include('partials.sidebar')
  <style type="text/css">
    .modal-backdrop{
      z-index: 0 !important; 
    }
  </style>
  <div class="col-lg-9 col-md-12">
    <div class="row">
      <div class="col"  id="#sendMoney">
        @include('flash')
        <div class="card">
          <div class="header">
            <h2><strong>{{__('Payment Detail')}}</strong></h2>
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
            <div class="card">
              <h5 class="card-header">Payment Repay Schedule</h5>
              <div class="table-responsive">
                <table class="table mb-0">
                  <thead>
                    <tr>
                      <th>@lang('Sr.No')</th>
                      <th>@lang('Amount')</th>
                      <th>@lang('Due Date')</th>
                      <th>@lang('Return Date')</th>
                      <th>@lang('Status')</th>
                      <th>@lang('Action')</th>
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
                            <td>
                                @if(isset($data->status) && $data->status == 0)
                                    <a href="#" data-id="{{$data->id}}" data-amount="{{$data->amount}}" class="btn btn-success btn-xs approveBtn">
                                        Repay Now
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
      </div>
    </div>
  </div>
</div>
{{-- Repay MODAL --}}
<div id="approveModal" class="modal fade" tabindex="-1" role="dialog" style="z-index:9999;margin-top: 269px;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Repay Loan Confirmation')</h5>
                <button type="button" class="close modal_close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('loan.repay_now',app()->getLocale())}}" method="POST">
                @csrf
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p>@lang('Are you sure to') <span class="font-weight-bold">@lang('repay')</span> <span class="font-weight-bold amount text-success"></span> @lang('loan') <span class="font-weight-bold username"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark modal_close" data-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn--success">@lang('Repay Now')</button>
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
