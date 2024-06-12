@extends('layouts.frontend')
@section('title')
    {{general_setting('title')}}
@endsection
@section('content')
    @include('layouts.frontend-partials.topbar')
    <!--Header-->
    @include('layouts.frontend-partials.header')
    <!--Banner-->
    <section class="bannermain position-relative">
        <figure class="mb-0 bgshape">
            <img src="{{asset('assets/frontend/images/homebanner-bgshape.png')}}" alt="" class="img-fluid">
        </figure>
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-5 col-sm-12 col-12">
                    <div class="banner" data-aos="fade-right">
                        <h6>Simple. Transparent. Secure </h6>
                        <h1>The Smart Way for<span>Online Payment</span> Solution.</h1>
                        <p class="banner-text">Pay online, send money with a digital wallet used by millions.</p>
                        <div class="button"><a class="button_text" href="{{ url( app()->getLocale(), 'register') }}">Open a Free Account</a></div>
                    </div>
                </div>
                <div class=" col-lg-7 col-md-7 col-sm-12">
                    <div class="banner-wrapper">
                        <figure class="mb-0 homeelement1">
                            <img src="{{asset('assets/frontend/images/homeelement1.png')}}" class="img-fluid" alt="">
                        </figure>
                        <figure class="mb-0 banner-image">
                            <img src="{{asset('assets/frontend/images/homebanner-image.png')}}" class="img-fluid" alt="banner-image">
                        </figure>
                        <figure class="mb-0 content img-bg">
                            <img src="{{asset('assets/frontend/images/homebanner-img-bg.png')}}" alt="banner-image-bg">
                        </figure>
                        <figure class="mb-0 homeelement">
                            <img src="{{asset('assets/frontend/images/homeelement.png')}}" class="img-fluid" alt="">
                        </figure>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--What-we-do-->
    <section class="what-we-do position-relative">
        <div class="container">
            <figure class="element1 mb-0">
                <img src="{{asset('assets/frontend/images/what-we-do-icon-1.png')}}" class="img-fluid" alt="">
            </figure>
            <div class="row">
                <div class="col-12">
                    <div class="subheading" data-aos="fade-right">
                        <h6>What we do</h6>
                        <h2>Get Ready To Have Best Smart Payments in The World</h2>
                    </div>
                </div>
            </div>
            <div class="row position-relative">
                <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                    <div class="service1">
                        <figure class="img">
                            <img src="{{asset('assets/frontend/images/what-we-do-credit-debit-icon.png')}}" alt="" class="img-fluid">
                        </figure>
                        <h3>Payment Solution</h3>
                        <p class="mb-0 text-size-18">Managing your money with Zook Money is easy – and safe.</p>
                    </div>
                </div>
                <figure class="arrow1 mb-0" data-aos="fade-down">
                    <img src="{{asset('assets/frontend/images/what-we-do-arrow-1.png')}}" class="img-fluid" alt="">
                </figure>
                <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                    <div class="service1 service2">
                        <figure class="img">
                            <img src="{{asset('assets/frontend/images/what-we-do-growth--icon.png')}}" alt="" class="img-fluid">
                        </figure>
                        <h3>Growth Business</h3>
                        <p class="mb-0 text-size-18">With your Business account, you get access to diverse tools to help run your business and help it grow.</p>
                    </div>
                </div>
                <figure class="arrow2 mb-0" data-aos="fade-up">
                    <img src="{{asset('assets/frontend/images/what-we-do-arrow-2.png')}}" class="img-fluid" alt="">
                </figure>
                <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                    <div class="service1">
                        <figure class="img">
                            <img src="{{asset('assets/frontend/images/what-we-do-connected-people-icon.png')}}" alt="" class="img-fluid">
                        </figure>
                        <h3>Connected People</h3>
                        <p class="mb-0 text-size-18">viverra maecenas accumsan lacus vel facili sis consectetur adipiscing
                            mae-cenelit seiscingsd.</p>
                    </div>
                </div>
                <figure class="element3 mb-0">
                    <img src="{{asset('assets/frontend/images/what-we-do-element.png')}}" alt="">
                </figure>
            </div>
        </div>
    </section>
    <!--About self-->
    @include('layouts.frontend-partials.section-about-self')
    <!--Services section-->
    <section class="service-section">
        <div class="container">
            <div class="row position-relative">
                <div class="service-content">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <figure class="mb-0 services-icon">
                            <img src="{{asset('assets/frontend/images/services-our-services-icon-1.png')}}" class="img-fluid" alt="">
                        </figure>
                        <h6>OUR SERVICES</h6>
                        <h2>Smart Solution for Your Payment</h2>
                        <figure class="service-image" data-aos="fade-up">
                            <img src="{{asset('assets/frontend/images/services-our-services-image.png')}}" class="img-fluid" alt="">
                        </figure>
                    </div>
                </div>
            </div>
            <figure class="element1 mb-0">
                <img src="{{asset('assets/frontend/images/what-we-do-icon-1.png')}}" class="img-fluid" alt="">
            </figure>
            <div class="services-data">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                        <div class="service-box">
                            <figure class="img img1">
                                <img src="{{asset('assets/frontend/images/services-payment-management-icon.png')}}" alt="" class="img-fluid">
                            </figure>
                            <div class="content">
                                <h3>Payment Management</h3>
                                <p class="text-size-18">Efficiently handle and organize payments with a streamlined payment management system, ensuring accuracy and smooth financial operations.</p>
                                <a href="{{ route('pages.service', app()->getLocale()) }}" class="more">More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                        <div class="service-box">
                            <figure class="img img2">
                                <img src="{{asset('assets/frontend/images/services-dashboard-icon.png')}}" alt="" class="img-fluid">
                            </figure>
                            <div class="content">
                                <h3>Personal Dashboard</h3>
                                <p class="text-size-18">Access all your important information and data at a glance with a personalized dashboard, empowering you to stay organized and in control.</p>
                                <a href="{{ route('pages.service', app()->getLocale()) }}" class="more">More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                        <div class="service-box">
                            <figure class="img img3">
                                <img src="{{asset('assets/frontend/images/services-integrated-payment-icon.png')}}" alt="" class="img-fluid">
                            </figure>
                            <div class="content">
                                <h3>Integrated Payments</h3>
                                <p class="text-size-18">Simplify your financial transactions with integrated payments, seamlessly merging payment processing and management for a seamless and efficient experience.</p>
                                <a href="{{ route('pages.service', app()->getLocale()) }}" class="more">More</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                        <div class="service-box">
                            <figure class="img img4">
                                <img src="{{asset('assets/frontend/images/services-friendly.png')}}" alt="" class="img-fluid">
                            </figure>
                            <div class="content">
                                <h3>Business Tracking</h3>
                                <p class="text-size-18">Efficiently track and monitor your business operations, empowering you to make informed decisions and drive success.</p>
                                <a href="{{ route('pages.service', app()->getLocale()) }}" class="more">More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                        <div class="service-box">
                            <figure class="img img4">
                                <img src="{{asset('assets/frontend/images/what-we-do-personal-account-icon.png')}}" alt="" class="img-fluid">
                            </figure>
                            <div class="content">
                                <h3>UPI & IMPS</h3>
                                <p class="text-size-18">Deposit funds effortlessly using UPI and IMPS, leveraging fast and secure payment methods for quick and convenient transactions.</p>
                                <a href="{{ route('pages.service', app()->getLocale()) }}" class="more">More</a>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                        <figure class="mb-0 mobile-image" data-aos="fade-right">
                            <img src="{{asset('assets/frontend/images/services-mobile-image.png')}}" alt="" class="img-fluid">
                        </figure>
                    </div> --}}
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                        <div class="service-box">
                            <figure class="img img5">
                                <img src="{{asset('assets/frontend/images/services-credit-debit-icon.png')}}" alt="" class="img-fluid">
                            </figure>
                            <div class="content">
                                <h3>Credit & Debit Card</h3>
                                <p class="text-size-18">Easily deposit funds using your credit card or debit card, providing a convenient and secure way to add money to your account.</p>
                                <a href="{{ route('pages.service', app()->getLocale()) }}" class="more">More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <figure class="element2 mb-0">
                <img src="{{asset('assets/frontend/images/what-we-do-icon-2.png')}}" class="img-fluid" alt="">
            </figure>
        </div>
    </section>
    <!-- manage -->
    <section class="manage-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="manage-content" data-aos="fade-right">
                        <h2>Manage Everything in Your Hand</h2>
                        <div class="first">
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-12 col-12">
                                    <figure class="mb-0 icon">
                                        <img src="{{asset('assets/frontend/images/manageyour-user-friendly-icon.png')}}" alt="">
                                    </figure>
                                </div>
                                <div class="col-lg-10 col-md-10 col-sm-12 col-12">
                                    <div class="content">
                                        <h4>User Friendly</h4>
                                        <p class="text-size-16 text">Enjoy a user-friendly experience that makes navigation and interaction intuitive, ensuring a seamless and hassle-free journey.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="secound">
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-12 col-12">
                                    <figure class="mb-0 icon">
                                        <img src="{{asset('assets/frontend/images/manageyour-best-support-icon.png')}}" alt="">
                                    </figure>
                                </div>
                                <div class="col-lg-10 col-md-10 col-sm-12 col-12">
                                    <div class="content">
                                        <h4>Best Support</h4>
                                        <p class="text-size-16">We’re here to help you and your customers with anything, from setting up your business account to Seller Protection and queries with transactions.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="third">
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-12 col-12">
                                    <figure class="mb-0 icon">
                                        <img src="{{asset('assets/frontend/images/manageyour-secure-icon.png')}}" alt="">
                                    </figure>
                                </div>
                                <div class="col-lg-10 col-md-10 col-sm-12 col-12">
                                    <div class="content">
                                        <h4>Secure</h4>
                                        <p class="text-size-16">Protect your transactions with robust security measures, ensuring secure payments and safeguarding your financial information.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="manage-wrapper">
                        <figure class="mb-0 homeelement1">
                            <img src="{{asset('assets/frontend/images/homeelement1.png')}}" class="img-fluid" alt="">
                        </figure>
                        <figure class="mb-0 manage-image">
                            <img src="{{asset('assets/frontend/images/manage-your-everything-image.png')}}" class="img-fluid" alt="">
                        </figure>
                        <figure class="mb-0 content img-bg">
                            <img src="{{asset('assets/frontend/images/manageyour-mange-your-bg.png')}}" alt="" class="">
                        </figure>
                        <figure class="mb-0 homeelement">
                            <img src="{{asset('assets/frontend/images/homeelement.png')}}" class="img-fluid" alt="">
                        </figure>
                    </div>
                </div>
            </div>
        </div>
        <figure class="mb-0 manage-layer">
            <img src="{{asset('assets/frontend/images/mange-layer.png')}}" alt="" class="img-fluid">
        </figure>
    </section>
    <!-- need more help? -->
    @include('layouts.frontend-partials.section-need-more-help')
    <!-- Footer -->
    @include('layouts.frontend-partials.footer')
@endsection