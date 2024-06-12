<div class="container">
    <div class="middle-portion">
        <div class="row">
            <div class="col-lg-3 col-md-5 col-sm-6 col-12">
                <a href="{{url('/')}}">
                    <figure class="footer-logo">
                        <img src="{{asset('assets/frontend/images/zookmoney.png')}}" class="img-fluid" alt="">
                    </figure>
                </a>
                <p class="text-size-16 footer-text">The Smart Way for Online Payment Solution.
                    Pay online, send money with a digital wallet used by millions.</p>
                <figure class="custom-gap mb-0 payment-icon">
                    <img src="{{asset('assets/frontend/images/norton.png')}}" class="img-fluid" alt="">
                    <img src="{{asset('assets/frontend/images/pci.png')}}" class="img-fluid" alt="">
                    <img src="{{asset('assets/frontend/images/truste.png')}}" class="img-fluid" alt="">
                    <img src="{{asset('assets/frontend/images/mcafee.png')}}" class="img-fluid" alt="">
                    <img src="{{asset('assets/frontend/images/phonepe.png')}}" class="img-fluid" alt="">
                    <img src="{{asset('assets/frontend/images/gpay.png')}}" class="img-fluid" alt="">
                    <img src="{{asset('assets/frontend/images/paytm.png')}}" class="img-fluid" alt="">
                    <img src="{{asset('assets/frontend/images/upi.png')}}" class="img-fluid" alt="">
                    <img src="{{asset('assets/frontend/images/visa.png')}}" class="img-fluid" alt="">
                    <img src="{{asset('assets/frontend/images/mastercard.png')}}" class="img-fluid" alt="">
                </figure>
            </div>
            <div class="col-lg-1 col-md-1 col-sm-12 col-12 d-lg-block d-none">
    
            </div>
            <div class="col-lg-2 col-md-3 col-sm-12 col-12 d-md-block d-none">
                <div class="links">
                    <h4 class="heading">Important Link</h4>
                    <hr class="line">
                    <ul class="list-unstyled mb-0">
                        <li><a href="{{url('/')}}" class=" text-size-16 text text-decoration-none">Home</a></li>
                        <li><a href="{{ route('pages.about_us', app()->getLocale()) }}" class=" text-size-16 text text-decoration-none">About Us</a></li>
                        <li><a href="{{ route('pages.service', app()->getLocale()) }}" class=" text-size-16 text text-decoration-none">Services</a></li>
                        <li><a href="{{ route('pages.contact_us', app()->getLocale()) }}" class=" text-size-16 text text-decoration-none">Contact</a></li>
                        <li><a href="{{ route('pages.faq', app()->getLocale()) }}" class=" text-size-16 text text-decoration-none">FAQ</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-12 d-lg-block d-none">
                <div class="links">
                    <h4 class="heading">Support</h4>
                    <hr class="line">
                    <ul class="list-unstyled mb-0">
                        <li><a href="{{ route('pages.contact_us', app()->getLocale()) }}" class=" text-size-16 text text-decoration-none">Support</a></li>
                        <li><a href="{{ route('pages.privacy_policy', app()->getLocale()) }}" class=" text-size-16 text text-decoration-none">Privacy Policy</a></li>
                        <li><a href="{{ route('pages.terms_of_use', app()->getLocale()) }}" class=" text-size-16 text text-decoration-none">Terms of Use</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 col-12 d-lg-block d-none">
                <div class="links">
                    <h4 class="heading">Download Our App</h4>
                    <hr class="line">
                    <ul class="list-unstyled mb-0">
                        <li><button type="button" style="background: coral;border: 1px solid coral;color: aliceblue;" class="btn btn-outline-primary">Download For Android</button></li>
                        <br>
                        <li><button type="button" style="background: coral;border: 1px solid coral;color: aliceblue;" class="btn btn-outline-primary">Download For ios</button></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-12 d-sm-block">
                <div class="icon">
                    <h4 class="heading">Get in Touch</h4>
                    <hr class="line">
                    <h5>Mail at <span style="color:#ff6400">info@zook.money</span></h5>
                    {{-- <ul class="list-unstyled mb-0"> --}}
                        {{-- <li class="text-size-16 text">Email: <a href="mailto:info@repay.com" class="mb-0 text text-decoration-none text-size-16">info@repay.com</a></li> --}}
                        {{-- <li class="text-size-16 text">Phone: <a href="tel:+4733378901" class="mb-0 text text-decoration-none text-size-16">+1 234 567 89 0 0</a></li> --}}
                        {{-- <li class="text-size-16 text1">Fax: <a href="tel:+198765432199" class="mb-0 text text-decoration-none text-size-16">+1 (987) 654 321 9 9</a></li> --}}
                        {{-- <li class="social-icons">
                            <div class="circle"><a href="#"><i class="fa-brands fa-facebook-f"></i></a></div>
                            <div class="circle"><a href="#"><i class="fa-brands fa-twitter"></i></a></div>
                            <div class="circle"><a href="#"><i class="fa-brands fa-linkedin"></i></a></div>
                            <div class="circle"><a href="#"><i class="fa-brands fa-pinterest"></i></a></div>
                        </li> --}}
                    {{-- </ul> --}}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>

   $('#pay_in_api').click(function (argument) {
        var pdfUrl = 'https://zookpe.com/application/storage/app/public/uploads/1712599692_ZookPe%20Integration%20Docs%20&%20Tutorial.pdf';
        var name = "Pay In.pdf";
        downloadPDF(pdfUrl,name);
   });

    $('#pay_out_api').click(function (argument) {
        var pdfUrl = 'https://zookpe.com/application/storage/app/public/uploads/1712599292_ZookPe%20Payout%20Integration%20Docs.pdf';
        var name = "Pay Out.pdf";
        downloadPDF(pdfUrl,name);
    });

    function downloadPDF(url,name)
    {
          var anchorElement = document.createElement('a');
          var fileName = name;
          var fileLink = url;
          anchorElement.href = fileLink;
          anchorElement.download = fileName;
          anchorElement.target = '_blank';
          document.body.appendChild(anchorElement);
          console.log(anchorElement);
          anchorElement.click();

    }
    
  
</script>
@endpush
