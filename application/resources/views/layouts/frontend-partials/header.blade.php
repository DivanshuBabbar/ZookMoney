<header class="header">
    <div class="container">
        <nav class="navbar position-relative navbar-expand-lg navbar-light">
            <a class="navbar-brand" href="{{url('/')}}">
                <figure class="mb-0">
                    <img src="{{asset('assets/frontend/images/zookmoney.png')}}" class="img-fluid" alt="Logo">
                </figure>
            </a>
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" 
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            <span class="navbar-toggler-icon"></span>
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item {{ request()->is(app()->getLocale()) ? 'active' : '' }}">
                        <a class="nav-link" href="{{url('/')}}">Home</a>
                    </li>
                    <li class="nav-item {{ request()->is(app()->getLocale() . '/about-us') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('pages.about_us', app()->getLocale()) }}">About us</a>
                    </li>
                    <li class="nav-item {{ request()->is(app()->getLocale() . '/service-of-us') ? 'active' : '' }}">
                        <a class="nav-link" 
                            href="{{ route('pages.service', app()->getLocale()) }}">Services</a>
                    </li>
                    <li class="nav-item {{ request()->is(app()->getLocale() . '/contact-us') ? 'active' : '' }}">
                        <a class="nav-link" 
                            href="{{ route('pages.contact_us', app()->getLocale()) }}">Contact</a>
                    </li>


                  
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url( app()->getLocale(), 'register') }}">{{ __('Register') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link signup" href="{{ url( app()->getLocale(), 'login') }}">
                            <i class="fa-solid fa-user-lock"></i>{{ __('Log in') }}</a>
                    </li>
                   {{-- <li class="nav-item">
                        <a class="nav-link signup" href="{{ url( app()->getLocale(), 'reseller') }}">
                        <i class="fa-solid fa-user-lock"></i>{{ __('Reseller') }}</a>
                    </li>--}}
                
                </ul>
            </div>
        </nav>
    </div>
</header>
