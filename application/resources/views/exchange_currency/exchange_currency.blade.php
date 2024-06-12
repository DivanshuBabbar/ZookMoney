@extends('layouts.app')
@section('content')
<div class="row">
  @include('partials.sidebar')
  <div class="col-lg-9 col-md-12">
    <div class="row">
      <div class="col"  id="#sendMoney">
        @include('flash')
        <div class="card">
          <div class="header">
            <h2><strong>{{__('Exchange Currency')}}</strong></h2>
          </div>
          <div class="body">
            <div class="card">
              <div class="card-body">
                <div class="alert_message alert" style="display: none;"></div>
                  <form action="{{url('/').'/'.app()->getLocale().'/save/currencyExchange'}}" method="POST" id="currencyExchangeForm">
                   @csrf
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>From Currency</label>
                                <select class="form-control from_currency_id" required="" name="from_currency_id">
                                    <option value="">--select--</option>
                                    @if(!empty($currencies))
                                    @foreach($currencies as $row)
                                    <option value="@isset($row->id){{$row->id}}@endisset">@isset($row->name){{$row->name}}@endisset</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                         <div class="col-sm-4">
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="text" class="form-control amount" required="" name="amount">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                              <label>To Currency</label>
                                <select class="form-control " required="" name="to_currency_id" id="to_currency_id">
                                    <option value="">--select--</option>
                                    @if(!empty($currencies))
                                    @foreach($currencies as $row)
                                    <option value="@isset($row->id){{$row->id}}@endisset">@isset($row->name){{$row->name}}@endisset</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-4">
                        <div class="exchange_rate-div mt-3" style="display:none;">
                          <label>{{__('Total Amount')}}</label>
                          <input type="text" class="form-control total_amount" name="total_amount" readonly>
                          <input type="hidden" name="exchange_rate" class="exchange_rate">
                          <span>Exchange rate = 
                          <small style="color: red;"id="exchange_rate"></small>
                          </span>
                        </div>  
                      </div>
                    </div>
                    <div class="row" style="margin-top:50px">
                        <div class="col-sm-3">
                        <button  type="submit" id="submit" class="btn btn-info">{{__('Submit')}}</button>
                        </div>
                    </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('footer')
@include('partials.footer')
@endsection
<script type="text/javascript">
  document.addEventListener('DOMContentLoaded',function(){
     function exchangeRate()
     {
     	var body = $('body');
        var from_currency_id = body.find('.from_currency_id').find(':selected').val();
        var to_currency_id = body.find('#to_currency_id').find(':selected').val();
        var amount = body.find(".amount").val();
        if(amount > 0 && from_currency_id && to_currency_id)
        {
          	$.ajax({
	            url:'currencyExchange',
	            method:'GET',
	            data:{'from_currency_id':from_currency_id,'to_currency_id':to_currency_id,'amount':amount},
	            success:function(response)
	            {
	                if(response.total_amount && response.exchange_rate)
	                {
	                  body.find('.total_amount').val(response.total_amount);
	                  body.find('#exchange_rate').html(response.exchange_rate);
                    body.find('.exchange_rate').val(response.exchange_rate);
	                  body.find('.exchange_rate-div').show();
	                }
	                else
	                {
	                  body.find('.exchange_rate-div').hide();
	                }
	            }
          	}); 
        }
    }
    $('body').on("change",".from_currency_id",function(){
     	exchangeRate();
    });
    $('body').on("change","#to_currency_id",function(){
     	exchangeRate();
    });
    $('body').on("keyup",".amount",function(){
     	exchangeRate();
    });
});
</script>
