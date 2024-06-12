@extends('layouts.app')

@section('content')
{{--  @include('partials.nav')  --}}
    <div class="row">
        @include('partials.sidebar')
      <div class="col ">
        @include('partials.flash')
        <div class="card bg">
          <div class="header">
            <h2><strong>  {{__('Get a virtual card')}} </strong></h2>
          </div>
          <div class="body">
            <p>
              {{__('Get USD virtual cards that work globally. Pay staff, interns, and recurring service providers instantly (at any time) so they can access salaries even on weekends.')}}
            </p>
            <a  href="#largeVirtualCardFormModal" data-toggle="modal" data-target="#largeVirtualCardFormModal"  class="btn btn-primary btn-simple">{{__('Get your Virtual Card')}}</a>
          </div>
        </div>

        @if( $virtualcards->total() > 0 )
        <div class="card bg-light">
          <div class="header">
            <h2>{{__('Virtualcard list')}}</h2>
          </div>
          <div class="body">
            <div class="row">
            @foreach($virtualcards as $virtualcard)

              @if(isset($virtualcard->detail) and isset($virtualcard->detail->masked_pan))
              <div class="col-lg-4  col-sm-6 col-xs-12 col-md-4">
                <div class="card">
                  <div class="header">
                   <h2> <strong>{{$virtualcard->detail->masked_pan}}</strong></h2>
                   <ul class="header-dropdown">
                        <li class="dropdown "> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="icon icon-credit-card"></i> </a>  {{--
                            <ul class="dropdown-menu dropdown-menu-right float-right">
                                <li><a href="https://devv2.dxtrader.xyz/en/web/payment/6223b566dc7a1">Card details</a></li>
                                
                            </ul>
                            --}}
                        </li>
                    </ul>
                  </div>
                  <div class="body">
                    <div class="row">
                      <div class="col">
                        <span>{{__('Card Number')}}</span><br>
                        <strong>{{$virtualcard->detail->pan}}</strong>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col mt-2">
                        <span>{{__('Expires')}}</span><br>
                        <strong>{{$virtualcard->detail->expiration}}</strong>
                      </div>
                      <div class="col mt-2">
                        <span>{{__('CVV')}}</span><br>
                        <strong>{{$virtualcard->detail->cvv}}</strong>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col mt-2">
                        <span>{{__('Status')}}</span>
                        @if($virtualcard->status == 0)
                          <small class="badge badge-danger">{{__('Inactive')}}</small>
                        @else
                          <small xlass="badge badge-success">{{__('Active')}}</small>
                        @endif

                      </div>
                    </div>
                  </div>
                </div>
              </div>
              @endif
            @endforeach
            </div>
          </div>
          @if($virtualcards->LastPage() != 1)
            <div class="footer">
                {{$virtualcards->links()}}
            </div>
          @else
          
          @endif
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