@extends('layouts.frontend')
@section('title')
{{ __("Reseller") }}
@endsection
@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
@endpush
@section('content')
<section class="login-form d-flex align-items-center">
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
                @include('flash')
               <form class="form" method="POST" action="{{ route('reseller', app()->getLocale()) }}" >
                    @csrf
                    <div class="form-group">
                        <label for="email">Enter your e-mail</label>

                        <input  id="email" type="email" class="form-control input-field {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email" value="{{ old('email') }}" placeholder="{{ __('Email') }}" required autofocus disabled="disabled">
                        <span class="input-group-addon"><i class="zmdi zmdi-account-circle"></i></span>
                        @if ($errors->has('email'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                    </div>
                    <div class="form-group">
                        <label for="password">Enter your password</label>
                        <input id="password" type="password" class="form-control input-field {{ $errors->has('password') ? 'is-invalid' : '' }}" name="password" placeholder="{{ __('Password') }}" required disabled="disabled">
                        <span class="input-group-addon"><i class="zmdi zmdi-lock"></i></span>
                        @if ($errors->has('password'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary" disabled="disabled">@lang('Login') </button>
                    <div>
                        <a href="{{ route('password.request', app()->getLocale()) }}" class="forgot-password float-right" onclick="return false">Lost Password?</a>
                    </div>
                </form>
            </div>
            <div class="join-now-outer text-center">
                <a class="mb-0" href="{{url('/')}}/{{app()->getLocale()}}/register/reseller" >Join now, create your FREE reseller account</a>
            </div>
        </div>
    </div>
    <figure class="mb-0 need-layer">
        <img src="{{ asset('assets/frontend/images/need-layer.png') }}" alt=""
            class="img-fluid">
    </figure>
</section>
@endsection
@push('scripts')
    <script
        src="{{ asset('assets/front/ajax/libs/toastr_js/latest/js/toastr.min.js') }}">
    </script>

    <script>
        toastr.error('Error', 'Error Title');
    </script>
@endpush