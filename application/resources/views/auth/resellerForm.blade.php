@extends('layouts.frontend')
@section('title')
    {{ __("Register") }}
@endsection
@push('styles')
<style>
    .merchant-checkbox {
        top: .8rem;
        width: 1.25rem;
        height: 1.25rem;
    }
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@endpush
@section('content')
    <!-- Sign-Up Form section-->
    <section class="login-form sign-up-form d-flex align-items-center">
        <div class="container">
            <div class="login-form-title text-center">
                <a href="{{url('/')}}">
                <figure class="login-page-logo">
                    <img src="{{general_setting('site_logo')}}" alt="">
                </figure>
                </a>
                <h2>{{ general_setting('site_name') }}</h2>
            </div>
            <div class="login-form-box">
                <div class="login-card">
                    <form class="form" method="POST" action="{{ route('resellerFormRegister', app()->getLocale()) }}">
                        @csrf
                      
                        <div class="form-group">
                            <label for="name">User Name</label>
                            <input class="input-field form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" type="text" name="name" value="{{ old('name') }}" placeholder="{{__('Enter User Name')}}" required autofocus>
                            <span class="input-group-addon"><i class="zmdi zmdi-account-circle"></i></span>
                            @if ($errors->has('name'))
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif

                        </div>
                        <div class="form-group">
                            <label for="first_name">First Name</label>
                            <input type="text" class="input-field form-control {{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" value="{{ old('first_name') }}" placeholder="{{__('Enter First Name')}}" required>
                            <span class="input-group-addon"><i class="zmdi zmdi-account-circle"></i></span>
                            @if ($errors->has('first_name'))
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $errors->first('first_name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last Name</label>
                            <input type="text" class="input-field form-control {{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ old('last_name') }}" placeholder="{{__('Enter Last Name')}}" required>
                            <span class="input-group-addon"><i class="zmdi zmdi-account-circle"></i></span>
                            @if ($errors->has('last_name'))
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $errors->first('last_name') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="email">Your e-mail</label>
                            <input type="email" class="input-field form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="{{__('Enter Email')}}" required >
                            <span class="input-group-addon"><i class="zmdi zmdi-email"></i></span>
                            @if ($errors->has('email'))
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="CC">Select Your County</label>
                            <select class="form-control z-index show-tick select-option" placeholder="Select Your Contry" name="CC" id="CC">
                                 <option value="" data-prefix="">Select</option>
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
                            <label for="phone">Mobile Number</label>
                            <input type="phone" class="form-control input-field {{ $errors->has('phone') ? ' is-invalid' : '' }}" 
                                name="phone" value="{{ old('phone') }}" placeholder="{{__('Mobile Number')}}" id="phonenumber" required >
                            <span class="input-group-addon"><i class="zmdi zmdi-phone"></i></span>
                            @if ($errors->has('phone'))
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $errors->first('phone') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input id="password" type="password" class="form-control input-field {{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" placeholder="{{__('Password')}}"  required>
                            <span class="input-group-addon"><i class="zmdi zmdi-lock"></i></span>
                            @if ($errors->has('password'))
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                       <div class="form-group">
                            <label for="password-confirm">Confirm Password</label>
                            <input id="password-confirm" type="password" class="form-control input-field" name="password_confirmation" placeholder="{{__('Repeat Password')}}" required>
                            <span class="input-group-addon"><i class="zmdi zmdi-lock"></i></span>
                            @if ($errors->has('password_confirmation'))
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div>
                            <label class="font-weight-normal mt-md-3 mt-2 mb-md-4 mb-3" style="cursor: pointer;">
                            <input class="checkbox" type="checkbox" id="terms" name="terms">
                            {{__('I read and Agree to the')}} <a href="{{ route('pages.terms_of_use', app()->getLocale()) }}">{{__('Terms of Usage')}}</a>
                            </label>
                            @if ($errors->has('terms'))
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $errors->first('terms') }}</strong>
                                </span>
                            @endif
                        </div>
                        
                        <button type="submit" class="btn btn-primary mb-0">@lang('Sign Up') <i class="las la-sign-in-alt"></i></button>
                    </form>
                </div>
                <div class="join-now-outer text-center">
                <a class="mb-0" href="{{url('/')}}/{{app()->getLocale()}}/reseller">Already have an account?</a>
                </div>
            </div>   
        </div>
        <figure class="mb-0 need-layer">
            <img src="{{asset('assets/frontend/images/need-layer.png')}}" alt="" class="img-fluid">
        </figure>
    </section>
@endsection
@push('scripts')
<script src="{{asset('assets/front/ajax/libs/toastr_js/latest/js/toastr.min.js')}}"></script>

<script>
    toastr.error('Error', 'Error Title');

    $( "#CC" ).change(function () {
        $( "#CC option:selected" ).each(function() {
            $('#phonenumber').val($(this).data('prefix'));
        });
    });
</script>
@endpush