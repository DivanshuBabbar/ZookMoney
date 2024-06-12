
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>{{general_setting('site_name')}} - Register</title>
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
                    <form class="form" method="POST" action="{{ route('register', app()->getLocale()) }}">
                        @csrf                        
                       <div class="form-group">
                            <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" placeholder="{{__('Enter User Name')}}" required autofocus>
                            <span class="input-group-addon"><i class="zmdi zmdi-account-circle"></i></span>
                            @if ($errors->has('name'))
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                       <div class="form-group">
                            <input type="text" class="form-control {{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" value="{{ old('first_name') }}" placeholder="{{__('Enter First Name')}}" required>
                            <span class="input-group-addon"><i class="zmdi zmdi-account-circle"></i></span>
                            @if ($errors->has('first_name'))
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $errors->first('first_name') }}</strong>
                                </span>
                            @endif
                        </div>
                       <div class="form-group">
                            <input type="text" class="form-control {{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ old('last_name') }}" placeholder="{{__('Enter Last Name')}}" required>
                            <span class="input-group-addon"><i class="zmdi zmdi-account-circle"></i></span>
                            @if ($errors->has('last_name'))
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $errors->first('last_name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="{{__('Enter Email')}}" required >
                            <span class="input-group-addon"><i class="zmdi zmdi-email"></i></span>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                       <div class="form-group">
                            <label for="CC">Select Your County</label>
                            <select class="form-control z-index show-tick" placeholder="Select Your Contry" name="CC" id="CC">
                                 <option value="" data-prefix="">select</option>
                                @foreach($countries as $country)
                                    <option value="{{$country->code}}" data-prefix="{{$country->prefix}}">{{$country->name}}</option>
                                @endforeach
                            </select>
                             @if ($errors->has('CC'))
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $errors->first('CC') }}</strong>
                                </span>
                            @endif
                        </div>
                       <div class="form-group">
                            <input type="phone" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ old('phone') }}" placeholder="{{__('Mobile Number')}}" id="phonenumber" required >
                            <span class="input-group-addon"><i class="zmdi zmdi-phone"></i></span>
                            @if ($errors->has('phone'))
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                </span>
                            @endif
                        </div>
                       <div class="form-group">
                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{__('Password')}}"  required>
                            <span class="input-group-addon"><i class="zmdi zmdi-lock"></i></span>
                            @if ($errors->has('password'))
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                       <div class="form-group">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="{{__('Repeat Password')}}" required>
                            <span class="input-group-addon"><i class="zmdi zmdi-lock"></i></span>
                            @if ($errors->has('password_confirmation'))
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="checkbox">
                            <input id="terms" type="checkbox" name="terms">
                            <label for="terms">{{__('I read and Agree to the')}} <a href="{{url('/')}}/{{app()->getLocale()}}/page/3">{{__('Terms of Usage')}}</a></label>
                            @if ($errors->has('terms'))
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $errors->first('terms') }}</strong>
                                </span>
                            @endif
                        </div>  
                        
                    <div class="form-group">
                    <button type="submit" class="cmn-btn py-3 w-100">@lang('Sign Up') <i class="las la-sign-in-alt"></i></button>
                </div>
                 <div class="form-group form-action-d-flex mb-3">
                        <a href="{{ route('password.request', app()->getLocale()) }}" class="link float-right">Forgot Password ?</a>
                        
                    </div>
                    <div class="login-account">
                        <span class="msg">Have an account?</span>
                        <a href="{{url('/')}}/{{app()->getLocale()}}/login" id="show-signup" class="link">Sign In</a>
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
<!-- Jquery Core Js --> 
<script src="{{ asset('assets/js/libscripts.bundle.js') }}"></script> <!-- Lib Scripts Plugin Js ( jquery.v3.2.1, Bootstrap4 js) --> 
<script type="text/javascript">
$( "#CC" )
  .change(function () {
    $( "#CC option:selected" ).each(function() {
        $('#phonenumber').val($(this).data('prefix'));
      //window.location.replace("{{url('/')}}/withdrawal/request/"+$(this).val());
  });
});
</script>
<script src="{{ asset('assets/js/vendorscripts.bundle.js')}}"></script> <!-- slimscroll, waves Scripts Plugin Js -->
<script src="{{ asset('assets/js/jquery.inputmask.bundle.js')}}"></script>
<script src="{{ asset('assets/js/jquery.multi-select.js')}}"></script>
<script src="{{ asset('assets/js/bootstrap-tagsinput.js')}}"></script>
<script src="{{ asset('assets/js/particles.min.js')}}"></script>
<script src="{{ asset('assets/js/particles.js')}}"></script>
<!--<script src="{{ asset('assets/js/mainscripts.bundle.js')}}"></script> -->
<script src="{{ asset('assets/js/advanced-form-elements.js')}}"></script>

</body>
</html>

{{--
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register', app()->getLocale()) }}">
                        @csrf

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        
                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="terms" {{ old('terms') ? 'checked' : '' }}> <a href="{{url('/')}}/{{app()->getLocale()}}/page/3">{{ __('Agree with the terms and conditions.') }}</a>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            @if ($errors->has('terms'))
                                    <span class="invalid-feedback d-block">
                                        <strong>{{ $errors->first('terms') }}</strong>
                                    </span>
                                @endif
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
--}}
