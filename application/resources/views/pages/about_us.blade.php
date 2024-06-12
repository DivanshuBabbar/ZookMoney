@extends('layouts.frontend')
@section('title')
    {{__('About Us')}}
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

    <!-- Sub-Banner -->
    <div class="sub-banner">
        <section class="banner-section">
            <figure class="mb-0 bgshape">
                <img src="{{asset('assets/frontend/images/homebanner-bgshape.png')}}" alt="" class="img-fluid">
            </figure>
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="banner_content">
                            <h1>About Us</h1>
                            <p>We Are The Best Online Payment Gateway Agency.</p>
                        </div>
                    </div>
                </div>
            </div>    
        </section>
        <div class="box">
            <span class="mb-0 text-size-16">Home</span><span class="mb-0 text-size-16 dash">-</span><span class="mb-0 text-size-16 box_span">About</span>
        </div>
    </div>
    <!--About-->
    <section class="what-we-do about-section position-relative">
        <div class="container">
            <figure class="element1 mb-0">
                <img src="{{asset('assets/frontend/images/what-we-do-icon-1.png')}}" class="img-fluid" alt="">
            </figure>
            
            <div class="row position-relative">
                <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                    <div class="service1">
                        <figure class="img img1">
                            <img src="{{asset('assets/frontend/images/vision-icon.png')}}" alt="" class="img-fluid">
                        </figure>
                        <h3>Our Vision</h3>
                        <p class="mb-0 text-size-18">Our vision is to innovate and inspire, creating a future where technology empowers individuals and businesses to reach their full potential.</p>
                    </div>
                </div>
                <figure class="arrow1 mb-0" data-aos="fade-down">
                    <img src="{{asset('assets/frontend/images/what-we-do-arrow-1.png')}}"  class="img-fluid" alt="">
                </figure>
                <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                    <div class="service1 service2">
                        <figure class="img img2">
                            <img src="{{asset('assets/frontend/images/mission-icon.png')}}" alt="" class="img-fluid">
                        </figure>
                        <h3>Our Mission</h3>
                        <p class="mb-0 text-size-18">Empower businesses and individuals through our mission of delivering innovative solutions and exceptional service, driving growth and success in a rapidly evolving world.</p>
                    </div>
                </div>
                <figure class="arrow2 mb-0" data-aos="fade-up">
                    <img src="{{asset('assets/frontend/images/what-we-do-arrow-2.png')}}"  class="img-fluid" alt="">
                </figure>
                <div class="col-lg-4 col-md-6 col-sm-12 col-12">
                    <div class="service1">
                        <figure class="img img3">
                            <img src="{{asset('assets/frontend/images/strategyicon.png')}}" alt="" class="img-fluid">
                        </figure>
                        <h3>Strategy</h3>
                        <p class="mb-0 text-size-18">Develop a robust strategy that aligns with your goals, leveraging market insights and tailored approaches to drive sustainable competitive advantage.</p>
                    </div>
                </div>
                <figure class="element3 mb-0">
                    <img src="{{asset('assets/frontend/images/what-we-do-element.png')}}" alt="">
                </figure>
            </div>
            <figure class="element2 mb-0">
                <img src="{{asset('assets/frontend/images/what-we-do-icon-2.png')}}" class="img-fluid" alt="">
            </figure>
        </div>
    </section>
    <!--About self-->
    @include('layouts.frontend-partials.section-about-self')
    <!--FAQ section-->
    <section class="accordian-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-6 col-sm-12 col-12">
                    <div class="faq">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="accordian-section-inner position-relative" data-aos="fade-up">
                                    <div class="accordian-inner">
                                        <div id="accordion1">
                                            <div class="accordion-card">
                                                <div class="card-header" id="headingOne">
                                                    <a href="#" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                                        <h4>How can I contact customer support?</h4>
                                                    </a>
                                                </div>
                                                <div id="collapseOne" class="collapse" aria-labelledby="headingOne">
                                                    <div class="card-body">
                                                        <p class="text-size-16 text-left mb-0 p-0">You can reach our customer support team through email, phone, or live chat on our website.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-card">
                                                <div class="card-header" id="headingTwo">
                                                    <a href="#" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                        <h4>What payment methods do you accept?</h4>
                                                    </a>
                                                </div>
                                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo">
                                                    <div class="card-body">
                                                        <p class="text-size-16 text-left mb-0 p-0">We accept various payment methods, including credit cards, debit cards, and online payment platforms such as UPI.</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-card for-space">
                                                <div class="card-header" id="headingThree">
                                                    <a href="#" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                        <h4>Is my personal information secure?</h4>
                                                    </a>
                                                </div>
                                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree">
                                                    <div class="card-body">
                                                        <p class="text-size-16 text-left mb-0 p-0">Yes, we prioritize the security and confidentiality of your personal information. We implement strict security measures to protect your data and ensure privacy.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-1 d-lg-block d-none">
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="accordion-content">
                        <h6 style="font-size: 28px;">frequently asked questions</h6>
                        <h3>Tips & Information</h3>
                        <p class="text-size-16">Discover valuable tips and information, empowering you with knowledge and insights to make informed decisions and excel in your endeavors.</p>
                    </div>
                </div>
            </div>
        </div>
        <figure class="mb-0 manage-layer">
            <img src="{{asset('assets/frontend/images/mange-layer.png')}}" alt="" class="img-fluid">
        </figure>
    </section>
    <!-- Footer -->
    @include('layouts.frontend-partials.footer')
@endsection