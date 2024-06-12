
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title> {{general_setting('site_name')}} - Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="{{asset('assets/front/media/Image/airtime_hvbYIZi.png')}}" type="image/png" sizes="16x16">
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
                    <img src="{{asset('assets/front/media/Image/airtime.png')}}" style="height:70px; margin-bottom: 20px;">
                </a>
            </center>
            @include('flash')
            <h3 class="text-center">Admin login</h3>
            
            <form action="{{route('admin.checklogin')}}" method="POST" class="cmn-form mt-30">
                @csrf
                <div class="form-group">
                    <label for="email">@lang('Email')</label>
                    <input type="text" name="email" class="form-control b-radius--capsule" id="username" value="{{ old('username') }}" placeholder="@lang('Enter your Email')">
                    <i class="las la-user input-icon"></i>
                </div>
                <div class="form-group">
                    <label for="pass">@lang('Password')</label>
                    <input type="password" name="password" class="form-control b-radius--capsule" id="pass" placeholder="@lang('Enter your password')">
                    <i class="las la-lock input-icon"></i>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="submit-btn mt-25 b-radius--capsule">@lang('Login') <i class="las la-sign-in-alt"></i></button>
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
</body>
</html>