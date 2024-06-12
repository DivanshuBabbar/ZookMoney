@extends('layouts.frontend')
@section('title')
{{__('Contact Us')}}
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
                            <h1>Contact Us</h1>
                            <p>We would love to hear from you! Whether you have questions, feedback, or inquiries, our team is here to assist you. Please feel free to reach out to us through our online contact form. We are committed to providing excellent customer service and ensuring a prompt response to your inquiries. Connect with us today and let us help you!</p>
                        </div>
                    </div>
                </div>
            </div>    
        </section>
        <div class="box">
            <span class="mb-0 text-size-16">Home</span><span class="mb-0 text-size-16 dash">-</span><span class="mb-0 text-size-16 box_span">Contact</span>
        </div>
    </div>
    <!--Contact-->
    <section class="message-section">
        <div class="container">
            <figure class="element1 mb-0">
                <img src="{{asset('assets/frontend/images/what-we-do-icon-1.png')}}" class="img-fluid" alt="">
            </figure>
            <div class="row position-relative">
                <div class="col-12">
                    <div class="content">
                        <h6>Connect with Us</h6>
                        <h2>Get in Touch with Us</h2>
                        <figure class="element3 mb-0">
                            <img src="{{asset('assets/frontend/images/what-we-do-element.png')}}" alt="" class="img-fluid">
                        </figure>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="message_content" data-aos="fade-up">
                        <div class="row d-flex justify-content-center">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center">
                                <h1>Mail at <span style="color:#ff6400">info@zookmoney.com</span></h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- need more help? -->
    @include('layouts.frontend-partials.section-need-more-help')
    <!-- Footer -->
    @include('layouts.frontend-partials.footer')
@endsection