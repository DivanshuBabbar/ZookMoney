<!DOCTYPE html>
<html class="no-js " lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<meta name="description" content="{{ general_setting('site_name') }}">

<title>{{ general_setting('site_name') }} Verify OTP</title>
<!-- Favicon-->
<link rel="apple-touch-icon" sizes="57x57" href="{{asset('assets/frontend/images/favicon/apple-icon-57x57.png')}}">
<link rel="apple-touch-icon" sizes="60x60" href="{{asset('assets/frontend/images/favicon/apple-icon-60x60.png')}}">
<link rel="apple-touch-icon" sizes="72x72" href="{{asset('assets/frontend/images/favicon/apple-icon-72x72.png')}}">
<link rel="apple-touch-icon" sizes="76x76" href="{{asset('assets/frontend/images/favicon/apple-icon-76x76.png')}}">
<link rel="apple-touch-icon" sizes="114x114" href="{{asset('assets/frontend/images/favicon/apple-icon-114x114.png')}}">
<link rel="apple-touch-icon" sizes="120x120" href="{{asset('assets/frontend/images/favicon/apple-icon-120x120.png')}}">
<link rel="apple-touch-icon" sizes="144x144" href="{{asset('assets/frontend/images/favicon/apple-icon-144x144.png')}}">
<link rel="apple-touch-icon" sizes="152x152" href="{{asset('assets/frontend/images/favicon/apple-icon-152x152.png')}}">
<link rel="apple-touch-icon" sizes="180x180" href="{{asset('assets/frontend/images/favicon/apple-icon-180x180.png')}}">
<link rel="icon" type="image/png" sizes="192x192"  href="{{asset('assets/frontend/images/favicon/android-icon-192x192.png')}}">
<link rel="icon" type="image/png" sizes="32x32" href="{{asset('assets/frontend/images/favicon/favicon-32x32.png')}}">
<link rel="icon" type="image/png" sizes="96x96" href="{{asset('assets/frontend/images/favicon/favicon-96x96.png')}}">
<link rel="icon" type="image/png" sizes="16x16" href="{{asset('assets/frontend/images/favicon/favicon-16x16.png')}}">
<link rel="manifest" href="{{asset('assets/frontend/images/favicon/manifest.json')}}">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/ms-icon-144x144.png')}}">
<meta name="theme-color" content="#ffffff">

 <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css')}}">

<!-- Custom Css -->
  <link rel="stylesheet" href="{{ asset('assets/css/main.css')}}">
    <link rel="stylesheet" href="{{ asset('assets/css/color_skins.css')}}">
    <style type="text/css">
        .authentication .company_detail .logo img {
    width: auto;
    vertical-align: top;
}
    </style>
</head>
<body class="theme-green">
<div class="authentication">
    <div class="container">
        <div class="col-md-12 content-center">
            <div class="row clearfix">
                <div class="col-lg-6 col-md-12">
                    <div class="company_detail">
                        <h4 class="logo"><img src="{{general_setting('site_logo')}}" height="42" alt="{{ general_setting('site_name') }}"> <span class="m-l-10">{{ general_setting('site_name') }}</span></h4>
                        <h3>Verify your new {{general_setting('site_name')}} account</h3>
                    </div>
                </div>                        
                <div class="col-lg-5 col-md-12 offset-lg-1">
                    <form class="form" action="{{url('/')}}/{{app()->getLocale()}}/otp" method="post">    
                    {{csrf_field()}}
                    <div class="card-plain">
                        <div class="header">
                        
                            @include('flash')                             
                            <h5 class="mb-5">One Time Password</h5>
                            <span>To verify your account, we've sent a One Time Password to (OTP) to the email {{ Auth::user()-> email }} </span>
                        </div>
                        <div class="body">                  
                            <div class="input-group">
                                <input type="text" class="form-control" id="otp" name="otp" placeholder="Enter OTP">
                                <span class="input-group-addon"><i class="zmdi zmdi-email"></i></span>
                            </div>
                            @if ($errors->has('otp'))
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $errors->first('otp') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="footer">
                            <input type="submit" class="btn btn-primary btn-round btn-block" value="SUBMIT" >                            
                        </div>
                        <a href="{{route('resend_otp', app()->getLocale())}}" class="link">Resend OTP</a>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div id="particles-js"></div>
</div>
<!-- Jquery Core Js --> 
<script src="{{ asset('assets/js/libscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js ( jquery.v3.2.1, Bootstrap4 js) --> 
<script src="{{ asset('assets/js/vendorscripts.bundle.js')}}"></script> <!-- slimscroll, waves Scripts Plugin Js -->

<script src="{{ asset('assets/js/particles.min.js')}}"></script>
<script src="{{ asset('assets/js/particles.js')}}"></script>
</body>
</html>

