@extends('layouts.frontend')
@section('title')
{{ __("Privacy Policy") }}
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
               <h1 class="mb-5">Privacy Policy</h1>
               <div class="banner_content" style="text-align: justify">
                  <div>
                     Please read this notice carefully to understand our policies and practices regarding your personal information and how we will treat it. For a short-form summary, please click here. California residents can find more specific information on the California Consumer Privacy Act (CCPA) and their rights in the “CCPA” section below. Contact Us details are provided at the end of the notice for feedback or any privacy enquiries you may have.
This notice applies to the companies that are part of the Zook Money Group, which use different trading names in different territories and the list of Zook Money companies that collect or process personal information according to this notice can be found at the end of this document. The reference to Zook Money (including “we”, “us” or “our”) includes those companies and all relevant group affiliates.
References to “you” in this notice are to the individual who is accessing or applying to use the Services (as defined below) either on your own account or on behalf of a business. This includes, in relation to a customer or prospective customer of Zook Money, any sole trader and any principals, including the managing and financial directors, any other directors and officers, shareholders, partners and beneficial owners of a customer, as well as any member of staff accessing or using the Services on behalf of a customer.
Zook Money is committed to protecting your privacy and will take all appropriate steps to ensure that your personal information is treated securely and will be collected, used, stored and disclosed in accordance with this notice. This notice (together with our terms of use applying to any specific services you may purchase or use) applies:
<ul>
   <li>to the website/portal features and services provided to you when you visit our websites, portals or our payment panels our clients may use on their websites;</li>
   <li>when you apply to use and/or use Zook Money’s products and services (including any loyalty or reward schemes, whether points-based or otherwise (“Loyalty”), as well as when you request changes to the services you are using;</li>
   <li>to your use of software including terminals, mobile and desktop applications provided by Zook Money; and</li>
   <li>to email, other electronic messages including SMS, telephone, web chat, website/portal and other communications between you and Zook Money.</li>
</ul>




Zook Money also collects non-personal information, or may anonymise personal information in order to make it non-personal. Non-personal information is information that does not enable a specific individual to be identified, either directly or indirectly. Zook Money may collect, create, store, use, and disclose such non-personal information for any reasonable business purpose. For example, Zook Money may use aggregated transactional information for commercial purposes, such as trend analysis and the use of data analytics to obtain learnings and insight around payment transaction patterns and usage.
To the extent that Internet Protocol (IP) addresses (or similar identifiers) are clearly defined to be personal information under any local law, and where such local law is applicable to Services, we will manage such identifiers as personal information.
Please note that Zook Money provides services to both individual consumers and businesses and this privacy notice applies to both and should be read and interpreted accordingly.
                  </div>
               </div>
               {{-- <div class="banner_content">
                  <h1>Privacy Policy</h1>
                  Please read this notice carefully to understand our policies and practices regarding your personal
                     information and how we will treat it. For a short-form summary, please click here. California
                     residents can find more specific information on the California Consumer Privacy Act (CCPA) and
                     their rights in the “CCPA” section below. Contact Us details are provided at the end of the notice
                     for feedback or any privacy enquiries you may have.
                     This notice applies to the companies that are part of the Zook Money Group, which use different
                     trading names in different territories and the list of Zook Money companies that collect or process
                     personal information according to this notice can be found at the end of this document. The
                     reference to Zook Money (including “we”, “us” or “our”) includes those companies and all relevant
                     group affiliates.
                     References to “you” in this notice are to the individual who is accessing or applying to use the
                     Services (as defined below) either on your own account or on behalf of a business. This includes,
                     in relation to a customer or prospective customer of Zook Money, any sole trader and any
                     principals, including the managing and financial directors, any other directors and officers,
                     shareholders, partners and beneficial owners of a customer, as well as any member of staff
                     accessing or using the Services on behalf of a customer.
                     Zook Money is committed to protecting your privacy and will take all appropriate steps to ensure
                     that your personal information is treated securely and will be collected, used, stored and
                     disclosed in accordance with this notice. This notice (together with our terms of use applying to
                     any specific services you may purchase or use) applies:
                     <li>
                        <ul>
                           to the website/portal features and services provided to you when you visit our websites, portals or our payment panels our clients may use on their websites;
                        </ul>
                        <ul>
                           when you apply to use and/or use Zook Money’s products and services (including any loyalty or
                           reward schemes, whether points-based or otherwise (“Loyalty”), as well as when you request changes
                           to the services you are using;
                        </ul>
                        <ul>
                           to your use of software including terminals, mobile and desktop applications provided by
                           Zook Money; and
                        </ul>
                        <ul>
                           to email, other electronic messages including SMS, telephone, web chat, website/portal and other
                           communications between you and Zook Money.
                        </ul>
                     </li>
                     <p>
                     Zook Money also collects non-personal information, or may anonymise personal information in order
                        to make it non-personal. Non-personal information is information that does not enable a specific
                        individual to be identified, either directly or indirectly. Zook Money may collect, create, store,
                        use, and disclose such non-personal information for any reasonable business purpose. For example,
                        Zook Money may use aggregated transactional information for commercial purposes, such as trend
                        analysis and the use of data analytics to obtain learnings and insight around payment transaction
                        patterns and usage.
                        To the extent that Internet Protocol (IP) addresses (or similar identifiers) are clearly defined to
                        be personal information under any local law, and where such local law is applicable to Services, we
                        will manage such identifiers as personal information.
                        Please note that Zook Money provides services to both individual consumers and businesses and this
                        privacy notice applies to both and should be read and interpreted accordingly.

                  </p>
               </div> --}}
            </div>
         </div>
      </div>
   </section>
   <div class="box">
      <span class="mb-0 text-size-16">Home</span><span class="mb-0 text-size-16 dash">-</span><span
         class="mb-0 text-size-16 box_span">Privacy Policy</span>
   </div>
</div>


<!-- need more help? -->
@include('layouts.frontend-partials.section-need-more-help')
<!-- Footer -->
@include('layouts.frontend-partials.footer')
@endsection