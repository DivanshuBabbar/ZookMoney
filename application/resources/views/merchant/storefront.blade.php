@extends('layouts.storefront')
@push('styles')
<style>
    .lw-show-till-loading {
        display: none;
    }
    .lw-show-till-loading {
        display: none;
    }
    .flash {
        display: none;
    }
    .lw-page-loader {
        position: fixed;
        width: 100%;
        height: 100vh;
        left: 0;
        top: 0;
        z-index: 99;
        background: rgba(0, 0, 0, 0.5);
    }
    .lw-page-loader .spinner-border {
        position: absolute;
        left: 50%;
        top: 50%;
    }
    .btn-warning {
        color: #212529;
        background-color: #ffc107;
        border-color: #ffc107;
    }

    .btn-warning:hover {
        color: #212529;
        background-color: #e0a800;
        border-color: #d39e00;
    }

    .btn-light {
        color: #212529;
        background-color: #f8f9fa;
        border-color: #f8f9fa;
    }

    .btn-light:hover {
        color: #212529;
        background-color: #e2e6ea;
        border-color: #dae0e5;
    }
    .form-group .form-control, .input-group .form-control {
        padding: 3px 18px 0px 18px;
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }

    div#app {
        background: coral;
    }


    .payment-container .content-wrapper {
        text-align: center;
    }

    .payment-container * {
        font-family: 'Inter', sans-serif;
         font-size: large;
    }

    p.login-txt {
        font-size: x-large;
    }

    form#loginForm {
        margin: auto;
        padding-top: 20px;
        width: 100%;
        padding: 12px 20px;
        box-sizing: border-box;
        -webkit-transition: 0.5s;
    }
    .container {height: 65em;}

    p.groove {
        height:44px;
        text-align: left; 
        padding-left: 10px; 
        padding-top: 5px; 

    }
    .box{
        display: flex;
        flex-flow: row nowrap;
        justify-content: center;
        align-content: center;
        align-items: center;
        background: beige;
        border: 1px solid;
        height: 70px;

    }
    .item{
      flex: 1 1 auto;
      
    }
    .theme-blue .btn-primary {
        background: beige;
        color: black;
    }

    .back-wrapper {
        text-align: end;
    }
    .body-wrapper {
        text-align: center !important;
    }
    
</style>    
@endpush
@section('content')

<div class="container">
    @if($merchant)
        <!-- Show loader when process payment request -->
        <div class="d-flex justify-content-center">
            <div class="lw-page-loader lw-show-till-loading">
                <div class="spinner-border" role="status"></div>
            </div>
        </div>
        
        <!-- Show loader when process payment request -->
        <div class="row justify-content-md-center">
            <div class="col-12 col-lg-push-1" style="margin-top: 20px">
                <div class="body">
                    <div class="row">
                        <div class="col">
                            {{--<div class="media">
                                <div>
                                    <div class="thumb hidden-sm m-r-20"> 
                                        <img src="{{$merchant->logo}}" class="rounded-circle" alt="" style="width: 40px;"> 
                                    </div>
                                </div>
                                <div class="media-body">
                                    <div class="media-heading " style="margin-top: 10px !important;">
                                        <span>{{$merchant->name}} </span>
                                        <span class="badge badge-info">Merchant</span>
                                    </div>
                                </div>
                            </div>--}}
                        </div>
                    </div>
                    <div class="row clearfix">
                        <div class="col-md-12" >
                            @include('flash')
                        </div>
                    </div>
                    <div class="row">
                        
                        <div class="col-lg-12">
                            {{--<h4 class="mb-5 mt-5">{{__('Pay With')}} {{general_setting('site_name')}}</h4>--}}
                            <div class="card bg-light mb-10">
                                <div class="body">
                                    
                                        <div class="clearfix"></div>
                                        
                                    <div class="flash">
                                        <div class="alert alert-danger alert-dismissible fade show mt-2">
                                            <a href="javascript:void(0);" class="alert-link"><strong>Error! </strong></a>
                                            <span class="flash-msg" ></span>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" id="requestRef" value="{{ $ref }}" >
                                    <input type="hidden" id="amount" value="{{ $amount }}" >
                                    <div id="loginContainer"> 
                                        @guest
                                            @include('merchant.partials.login')
                                        @endguest
                                    </div>
                                    <div id="balanceContainer">
                                        @auth
                                            @include('merchant.partials.balance')
                                        @endauth
                                    </div>
                                    {{-- <a href="{{url('/')}}/{{app()->getLocale()}}/register" 
                                        class="btn btn-dark btn-block btn-lg" 
                                        style="font-weight: bold; margin-bottom: 20px">{{__('Create An Account')}}</a> --}}
                                    {{-- temporary disabled gatepay --}}
                                    {{-- <a href="{{route('merchantlinkGateWay', [app()->getLocale(),$merchant->id])}}" class="btn btn-info btn-block btn-lg" style="font-weight: bold; margin-bottom: 20px">{{__('Pay with gatepay')}}</a> --}}
                                </div>
                            </div>
                            @if(setting('payment-gateways.enable_paypal') == 're' || setting('payment-gateways.enable_paystack') == 're' || setting('payment-gateways.enable_stripe') == 're')
                                <h4 class="mb-5 mt-5">{{__('Pay With Third Party')}} </h4>
                                <div class="card bg-light">
                                    <div class="body">
                                        <div class="table-responsive">
                                            <table class="table m-b-0">
                                                <tbody>
                                                    <tr>
                                                    @if(setting('payment-gateways.enable_paypal') == 1)
                                                        <td style="border: 0" class="align-center">
                                                            <form method="post" action="{{url('/')}}/{{app()->getLocale()}}/merchant/storefront/paypal/{{$ref}}" id="paypal-form">
                                                                <input type="hidden" name="ref" value="{{$ref}}">   
                                                                @csrf
                                                                <a href="" onclick="event.preventDefault();
                                                        document.getElementById('paypal-form').submit();">
                                                                    <img style="width: 60px; border: 0" src="{{url('/')}}/storage/imgs/N7EVK0hQpVT3p0PrB95QIufkOOOmKXQ2WqiO2sPi.png" alt="" class="rounded">
                                                                </a>
                                                            </form>
                                                        </td>
                                                    @endif
                                                    @if(setting('payment-gateways.enable_paystack') == 1)
                                                        <td style="border: 0"  class="align-center">
                                                            <form method="post" action="{{url('/')}}/{{app()->getLocale()}}/merchant/storefront/paystack/{{$ref}}" id="paystack-form">
                                                                <input type="hidden" name="ref" value="{{$ref}}">   
                                                                @csrf
                                                                <a href="" onclick="event.preventDefault();
                                                        document.getElementById('paystack-form').submit();">
                                                                    <img style="width: 60px;border:0" src="{{url('/')}}/storage/imgs/smOMNQbvaoIgP8Y2TcA6DfgAdVdWsXe1Caww3aYV.png" alt="" class="rounded">
                                                                </a>
                                                            </form>
                                                        </td>
                                                    @endif
                                                    @if(setting('payment-gateways.enable_stripe') == 1)
                                                        <td style="border: 0"  class="align-center">
                                                            <img style="width: 60px;border:0" src="{{url('/')}}/storage/imgs/xNyqTMuGhvfDAQGIpWxfWrz9K49MEpYlvWJgLPeG.jpeg" alt="" class="rounded">
                                                        </td>
                                                    @endif   
                                                    </tr>
                                                    <tr>
                                                    @if(setting('payment-gateways.enable_paypal') == 1)
                                                        <td style="border:0"  class="align-center">PayPal</td>
                                                    @endif
                                                    @if(setting('payment-gateways.enable_paystack') == 1)
                                                        <td style="border:0"  class="align-center">Paystack</td>
                                                    @endif
                                                    @if(setting('payment-gateways.enable_stripe') == 1)
                                                        <td style="border:0"  class="align-center">Stripe</td>
                                                    @endif
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif  
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('merchant.modals.deposit')
    @endif
</div>
@endsection
@section('footer')
    @include('partials.footer')
@endsection
@push('scripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        const REF = $("#requestRef").val();
        const amount = $("#amount").val();


        $(document).off('submit', '#loginForm').on('submit', '#loginForm', function(e) {
            e.preventDefault();
            let form = $(this);
            let action = $(this).attr('action');
            let formData = new FormData(form[0]);
            formData.append('ref', REF);
            formData.append('amount', amount);
            $.ajax({
                url: action,
                type: 'post',
                dataType: 'json',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $(".lw-show-till-loading").show();
                    $(".flash").hide();
                    $('.invalid-response').text('');
                },
                complete: function() {
                    $(".lw-show-till-loading").hide();
                },
                success: function(response) {
                    $(".flash").hide();
                    Toast.fire({
                        icon: 'success',
                        title: 'Logged in successfully. Plese check your balance and pay'
                    })
                    $("#loginContainer").hide();
                    $("#balanceContainer").html(response.html);
                    $("#balanceContainer").show();
                },
                error: function(response) {
                    $('.flash-msg').text(response.responseJSON.message);
                    $(".flash").show();
                    if (response.responseJSON.errors) {
                        var errors = response.responseJSON.errors;
                        $('.invalid-response').text('');
                        $.each(errors, function (index, value) {
                            $('#invalid_' + index).text(value[0]);
                        });
                    }
                }
            });
        })
        $(document).off('submit', '#expressLoginForm').on('submit', '#expressLoginForm', function(e) {
            e.preventDefault();
            let form = $(this);
            let action = $(this).attr('action');
            let formData = new FormData(form[0]);
            formData.append('ref', REF);
            $.ajax({
                url: action,
                type: 'post',
                dataType: 'json',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $(".lw-show-till-loading").show();
                    $(".flash").hide();
                    $('.invalid-response').text('');
                },
                complete: function() {
                    $(".lw-show-till-loading").hide();
                },
                success: function(response) {
                    $(".flash").hide();
                    Toast.fire({
                        icon: 'success',
                        title: 'Please confirm 6 digit OTP sent to your email.'
                    })
                    $("#balanceContainer").hide();
                    $("#loginContainer").html(response.html);
                    $("#loginContainer").show();
                    $("input[name='otp']").focus();
                },
                error: function(response) {
                    $('.flash-msg').text(response.responseJSON.message);
                    $(".flash").show();
                    if (response.responseJSON.errors) {
                        var errors = response.responseJSON.errors;
                        $('.invalid-response').text('');
                        $.each(errors, function (index, value) {
                            $('#invalid_' + index).text(value[0]);
                        });
                    }
                }
            });
        })

        $(document).off('click', '#resendExpressOtp').on('click', '#resendExpressOtp', function(e) {
            e.preventDefault();
            let form = $(this);
            let action = $(this).data('action');
            let formData = new FormData();
            formData.append('ref', REF);
            formData.append('email', $('input[name="email"]').val());
            $.ajax({
                url: action,
                type: 'post',
                dataType: 'json',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $(".lw-show-till-loading").show();
                    $(".flash").hide();
                    $('.invalid-response').text('');
                },
                complete: function() {
                    $(".lw-show-till-loading").hide();
                },
                success: function(response) {
                    $(".flash").hide();
                    Toast.fire({
                        icon: 'success',
                        title: 'Please confirm 6 digit OTP sent to your email.'
                    })
                    $("#balanceContainer").hide();
                    $("#loginContainer").html('');
                    $("#loginContainer").html(response.html);
                    $("#loginContainer").show();
                    $("input[name='otp']").focus();
                },
                error: function(response) {
                    $('.flash-msg').text(response.responseJSON.message);
                    $(".flash").show();
                    if (response.responseJSON.errors) {
                        var errors = response.responseJSON.errors;
                        $('.invalid-response').text('');
                        $.each(errors, function (index, value) {
                            $('#invalid_' + index).text(value[0]);
                        });
                    }
                }
            });
        })

        $(document).off('submit', '#validateExpressLoginForm').on('submit', '#validateExpressLoginForm', function(e) {
            e.preventDefault();
            let form = $(this);
            let action = $(this).attr('action');
            let formData = new FormData(form[0]);
            formData.append('ref', REF);
            formData.append('amount', amount);

            $.ajax({
                url: action,
                type: 'post',
                dataType: 'json',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $(".lw-show-till-loading").show();
                    $(".flash").hide();
                    $('.invalid-response').text('');
                },
                complete: function() {
                    $(".lw-show-till-loading").hide();
                },
                success: function(response) {
                    $(".flash").hide();
                    Toast.fire({
                        icon: 'success',
                        title: 'Logged in successfully. Plese check your balance and pay'
                    })
                    $("#loginContainer").hide();
                    $("#balanceContainer").html(response.html);
                    $("#balanceContainer").show();

                },
                error: function(response) {
                    $('.flash-msg').text(response.responseJSON.message);
                    $(".flash").show();
                    if (response.responseJSON.errors) {
                        var errors = response.responseJSON.errors;
                        $('.invalid-response').text('');
                        $.each(errors, function (index, value) {
                            $('#invalid_' + index).text(value[0]);
                        });
                    }
                }
            });
        })

        $(document).off('click', '#refreshBalance').on('click', '#refreshBalance', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).data('action'),
                type: 'get',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $(".lw-show-till-loading").show();
                    $(".flash").hide();
                },
                complete: function() {
                    $(".lw-show-till-loading").hide();
                },
                success: function(response) {
                    $(".flash").hide();
                    $("#availableBalance").text(response.available_balance);
                },
                error: function(response) {
                    $('.flash-msg').text(response.responseJSON.message);
                    $(".flash").show();
                }
            });
        })

        $(document).off('click', '#showExpressLogin').on('click', '#showExpressLogin', function(e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('href'),
                type: 'get',
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $(".lw-show-till-loading").show();
                    $(".flash").hide();
                },
                complete: function() {
                    $(".lw-show-till-loading").hide();
                },
                success: function(response) {
                    $(".flash").hide();
                    $("#loginContainer").html(response.html);
                },
                error: function(response) {
                    $('.flash-msg').text(response.responseJSON.message);
                    $(".flash").show();
                }
            });
        })

        $(document).off('click', '#depositFund').on('click', '#depositFund', function(e) {
            e.preventDefault();
            $('#depositModal').modal('show')
        })

        $(document).off('change', 'select[name="deposit_method_id"]').on('change', 'select[name="deposit_method_id"]', function(e) {
            let id  = $(this).find(':selected').val();
            if(id == '') {
                $('body').find('.detail').html('{{ __('Please select a payment method to get instruction related to that.') }}');
                $('body').find('#refNoFormat').html('{{ __('Sample Transaction Ref No: please select a payment method to get sample ref no.') }}');
                return;
            }
            let text = '';
            $.ajax({
                url:"{{ route('ipn.deposit', app()->getLocale()) }}",
                method:'POST',
                datatype:'json',
                data:{'id':id},
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success:function(response) {

                    if(response.is_eligible != '') {
                        $('body').find('.eligible-row').show();
                    } else {
                        $('body').find('.eligible-row').hide();
                    }

                    if(response.detail != '') {
                        $('body').find('.detail').html(response.detail);
                    } else {
                        $('body').find('.detail').html('{{ __('Please select a payment method to get instruction related to that.') }}');    
                    }

                    if(response.transaction_receipt_ref_no_format != '') {
                        $('body').find('#refNoFormat').html(`Sample Transaction Ref No: ${response.transaction_receipt_ref_no_format}`);
                    } else {
                        $('body').find('#refNoFormat').html('');    
                    }
                }
            })
        })

    </script>
@endpush