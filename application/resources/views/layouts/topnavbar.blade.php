
                          <nav class="top_navbar">
    <div class="container">
        <div class="row clearfix">
            <div class="col-12">
                @include('cookieConsent::index')
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-12">
                <div class="navbar-logo">
                    <a href="javascript:void(0);" class="bars"></a>
                    <a class="navbar-brand" href="{{ url(app()->getLocale().'/') }}">
                        @impersonating

                        @else
                        <img src="{{general_setting('site_icon')}}" height="42" alt="{{general_setting('site_name')}}">
                        @endImpersonating
                        <span class="m-l-10">@if(setting('site.enable_text_logo')){{general_setting('site_name')}}@endif</span>
                    </a>
                </div>
                @auth
                <ul class="nav navbar-nav">
                    @impersonating
                    <li class="dropdown task ">
                      <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="icon-user"></i>
                            <span class="label-count">{{__('Impersonated')}}</span>
                        </a>  
                    </li>
                    @endImpersonating
                    <li class="dropdown task ">
                        <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button">
                            <i class="icon-flag"></i>
                            <span class="label-count">{{app()->getLocale()}}</span>
                        </a>
                        <ul class="dropdown-menu  pullDown">
                            <li>
                                <a href="{{route('home','en')}}">
                                    {{-- <i class="icon-user m-r-10"></i> --}}
                                    <span>{{__('English')}}</span> 
                                </a>
                            </li>
                             <li>
                                <a href="{{route('home','fr')}}">
                                    {{-- <i class="icon-user m-r-10"></i> --}}
                                    <span>{{__('French')}}</span> 
                                </a>
                            </li>  
                            <li>
                                <a  href="{{route('home','zh_CN')}}">
                                    {{-- <i class="icon-directions m-r-10"></i> --}}
                                    <span>{{__('Chinese')}}</span>
                                </a>
                            </li>
                            <li>
                                <a  href="{{route('home','pt')}}">
                                    {{-- <i class="icon-directions m-r-10"></i> --}}
                                    <span>{{__('Portuguese')}}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{route('home','es')}}">
                                    {{-- <i class="icon-user m-r-10"></i> --}}
                                    <span>{{__('Spanish')}}</span> 
                                </a>
                            </li>   
                        </ul>
                    </li> 
                    <li class="dropdown profile"> 
                        <a href="javascript:void(0);" class="dropdown-toggle pr-0"  data-toggle="dropdown" role="button" aria-expanded="true"> <img src="{{ Auth::user()->currentWallet()->currency->thumb}}" alt="" class="rounded-circle"> </a>
                         <ul class="dropdown-menu pullDown">
                        
                            @if(count(Auth::user()->wallets()))
                                @foreach(Auth::user()->wallets() as $someWallet)
                            <li> 
                               <a href="{{ url('/') }}/{{app()->getLocale()}}/wallet/{{$someWallet->id}}">      
                                <span class="name">{{ $someWallet->currency->name }}</span>
                               
                                </a>
                            </li>              
                                @endforeach
                            @endif                            
                          
                        </ul>
                    </li>
                    <li class="dropdown profile">
                        <a href="javascript:void(0);" class="dropdown-toggle pl-0" style="margin-left: -12px;" data-toggle="dropdown" role="button">
                            <img src="{{Auth::user()->avatar()}}" alt="" class="rounded-circle">
                        </a>
                        <ul class="dropdown-menu pullDown">
                            <li>
                                <div class="user-info">
                                    <h6 class="user-name m-b-0">{{Auth::user()->name}}</h6>
                                    @if(Auth::user()->verified == 1 )
                                    <p class="user-position"><span class="badge badge-success ml-0 mt-3">Verified sp#{{Auth::user()->id}}</span> </p>
                                    @else
                                    <p class="user-position"><a class="" href="{{url(app()->getLocale().'/')}}/resend/activationlink"><span class="badge badge-danger ml-0 mt-3">Verify your email</span></a></p>
                                    @endif
                                    <hr>
                                </div>
                            </li>                            
                            <li>
                                <a href="{{route('profile.info',  app()->getLocale())}}"><i class="icon-user m-r-10"></i> <span>{{__('Profile')}}</span> </a>
                            </li>
                            {{-- 
                            <li><a href="{{url(app()->getLocale().'/')}}/my_tickets"><i class="icon-lock m-r-10"></i><span>{{__('Support')}}</span></a></li>
                            <li><a href="{{url('/')}}/{{app()->getLocale()}}/tutorials"><i class="icon-lock m-r-10"></i><span>{{__('Tutorials')}}</span></a></li>
                            --}}
                            @impersonating
                            <li>
                                <a href="{{ route('impersonate.leave', app()->getLocale()) }}"><i class="icon-power m-r-10"></i><span>{{__('Leave impersonation')}}</span></a>
                            </li>
                            @endImpersonating
                            <li><a  href="{{ route('logout', app()->getLocale()) }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class="icon-power m-r-10"></i><span>    {{ __('Logout') }}</span></a><form id="logout-form" action="{{ route('logout', app()->getLocale()) }}" method="POST" style="display: none;">
                                        @csrf
                                    </form></li>
                        </ul>
                    </li>
                    {{-- <li><a href="javascript:void(0);" class="js-right-sidebar"><i class="icon-equalizer"></i></a></li> --}}
                </ul>
                @endauth
            </div>
        </div>         
    </div>
</nav>