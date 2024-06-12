<div class="container  mt-5 mb-5">
    <div class="row content-center">
       
    </div>
    <div class="row">
        <div class="col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-12  "> 
            <div class="card" >
                <div class="d-flex justify-content-between p-3" style="background-color: #ffffff">
                    <div class="media">
                        <img class="mr-3" src="{{general_setting('site_logo')}}" alt="Generic placeholder image">
                        <div class="media-body">
                          <h5 class="mt-0 mb-1">{{general_setting('site_name')}}</h5>
                            {{__('Payment Link')}}
                        </div>
                    </div>
                    {{--
                    <div class="d-flex flex-column">
                     <span>{{general_setting('site_name')}}<i class="fa fa-caret-down"></i></span>
                      <a href="#" class="billing">{{$paymentlink->user->name}}</a> 
                    </div>
                    --}}
                    <div class="mt-1"> <sup class="super-price">{{ \App\Helpers\Money::instance()->value($paymentlink->amount, $paymentlink->currency->symbol, $paymentlink->currency->is_crypto) }}</sup>{{--  <span class="super-month">/Month</span> --}} </div>
                </div>
                {{-- <hr class="mt-0 line"> --}}
                <div class="p-3">
                    <div class="d-flex justify-content-center mb-2">
                        <div id="qrcode" class="m5"></div>
                    {{-- <span>-$2.00</span> --}}
                    </div>
                    <div class="d-flex justify-content-center">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#exampleModalCenter">
                          {{__('Payment Link Details')}}
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">{{__('Payment Link Details')}}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                                </button>
                              </div>
                              <div class="modal-body">
                                    <span>{{$paymentlink->paymentlink_details}}</span>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                              </div>
                            </div>
                          </div>
                        </div> 
                    </div>
                    {{--
                        <div class="d-flex justify-content-between"> 
                            <span>Vat <i class="fa fa-clock-o"></i></span> 
                            <span>-20%</span>
                        </div>
                    --}}
                </div>
                
            </div>
        </div>
    </div>
    <div class="row ">
        <div class="col-lg-4 offset-lg-4 col-md-6 offset-md-3 col-sm-12 ">
            <div class="card">
                <div class="accordion" id="paymentAcordion">

                    @if($paymentlink->Currency->code == 'USD')
                    <div class="card">
                        <div class="card-header p-0">
                            <h2 class="mb-0"> 
                                <a href="{{route('paymentlinkGateWay',[app()->getLocale(),$paymentlink->paymentlink_id])}}" class="btn btn-light btn-block text-left p-3 rounded-0">
                                    <div class="d-flex align-items-center justify-content-between"> 
                                        <strong class="">Pay with Gateway</strong>
                                    </div>
                                </a> 
                            </h2>
                        </div>
                        <!-- <div class="card-header p-0">
                            <h2 class="mb-0"> <button class="btn btn-light btn-block text-left p-3 rounded-0" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <div class="d-flex align-items-center justify-content-between"> <strong class="">Pay with Card</strong>
                                        <div class="icons"> <img src="https://i.imgur.com/2ISgYja.png" class="black-white-img" width="30"> <img src="https://i.imgur.com/W1vtnOV.png" class="black-white-img" width="30"> </div>
                                    </div>
                                </button> </h2>
                        </div> -->
                        <!-- <div id="collapseOne" class="collapse show " aria-labelledby="headingOne" data-parent="#paymentAcordion">
                        <script src='https://js.stripe.com/v2/' type='text/javascript'></script>
                        <form accept-charset="UTF-8" action="{{url('/')}}/{{app()->getLocale()}}/web/payment/link/process/card" class="require-validation" data-cc-on-file="false" data-stripe-publishable-key="{{$_ENV['STRIPE_PUBLISHABLE_KEY']}}" id="payment-form" method="post">
                            @csrf
                            <input type="hidden" name="link" value="{{$paymentlink->paymentlink_id}}">
                             @if ($errors->has('link'))
                                        <span class="invalid-feedback d-block pl-2">
                                            <strong>{{ $errors->first('link') }}</strong>
                                        </span>
                                    @endif
                                     @if ($errors->has('cardToken'))
                                        <span class="invalid-feedback d-block pl-2">
                                            <strong>{{ $errors->first('cardToken') }}</strong>
                                        </span>
                                    @endif
                            <div class="card-body payment-card-body"> 
                                <span class="font-weight-normal card-text">Card Number</span>
                                <div class="input form-group required"> 
                                    <i class="fa fa-credit-card"></i> 
                                    <input type="text" class="form-control card-number" placeholder="0000 0000 0000 0000"> 
                                </div>
                                <div class="row mt-3 mb-3">
                                    <div class="col-md-8"> 
                                        <span class="font-weight-normal card-text">Expiry Date</span>

                                        <div class="row">
                                            <div class="col-6 pr-0">
                                                 <div class="input form-group required"> 
                                            <i class="fa fa-calendar"></i> 
                                            <input type="text" class="form-control pr-0 card-expiry-month" placeholder="MM" size='2'> 
                                        </div>
                                            </div>
                                            <div class="col-6 pl-0">
                                                 <div class="input form-group required"> 
                                            <i class="fa fa-calendar"></i> 
                                            <input type="text" class="form-control pr-0 card-expiry-year" placeholder="YY" size='4'> 
                                        </div>
                                            </div>
                                        </div> 
                                       
                                    </div>
                                    <div class="col-md-4"> 
                                        <span class="font-weight-normal card-text">CVC/CVV</span>
                                        <div class="input form-group required"> 
                                            <i class="fa fa-lock"></i> 
                                            <input type="text" class="form-control card-cvc" placeholder="000" size='4'> 
                                        </div>
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="col">
                                        <span class="font-weight-normal card-text">Your Email</span>
                                        <div class="input form-group required"> 
                                            <i class="fa fa-at"></i> 
                                            <input type="email" name="email" class="form-control" placeholder="email" required> 
                                        </div>
                                        
                                     @if ($errors->has('email'))
                                        <span class="invalid-feedback d-block pl-2">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                    </div>
                                </div>
                                <div class="row">
                                     <div class='col error d-none mt-3'>
                                            <div class='alert-danger alert'>Please correct the errors and try
                                                again.</div>
                                        </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col">
                                         <button  class="btn btn-success btn-block free-button  submit-button" style="font-weight: bold" >Pay</button>
                                    </div>
                                </div>
                               
                            </div>
                            <div class="card-body text-center"> 
                               
                                <span class="text-muted certificate-text"><i class="fa fa-lock"></i> Your transaction is secured with ssl certificate</span>
                            </div>
                        </form>
                        </div> -->
                    </div>
                    @endif
                     <div class="card">
                        <div class="card-header p-0" id="headingTwo">
                            <h2 class="mb-0"> <button class="btn btn-light btn-block text-left collapsed p-3 rounded-0 border-bottom-custom" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    <div class="d-flex align-items-center justify-content-between"> <strong class="">{{__('Pay with')}} {{general_setting('site_name')}} {{__('balance')}}</strong> <img src="{{general_setting('site_logo')}}" class="black-white-img" width="30"> </div>
                                </button> </h2>
                        </div>
                        <div id="collapseTwo" class="collapse " aria-labelledby="headingTwo" data-parent="#paymentAcordion">
                            <div class="card-body"> 
                                <form method="POST" action="{{url('/')}}/{{app()->getLocale()}}/web/payment/link/process">
                                    @csrf
                                    <input type="hidden" name="pid" value="{{$paymentlink->paymentlink_id}}">
                                    <div class="input"> 
                                        <i class="fa fa-at"></i> 
                                        <input type="text" name="email" class="form-control mb-2" placeholder="Email">
                                        
                                    </div> 
                                     @if ($errors->has('email'))
                                        <span class="invalid-feedback d-block pl-2">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                    @endif
                                    <div class="input">
                                        <i class="fa fa-lock"></i>
                                        <input type="password" name="password" class="form-control mb-2" placeholder="Password"> 
                                    </div>
                                   
                                     @if ($errors->has('password'))
                                        <span class="invalid-feedback d-block pl-2">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                    <input type="submit" class="btn btn-success btn-block free-button" value="Pay" style="font-weight: bold" />
                                    <div class="card-body text-center"> 
                               
                                        <span class="text-muted certificate-text"><i class="fa fa-lock"></i> Your transaction is secured with ssl certificate</span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div> 
                    {{--
                    <div class="card">
                        <div class="card-header p-0">
                            <h2 class="mb-0"> <button class="btn btn-light btn-block text-left p-3 rounded-0" data-toggle="collapse" data-target="#collapseZero" aria-expanded="true" aria-controls="collapseZero">
                                    <div class="d-flex align-items-center justify-content-between"> <strong class="">Pay with Flutterwave</strong>
                                        <div class="icons"> <img src="https://i.imgur.com/2ISgYja.png" width="30"> <img src="https://i.imgur.com/W1vtnOV.png" width="30"> <img src="https://i.imgur.com/35tC99g.png" width="30"> <img src="https://i.imgur.com/2ISgYja.png" width="30"> </div>
                                    </div>
                                </button> </h2>
                        </div>
                        <div id="collapseZero" class="collapse " aria-labelledby="headingZero" data-parent="#paymentAcordion">
                            <div class="card-body payment-card-body"> 
                                <input type="submit" class="btn btn-primary btn-block free-button" value="Procees to Flutterwave Checkout" />
                                <span class="text-muted certificate-text"><i class="fa fa-lock"></i> Your transaction is secured with ssl certificate</span>
                            </div>
                        </div>
                    </div>
                    --}}
                </div>
            </div>
        </div>
    </div>
</div>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/jquery.qrcode.js')}}"></script>
<script src="{{ asset('assets/js/qrcode.js')}}"></script>
<script>
    //jQuery('#qrcode').qrcode("this plugin is great");
    jQuery('#qrcode').qrcode({
        render  : "table",
        text    : "{{url('/')}}/{{app()->getLocale()}}/web/payment/{{$paymentlink->paymentlink_id}}"
    });
    
</script>
<script>
    $(function() {

        //$("#qrcode").qrcode({text:"https://www.jqueryscript.net"});
        $('.card-number').on('keyup',function(e) {
                e.preventDefault();
                eventKeyCode = e.which; 
                let value =  $(this).val();
                if( value.length == 4 || value.length == 9 || value.length == 14 || value.length == 19) {
                    value += ' ';
                    $(this).val(value);
                }


                if(eventKeyCode == 8){
                 $(this).val('');
                }

                let trimed_value = value.replace(/\s/g, "");

                if( trimed_value.length > 16 ){
                    Swal.fire({
                    title: 'Ivalid Card Number',
                    showConfirmButton: false,
                    showDenyButton: true,
                    text: `A valid card number has 16 digits, please type a valid card number !`,
                    denyButtonText: 'Fix',
                    }).then((result) => {
                      
                    });
                }
                
            });

            $('.card-cvc').on('keyup',function(e) {

                e.preventDefault();
                eventKeyCode = e.which; 
                let value =  $(this).val().replace(/\s/g, "");
                $(this).val(value);

                if( value.length > 3 ) {

                    Swal.fire({
                    title: 'Ivalid CVV',
                    showConfirmButton: false,
                    showDenyButton: true,
                    text: `A valid CVV has 3 digits.`,
                    denyButtonText: 'Fix',
                    }).then((result) => {
                      
                    });
                }


                if(eventKeyCode == 8){
                 $(this).val('');
                }

                
                
            });

            $('.card-expiry-month').on('keyup',function(e) {
                
                e.preventDefault();
                eventKeyCode = e.which; 
                let value =  $(this).val().replace(/\s/g, "");
                $(this).val(value);

                if( value.length > 2 ) {

                    Swal.fire({
                    title: 'Ivalid CVV',
                    showConfirmButton: false,
                    showDenyButton: true,
                    text: `A valid Expiry Month has 2 digits`,
                    denyButtonText: 'Fix',
                    }).then((result) => {
                      
                    });
                }


                if(eventKeyCode == 8){
                 $(this).val('');
                }
                
            });

            $('.card-expiry-year').on('keyup',function(e) {
               e.preventDefault();
                eventKeyCode = e.which; 
                let value =  $(this).val().replace(/\s/g, "");
                $(this).val(value);

                if( value.length > 2 ) {

                    Swal.fire({
                    title: 'Ivalid CVV',
                    showConfirmButton: false,
                    showDenyButton: true,
                    text: `A valid Expiry Year has 2 digits`,
                    denyButtonText: 'Fix',
                    }).then((result) => {
                      
                    });
                }


                if(eventKeyCode == 8){
                 $(this).val('');
                }
                
            });

            // valid
              $('form.require-validation').bind('submit', function(e) {
                var $form         = $(e.target).closest('form'),
                    inputSelector = ['input[type=email]', 'input[type=password]',
                                     'input[type=text]', 'input[type=file]',
                                     'textarea'].join(', '),
                    $inputs       = $form.find('.required').find(inputSelector),
                    $errorMessage = $form.find('div.error'),
                    valid         = true;
                $errorMessage.addClass('d-none');
                $('.has-error').removeClass('has-error');
                $inputs.each(function(i, el) {
                  var $input = $(el);
                  if ($input.val() === '') {
                    $input.parent().addClass('has-error');
                    $errorMessage.removeClass('d-none');
                    e.preventDefault(); // cancel on first error
                  }
                });
              });
            });
            $(function() {
              var $form = $("#payment-form");
              $form.on('submit', function(e) {
                if (!$form.data('cc-on-file')) {
                  e.preventDefault();
                  Stripe.setPublishableKey($form.data('stripe-publishable-key'));
                  Stripe.createToken({
                    number: $('.card-number').val(),
                    cvc: $('.card-cvc').val(),
                    exp_month: $('.card-expiry-month').val(),
                    exp_year: $('.card-expiry-year').val()
                  }, stripeResponseHandler);
                }
              });
              function stripeResponseHandler(status, response) {
                if (response.error) {
                  $('.error')
                    .removeClass('d-none')
                    .find('.alert')
                    .text('Invalid Card');
                    ///.text(response.error.message);
                } else {
                  // token contains id, last4, and card type
                  var token = response['id'];
                  // insert the token into the form so it gets submitted to the server
                  $form.find('input[type=text]').empty();
                  $form.append("<input type='hidden' name='cardToken' value='" + token + "'/>");
                  $form.get(0).submit();
                }
              }
            })
</script>
