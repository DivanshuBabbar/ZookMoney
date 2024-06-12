@extends('layouts.app')
@section('content')
{{--  @include('partials.nav')  --}}
<div class="row">
  @include('partials.sidebar')
  <style type="text/css">
    body {
      font-family: Sans-Serif;
    }

    #start-payment-button {
        cursor: pointer;
        position: relative;
        background-color: blueviolet;
        color: #fff;
        max-width: 30%;
        padding: 10px;
        font-weight: 600;
        font-size: 14px;
        border-radius: 10px;
        border: none;
        transition: all .1s ease-in;
    }
  </style>
  <div class="col-lg-9 col-md-12">
    <div class="row">
      <div class="col">
        <div class="card" >
          <div class="header">
              <h2><strong>{{  __('Deposit Request Form') }}</strong></h2>
          </div>
          <div class="body">
            <div class="row">
              <div class="col-sm-6">
                  <script src="https://checkout.flutterwave.com/v3.js"></script>
                  <form>
                    <div>
                      Your order is ₦{{$amount}}
                    </div>
                    <button type="button" id="start-payment-button" onclick="makePayment()">Pay Now</button>
                  </form>
                  <script>
                    function makePayment() {
                      FlutterwaveCheckout({
                        public_key: "FLWPUBK-8a",
                        tx_ref: "{{$tx_ref}}",
                        amount: "{{$amount}}",
                        currency: "NGN",
                        payment_options: "{{$transferMethod->how_to_deposit}}",
                        redirect_url: "{{ url('en/flutteraddredirect') }}",
                        meta: {
                          consumer_id: "{{$userDetails->id}}",
                        },
                        customer: {
                          email: "{{$userDetails->email}}",
                          phone_number: "{{$userDetails->phonenumber}}",
                          name: '{{$userDetails->name}}',
                        },
                        onclose: function(incomplete) {
                          if(incomplete === true) 
                          {
                            window.location.reload();
                          }
                        }
                      });
                    }
                  </script>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('js')
<script>
$( "#deposit_method" )
  .change(function () {
    $( "#deposit_method option:selected" ).each(function() {
      window.location.replace("{{url('/')}}/{{app()->getLocale()}}/addcredit/"+$(this).val());
    });
  });
  $( "#deposit_currency" )
  .change(function () {
    $( "#deposit_currency option:selected" ).each(function() {
      window.location.replace("{{url('/')}}/{{app()->getLocale()}}/wallet/"+$(this).val());
    });
  })
</script>
@endsection