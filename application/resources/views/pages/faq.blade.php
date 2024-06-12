@extends('layouts.frontend')
@section('title')
{{ __("FAQ") }}
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
                  <h1>FAQ</h1>
               </div>
            </div>
         </div>
      </div>
   </section>
   <div class="box">
      <span class="mb-0 text-size-16">Home</span><span class="mb-0 text-size-16 dash">-</span><span
         class="mb-0 text-size-16 box_span">FAQ</span>
   </div>
</div>
<!--FAQ section-->
<section class="faq-section">
   <div class="container">
      <div class="row">
         <div class="col-12">
            <div class="subheading">
               <h6>General Questions</h6>
               <h2>Frequently Asked Questions</h2>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="faq" data-aos="fade-up">
               <div class="row">
                  <div class="col-12">
                     <div class="accordian-section-inner position-relative">
                        <div class="accordian-inner">
                           <div id="accordion1">
                              <div class="accordion-card">
                                 <div class="card-header" id="headingOne">
                                    <a href="#" class="btn btn-link collapsed" data-toggle="collapse"
                                       data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                       <h4>Where Can I Find More Information?</h4>
                                    </a>
                                 </div>
                                 <div id="collapseOne" class="collapse" aria-labelledby="headingOne">
                                    <div class="card-body">
                                       <p class="text-size-16 text-left mb-0 p-0">You can mail us at <a href="mailto:info@zookpe.com" style="color:#ff6400">info@zookmoney.com</a></p>
                                    </div>
                                 </div>
                              </div>
                              <div class="accordion-card">
                                 <div class="card-header" id="headingTwo">
                                    <a href="#" class="btn btn-link collapsed" data-toggle="collapse"
                                       data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                       <h4>What Are Your Terms and Conditions?</h4>
                                    </a>
                                 </div>
                                 <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo">
                                    <div class="card-body">
                                       <p class="text-size-16 text-left mb-0 p-0">Checkout our <a href="{{ route('pages.terms_of_use', app()->getLocale()) }}" class="text-size-16">terms and conditions</a> page for details</p>
                                    </div>
                                 </div>
                              </div>
                              <div class="accordion-card">
                                 <div class="card-header" id="headingThree">
                                    <a href="#" class="btn btn-link collapsed" data-toggle="collapse"
                                       data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                       <h4>How can I contact customer support?</h4>
                                    </a>
                                 </div>
                                 <div id="collapseThree" class="collapse" aria-labelledby="headingThree">
                                    <div class="card-body">
                                       <p class="text-size-16 text-left mb-0 p-0">You can reach our customer support team through email, phone, or live chat on our website.</p>
                                    </div>
                                 </div>
                              </div>
                              <div class="accordion-card">
                                 <div class="card-header" id="headingFour">
                                    <a href="#" class="btn btn-link collapsed" data-toggle="collapse"
                                       data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                       <h4>What payment methods do you accept?</h4>
                                    </a>
                                 </div>
                                 <div id="collapseFour" class="collapse" aria-labelledby="headingFour">
                                    <div class="card-body">
                                       <p class="text-size-16 text-left mb-0 p-0">We accept various payment methods, including credit cards, debit cards, and online payment platforms such as UPI.</p>
                                    </div>
                                 </div>
                              </div>
                              <div class="accordion-card">
                                 <div class="card-header" id="headingFive">
                                    <a href="#" class="btn btn-link collapsed" data-toggle="collapse"
                                       data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                       <h4>Is my personal information secure?</h4>
                                    </a>
                                 </div>
                                 <div id="collapseFive" class="collapse" aria-labelledby="headingFive">
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
         
      </div>
   </div>
</section>
<!-- need more help? -->
@include('layouts.frontend-partials.section-need-more-help')
<!-- Footer -->
@include('layouts.frontend-partials.footer')
@endsection