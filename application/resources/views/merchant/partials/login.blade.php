<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('front/css/style.css') }}" />
</head>
<style>
    .rotate {
        animation: rotation 2s infinite linear;
    }

    @keyframes rotation {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(359deg);
        }
    }

</style>
<link rel="stylesheet" href="{{ asset('assets/merchant-payment/css/payment-with-qr.css') }}" />
<body>

    <div class="">
        <div class="body-wrapper">
           <div class="back-wrapper">
                 <a href="{{route('ipn.show_express_login',['language' => app()->getLocale(),'ref' => $ref ])}}"
                        style="margin-bottom: 20px; color: #000000;">{{__('Back to guest checkout')}}</a>
                <span><strong>X</strong></span>
            </div>
            <div class="content-wrapper">
                <div class="img-wrapper" >
                    <img src="{{ general_setting('site_logo') }}" alt="logo"  />
                </div>
                <b><p class="login-txt">Login to your wallet to pay using <br /> balance</p></b>
                <form id="loginForm" align="center" class="form-horizontal form-wrapper col-sm-6" method="POST" action="{{ route('ipn.login', app()->getLocale()) }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="ref" value="{{$ref}}">
                    <input type="hidden" name="amount" value="{{$amount}}">
                    <div class="input-wrapper">
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus placeholder="E-Mail Address"><br /> 
                        <span id="invalid_email" class="invalid-response text-danger"></span><br>
                        <input id="password" type="password" class="form-control" name="password" placeholder="Password" required><br /> 
                        <span id="invalid_password" class="invalid-response text-danger"></span>
                    </div>
                    <br />
                    <button id="submitLoginForm" type="submit" class="btn btn-primary btn-block btn-lg" style="font-weight: bold;background: coral; color: aliceblue;"> {{__('LOGIN')}}</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>


    


