
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>{{general_setting('site_name')}} - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="{{general_setting('site_icon')}}" type="image/png" sizes="16x16">
    <meta name="theme-color" content="#27B08C">
    <meta name="msapplication-TileColor" content="#27B08C ">
    <meta itemprop="image" content="assets/front/media/Image/bg2.jpg">
    <link rel="stylesheet" href="assets/front/static/ogbam/w3.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{asset('assets/front/static/assets/vendor/bootstrap/css/bootstrap.min.css')}}">
    <link href="{{asset('assets/front/static/assets/vendor/fonts/circular-std/style.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('assets/front/static/assets/libs/css/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/front/static/assets/vendor/fonts/fontawesome/css/fontawesome-all.css')}}">
    <link rel="stylesheet" href="{{asset('assets/front/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css')}}">
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="{{asset('assets/front/static/dashboard/assets/img/icon.ico')}}" type="image/x-icon" />
    <!-- Fonts and icons -->
    <script src="{{asset('assets/front/static/dashboard/assets/js/plugin/webfont/webfont.min.js')}}"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <script>
        WebFont.load({
            google: {
                "families": ["Lato:300,400,700,900"]
            },
            custom: {
                "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"],
                urls: ['{{asset("assets/front/static/dashboard/assets/css/fonts.min.css")}}']
            },
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>
    <script>
        toastr.error('Error', 'Error Title')
    </script>
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{asset('assets/front/static/dashboard/assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/front/static/dashboard/assets/css/atlantis.css')}}">
        <style>
            .login {
                background: #4C6E4D !important;
            }
        </style>
</head>
<body class="login">
    <div class="wrapper wrapper-login">
        <div class="container container-login animated fadeIn">
            <center> 
                <a href="/" class="logo">
                    <img src="{{general_setting('site_icon')}}" style="height:70px; margin-bottom: 20px;">
                </a>
            </center>
            @include('flash')
            <h3 class="text-center">User login</h3>
            <form class="form" method="POST" action="{{ route('login', app()->getLocale()) }}">
                         @csrf
                        <div class="form-group">
                            <input  id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="{{ __('Email') }}" required autofocus>
                            <span class="input-group-addon"><i class="zmdi zmdi-account-circle"></i></span>
                            @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                        </div>
                 <div class="form-group">
                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{ __('Password') }}" required>
                            <span class="input-group-addon"><i class="zmdi zmdi-lock"></i></span>
                             @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                        </div> 
                <div class="form-group">
                    <button type="submit" class="cmn-btn py-3 w-100">@lang('Login') <i class="las la-sign-in-alt"></i></button>
                </div>
                 <div class="form-group form-action-d-flex mb-3">
                        <a href="{{ route('password.request', app()->getLocale()) }}" class="link float-right">Forgot Password ?</a>
                        
                    </div>
                    <div class="login-account">
                        <span class="msg">Don't have an account yet ?</span>
                        <a href="{{url('/')}}/{{app()->getLocale()}}/register" id="show-signup" class="link">Sign Up</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="{{asset('assets/front/static/dashboard/assets/js/core/jquery.3.2.1.min.js')}}"></script>
    <script src="{{asset('assets/front/static/dashboard/assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js')}}"></script>
    <script src="{{asset('assets/front/static/dashboard/assets/js/core/popper.min.js')}}"></script>
    <script src="{{asset('assets/front/static/dashboard/assets/js/core/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/front/static/dashboard/assets/js/atlantis.min.js')}}"></script>
    <script src="{{asset('assets/front/ajax/libs/toastr_js/latest/js/toastr.min.js')}}"></script>
    <!-- Jquery Core Js --> 
<script src="{{ asset('assets/js/libscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js ( jquery.v3.2.1, Bootstrap4 js) --> 
<script src="{{ asset('assets/js/vendorscripts.bundle.js')}}"></script> <!-- slimscroll, waves Scripts Plugin Js -->

<script src="{{ asset('assets/js/particles.min.js')}}"></script>
<script src="{{ asset('assets/js/particles.js')}}"></script>
</body>
</html>