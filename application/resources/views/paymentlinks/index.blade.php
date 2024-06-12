@extends('layouts.app')

@section('content')
{{--  @include('partials.nav')  --}}
    <div class="row">
        @include('partials.sidebar')
      <div class="col-lg-9 ">
        @include('partials.flash')
       		@if($paymentlinks->total() > 0)
            <div class="row">
              <div class="col mb-5">
                 <a  href="#largePaymentLinkFormModal" data-toggle="modal" data-target="#largePaymentLinkFormModal"  class="btn btn-primary bg-teal float-right">{{__('Request Payment')}}</a>
              </div>
            </div>
            <div class="row">
         			@foreach( $paymentlinks as $link)
  	       		<div class="col-md-6 col-lg-4 col-sm-6">
  		       		<div class="card">
      						<div class="header">
      							<h2>{{$link->name}}</h2>
      							<ul class="header-dropdown">
                          <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                              <ul class="dropdown-menu dropdown-menu-right float-right">
                                  <li><a href="{{url('/')}}/{{app()->getLocale()}}/web/payment/{{$link->paymentlink_id}}">preview</a></li>
                                  {{--
                                  <li><a href="javascript:void(0);">Delete</a></li>
                                  <li><a href="javascript:void(0);">Report</a></li>
                                  --}}
                              </ul>
                          </li>
                      </ul>
      						</div>
      						<div class="body">
      							<p>
      							@if($link->amount == 0)
      								<strong>{{__('Amount')}}</strong> : {{__('Decided by Payer')}}
      							@else
      								<strong>{{__('Amount')}}</strong> : {{ \App\Helpers\Money::instance()->value($link->amount, $link->currency->symbol, $link->currency->is_crypto) }}
      							@endif
      							<br>
      								<strong>{{__('Currency')}}</strong> : {{ $link->currency->name}} <span class="text-primary">  {{ $link->currency->code}}</span>
      							<br>
      								<strong>{{__('Created')}}</strong> : {{ \Carbon\Carbon::parse($link->createdAt)->diffForHumans()}}
      							<br>
      								<strong>{{__('Link')}}</strong> : <a href="{{url('/')}}/{{app()->getLocale()}}/web/payment/{{$link->paymentlink_id}}"> {{url('/')}}/{{app()->getLocale()}}/web/payment/{{$link->paymentlink_id}} </a>
      							</p>
      							<p class="mb-0">
      								@if($link->payment_status == 1)
      									<button class="btn btn-sm btn-round btn-simple btn-primary">{{__('Active')}}</button>
      								@else
      									<button class="btn btn-sm btn-round btn-simple btn-warning">{{__('iddle')}}</button>
      								@endif
      							</p>
      						</div>
  		        	</div>
  		        </div>
  	        	@endforeach
            </div>
            @if($paymentlinks->LastPage() != 1)
            <div class="row">
              <div class="col">
                <div class="card">
                  <div class="body">
                     {{$paymentlinks->links()}}
                  </div>
                </div>
              </div>
            </div>
            @endif
	        @endif
			@if($paymentlinks->count() == 0)
			  <div class="container">
			    <div class="card ">
			      <div class="header">
			        <h2><i class="zmdi zmdi-alert-circle-o "></i> <strong class="">Info</strong></h2>
			          <ul class="header-dropdown">  
			              <li class="remove">
			                  <a role="button" class="boxs-close "><i class="zmdi zmdi-close "></i></a>
			              </li>
			          </ul>
			      </div>
			      <div class="body block-header">
			          <div class="row">
			              <div class="col">
			                  <p class=""><strong> {{__('Your account is Fresh and New !')}} </strong> <br>{{__('Start by requesting money from Friends and Businesses . Create your first Payment Link ')}}</p>
                        <p>
                           <a  href="#largePaymentLinkFormModal" data-toggle="modal" data-target="#largePaymentLinkFormModal"  class="btn btn-primary bg-teal float-right">{{__('Request Payment')}}</a>
                        </p>
			              </div>   
			          </div>
			      </div>
			    </div>
			  </div>
			@endif
        
        
      </div>
    </div>
@endsection
@section('js')
<script>

$(document).ready(function(){

  var card_fee = $('#card_fee');
  var total_fee = $('#total_card_creation_amount');
  var card_amount = $('#card_amount');
  var calc_fee = 0;
  var card_form_error = false ;

  // containers

  var card_fees = $('#card_fees');
  var pay_card_button = $('#pay_card_button');
  var errors = $('#errors');

  card_amount.on('input',function(event){

      if(isNaN(event.target.value) === false ){
        card_form_error = false;
      }else {
        card_form_error = true;
      }

      if(event.target.value  < {{setting('cards.vt_min')}} || event.target.value > {{setting('cards.vt_max')}} ){
        card_form_error = true;
      }

      if(card_form_error == true ){
       card_fees.addClass('d-none');
       pay_card_button.addClass('d-none');
       errors.removeClass('d-none');
      } 

      if(card_form_error == false ) {
         card_fees.removeClass('d-none');
       pay_card_button.removeClass('d-none');
       errors.addClass('d-none');
      }

      calc_fee = {{setting('cards.vt_fixed_fee')}} + ( ( event.target.value * {{setting('cards.vt_fercentage_fee')}})  / 100 );
      total_calc_fee = (event.target.value * 1 ) + calc_fee; 
      card_fee.text(calc_fee.toFixed(2));
      total_fee.text(total_calc_fee.toFixed(2));
  });
});


</script>
@endsection

@section('footer')
  @include('partials.footer')
@endsection