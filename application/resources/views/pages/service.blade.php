@extends('layouts.frontend')
@section('title')
    {{__('Services')}}
@endsection
@section('content')
    @include('layouts.frontend-partials.topbar')
    <!--Header-->
    @include('layouts.frontend-partials.header')
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
                            <h1>Our Services</h1>
                            <p>Explore our comprehensive range of services, tailored to meet your specific needs and designed to deliver exceptional value and results.</p>
                        </div>
                    </div>
                </div>
            </div>    
        </section>
        <div class="box">
            <span class="mb-0 text-size-16">Home</span><span class="mb-0 text-size-16 dash">-</span><span class="mb-0 text-size-16 box_span">Service</span>
        </div>
    </div>
    <!--Services section-->
    <section class="service-section service position-relative">
        <div class="container">
            <figure class="mb-0 services-icon">
                <img src="{{asset('assets/frontend/images/services-our-services-icon-1.png')}}" class="img-fluid" alt="">
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
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                        <div class="service-box">
                            <figure class="img img5">
                                <img src="{{asset('assets/frontend/images/services-credit-debit-icon.png')}}" alt="" class="img-fluid">
                            </figure>
                            <div class="content">
                                <h3>Credit & Debit Card</h3>
                                <p class="text-size-18">Easily deposit funds using your credit card or debit card, providing a convenient and secure way to add money to your account.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-12 col-12">
                        <div class="service-box">
                            <figure class="img img6">
                                <img src="{{asset('assets/frontend/images/secure.png')}}" alt="" class="img-fluid">
                            </figure>
                            <div class="content">
                                <h3>Secure Payments</h3>
                                <p class="text-size-18">Protect your transactions with robust security measures, ensuring secure payments and safeguarding your financial information.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <figure class="element1 mb-0">
                <img src="{{asset('assets/frontend/images/what-we-do-icon-1.png')}}" class="img-fluid" alt="">
            </figure>
        </div>
    </section>
    <!--Benefits-->
    <section class="benefit-section position-relative">
        <div class="container">
            <figure class="element1 mb-0">
                <img src="{{asset('assets/frontend/images/what-we-do-icon-1.png')}}" class="img-fluid" alt="">
            </figure>
            <div class="row">
                <div class="col-12">
                    <div class="subheading">
                        <h6>Benefits</h6>
                        <h2>Benefits of using Zook Money </h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="benefit-wrapper">
                        <figure class="circle mb-0">
                            <img src="{{asset('assets/frontend/images/image-2-bg.png')}}" alt="">
                        </figure>
                        <figure class="benefit-image mb-0">
                            <img src="{{asset('assets/frontend/images/benefit-image.png')}}" alt="" class="img-fluid">
                        </figure>
                        <figure class="homeelement mb-0">
                            <img src="{{asset('assets/frontend/images/homeelement.png')}}" alt="" class="img-fluid">
                        </figure>
                        <figure class="homeelement1 mb-0">
                            <img src="{{asset('assets/frontend/images/homeelement.png')}}" alt="" class="img-fluid">
                        </figure>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="benefit-content" data-aos="fade-right">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="content">
                                    <figure class="icon icon1">
                                        <img src="{{asset('assets/frontend/images/manageyour-user-friendly-icon.png')}}" alt="" class="img-fluid">
                                    </figure> 
                                    <h4>User Friendly</h4>
                                    <p class="text-size-16 mb-0">Enjoy a user-friendly experience that makes navigation and interaction intuitive, ensuring a seamless and hassle-free journey.</p>
                                </div>   
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                <div class="content-box">
                                    <figure class="icon icon2">
                                        <img src="{{asset('assets/frontend/images/manageyour-best-support-icon.png')}}" alt="" class="img-fluid">
                                    </figure>
                                    <h4>Best Support</h4>
                                    <p class="text-size-16">We’re here to help you and your customers with anything, from setting up your business account to Seller Protection and queries with transactions.</p>
                                </div>
                            </div>
                        </div>
                        <div class="feature-downcontent">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="content">
                                        <figure class="icon icon1">
                                            <img src="{{asset('assets/frontend/images/manageyour-secure-icon.png')}}" alt="" class="img-fluid">
                                        </figure> 
                                        <h4>Secure</h4>
                                        <p class="text-size-16 mb-0">Protect your transactions with robust security measures, ensuring secure payments and safeguarding your financial information.</p>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                    <div class="content">
                                        <figure class="icon icon2">
                                            <img src="{{asset('assets/frontend/images/total-customers-icon.png')}}" alt="" class="img-fluid">
                                        </figure> 
                                        <h4>Integration</h4>
                                        <p class="text-size-16 mb-0">Seamless integration with various systems for effortless and efficient operation.</p> 
                                    </div>
                                </div>
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
    <!--Testimonial-->
    <section class="testimonial-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="content" data-aos="fade-right">
                        <figure class="quote-icon">
                            <img src="{{asset('assets/frontend/images/quote-icon.png')}}" alt="" class="img-fluid">
                        </figure>
                        <h6>TESTIMONIAL</h6>
                        <h2>What Our Customers Say</h2>
                        <p class="text-size-18">Hear from our satisfied clients about their experience.</p>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="testimonial-wrapper">
                        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner">
                                <div class="carousel-item active">
                                    <div class="review_content" style="padding-top: 90px">
                                        <p class="text-size-18">"Using their wallet has made my financial transactions so much easier and faster. I love the convenience it offers!"</p>
                                        <h4 class="mb-0">Sarah D.</h4>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <div class="review_content" style="padding-top: 90px">
                                        <p class="text-size-18">"The integration capabilities of their wallet have saved me valuable time and effort in managing my payments. Highly recommended!"</p>
                                        <h4 class="mb-0">Mark S.</h4>
                                    </div>
                                </div>
                                <div class="carousel-item">
                                    <div class="review_content" style="padding-top: 90px">
                                        <p class="text-size-18">"I feel secure knowing that my sensitive financial information is well-protected with their wallet. It's a reliable and trustworthy solution."</p>
                                        <h4 class="mb-0">Emily L.</h4>
                                    </div>
                                </div>
                                <div class="pagination-outer">
                                    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                                        <i class="fa-solid fa-arrow-left"></i>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                                        <i class="fa-solid fa-arrow-right"></i>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                            </div>
                        </div> 
                        <figure class="mb-0 testimonial-circle">
                            <img src="{{asset('assets/frontend/images/testimonial-backimage.png')}}" alt="">
                        </figure>
                        <figure class="homeelement mb-0">
                            <img src="{{asset('assets/frontend/images/homeelement.png')}}" alt="" class="img-fluid">
                        </figure>
                        <figure class="homeelement1 mb-0">
                            <img src="{{asset('assets/frontend/images/homeelement.png')}}" alt="" class="img-fluid">
                        </figure>
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