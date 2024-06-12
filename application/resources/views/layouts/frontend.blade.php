<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <title>Zook Money - @yield('title')</title>
    <!-- /SEO Ultimate -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
    <meta charset="utf-8">

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


    <!-- Latest compiled and minified CSS -->
    <link href="{{asset('assets/frontend/bootstrap/bootstrap.min.css')}}" rel="stylesheet">
    <!-- Font Awesome link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <!-- StyleSheet link CSS -->
    
    <link href="{{asset('assets/frontend/css/style.css')}}" rel="stylesheet" type="text/css">
    <link href="{{asset('assets/frontend/css/responsive.css')}}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/custom-style.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('assets/frontend/css/special-classes.css')}}" type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        .navbar-brand figure img {
            width: 123px;
        }
        .login-form .login-page-logo img {
            width: 170px;
        }
        .benefit-content img {
            height: 70px;
        }
        .login-form .login-card .input-field { 
            color: #010647;
        }
        .custom-gap {
            left: -15px !important;
            margin-top:40px;
        }

        .custom-gap > * {
            margin: 12px 0 0 12px;
        }
    </style>
    @stack('styles')    
</head>
<body>
    @yield('content')

    <script src="{{asset('assets/frontend/js/jquery-3.6.0.min.js')}}"> </script>
    <script src="{{asset('assets/frontend/js/bootstrap.min.js')}}"> </script>
    <script src="{{asset('assets/frontend/js/video_link.js')}}"></script>
    <script src="{{asset('assets/frontend/js/video.js')}}"></script>
    <script src="{{asset('assets/frontend/js/counter.js')}}"></script>
    <script src="{{asset('assets/frontend/js/custom.js')}}"></script>
    <script src="{{asset('assets/frontend/js/animation_links.js')}}"></script>
    <script src="{{asset('assets/frontend/js/animation.js')}}"></script>

    @stack('scripts')    
</body>
</html>