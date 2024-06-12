 <div class=" col-sm-12 col-md-12 col-lg-3">
    <div class="card ">
        <!-- overflowhidden -->
        @if(count(Auth::user()->wallets()))
       
                           <div class="header" style="padding-bottom: 0">
            <h2><strong>Payin Balance</strong> </h2>
            <ul class="header-dropdown">
                {{--
                            <li class="dropdown"> 
                                <a href="#smallModal" data-toggle="modal" data-target="#walletModal"> <i class="zmdi zmdi-plus"></i> </a>
                               
                            </li>
                --}}       
                <li class="dropdown profile"> 
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="true"> <i class="zmdi zmdi-plus"></i> </a>
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
            </ul>
        </div>

       
        @endif
        <div class="body mb-0">

{{--
@if(count(\App\Models\Currency::where('id', '!=', Auth::user()->currentCurrency()->id)->get()))

     <a href="{{url('/')}}/{{app()->getLocale()}}/exchange/first/0/second/0">{{ __('Convert Currency')}}</a>

@endif
--}}
            <ul class="follow_us list-unstyled mb-0">
                <li class="online">
                        <div class="media mb-0">
                            <img class="media-object " src="{{ Auth::user()->currentWallet()->currency->thumb}}" alt="">
                            <div class="media-body">
                                <span class="name"> {{Auth::user()->currentWallet()->currency->name}} </span>
                                <span class="message" style="font-size: 18px;font-weight: 600;">{{ \App\Helpers\Money::instance()->value(Auth::user()->balance(), Auth::user()->currentCurrency()->symbol, Auth::user()->currentCurrency()->is_crypto) }}</span>
                                <span class="badge badge-outline status"></span>
                            </div>
                        </div>                         
                </li>                        
            </ul>
       
            <div class="content">
                <!-- <a href="{{url('/')}}/{{app()->getLocale()}}/deposit/{{Auth::user()->currentWallet()->id}}"><span class="badge badge-success">{{__('DEPOSIT')}}</span></a> <a href="{{url('/')}}/{{app()->getLocale()}}/payout/{{Auth::user()->currentWallet()->id}}" ><span class="badge badge-success">{{__('WITHDRAW')}}</span></a> -->

                @if(Auth::user()->account_status == 1)
                    <a href="{{ route('add.withdrawal.method', app()->getLocale()) }}" class="badge badge-success">{{__('WITHDRAW')}}</a>
                    @if(Auth::user()->payout_status == 1)
                    <a href="#" class="badge badge-success restrict_modal">{{__('TRF TO PAYOUT NODAL')}}</a>
                    @endif
                 @endif
             </div>
        </div>

        {{-- <div id="sparkline16" class="text-center"><canvas width="403" height="390" style="display: inline-block; width: 403.328px; height: 390px; vertical-align: top;"></canvas></div> --}}
    </div>
     @if(Auth::user()->payout_status == 1)
    <div class="card">
        <div class="header">
            <h2><strong>Payout Balance</strong> </h2>
        </div>
       <ul class="follow_us list-unstyled mb-0">
            <li class="online">
                <div class="media mb-0">
                    <img class="media-object" style=" margin-left: 18px; margin-bottom: 12px;" src="{{ Auth::user()->currentWallet()->currency->thumb}}" alt="">
                    <div class="media-body">
                        <span class="name"> {{Auth::user()->currentWallet()->currency->name}} </span>
                        <span class="message" style="font-size: 18px;font-weight: 600;">{{ \App\Helpers\Money::instance()->value(Auth::user()->payout_balance(), Auth::user()->currentCurrency()->symbol, Auth::user()->currentCurrency()->is_crypto) }}</span>
                        <span class="badge badge-outline status"></span>
                    </div>
                </div>                         
            </li>                        
        </ul>
        @if(Auth::user()->account_status == 1)
        <div style=" margin-left: 18px; margin-bottom: 12px;">
             @if(Auth::user()->payin_status == 1)
            <a class="badge badge-success" href="#" id="depositDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{__('DEPOSIT')}}</a>
            <div class="dropdown-menu" aria-labelledby="depositDropdown">
            <a class="dropdown-item" href="{{ route('add.mydeposits', ['locale' => app()->getLocale(), 'type' => 'payout']) }}">{{__('Payout Balance')}}</a>
            <a class="dropdown-item" onclick="return false;" style="color: darkgray;" href="{{ route('add.mydeposits', ['locale' => app()->getLocale(), 'type' => 'main']) }}">{{__('Main Balance')}}</a>
            </div>
            @endif
        </div>
        @endif
    </div>
    @endif
    @if(Route::is('home')   )

        @if(!empty($myEscrows))
        
            @foreach($myEscrows as $escrow)

                <div class="card">
                    <div class="header">
                        <h2><strong>On Hold</strong> #{{$escrow->id}}</h2>
                        <ul class="header-dropdown">
                            <li class="remove">
                                <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <h3 class="mb-0 pb-0">
                       -  {{ \App\Helpers\Money::instance()->value( $escrow->gross, $escrow->currency_symbol )}}       
                        </h3>
                        Escrow money to  <a href="{{url('/')}}/{{app()->getLocale()}}/escrow/{{$escrow->id}}"><span class="text-primary">{{$escrow->toUser->name}}</span></a> <br> 
                        <form action="{{url('/')}}/{{app()->getLocale()}}/escrow/release" method="post">
                            {{csrf_field()}}
                            <input type="hidden" name="eid" value="{{$escrow->id}}">
                            <input type="submit" class="btn btn-sm btn-round btn-primary btn-simple" value="{{('Release')}}">
                            
                        </form>
                    </div>
                </div>

            @endforeach
        
        @endif 
    
        @if(!empty($toEscrows))
        
            @foreach($toEscrows as $escrow)

                <div class="card">
                    <div class="header">
                        <h2><strong>On Hold</strong> #{{$escrow->id}}</h2>
                        <ul class="header-dropdown">
                            <li class="remove">
                                <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <h3 class="mb-0 pb-0">
                        +  {{ \App\Helpers\Money::instance()->value( $escrow->gross, $escrow->currency_symbol )}}       
                        </h3>
                        Escrow money from <a href="{{url('/')}}/{{app()->getLocale()}}/escrow/{{$escrow->id}}"><span class="text-primary">{{$escrow->User->name}}</span></a> 
                        <form action="{{url('/')}}/{{app()->getLocale()}}/escrow/refund" method="post">
                            {{csrf_field()}}
                            <input type="hidden" name="eid" value="{{$escrow->id}}">
                            <input type="submit" class="btn btn-sm btn-round btn-danger btn-simple" value="{{_('refund')}}">
                        </form>
                    </div>
                </div>

            @endforeach
        
        @endif 

    @endif

 @if(Auth::user()->role_id == 2 or Auth::user()->is_ticket_admin )
    <div class="card hidden-sm">
        <div class="header">
            <h2><strong>Extra</strong> area</h2>
            <ul class="header-dropdown">
                <li class="remove">
                    <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                </li>
            </ul>
        </div>
        <div class="body">
                   <h5 class="card-title">Hello {{Auth::user()->name}}</h5>
                <p class="card-text">In this section you have links that are visible to you.</p>
                 <div class="list-group mb-2">
                    <a href="{{ url('/') }}/{{app()->getLocale()}}/my_tickets" class="list-group-item list-group-item-action {{ (Route::is('makeVouchers') ? 'inactive' : '') }}">{{__('Support Tickets')}}</a>
                    
                        <a href="{{ url('/') }}/{{app()->getLocale()}}/new_ticket" class="list-group-item list-group-item-action {{ (Route::is('support') ? 'inactive' : '') }}">{{__('New Ticket')}}</a>
                    
                   
                </div>
                                 
            
        </div>
    </div> 
    @endif
    
    @if(Auth::user()->role_id == 1 or Auth::user()->is_ticket_admin )
    <div class="card hidden-sm">
        <div class="header">
            <h2><strong>Admin</strong> area</h2>
            <ul class="header-dropdown">
                <li class="remove">
                    <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                </li>
            </ul>
        </div>
        <div class="body">
                   <h5 class="card-title">Howdy Mr. admin {{Auth::user()->name}}</h5>
                <p class="card-text">In this section you have links that are only visible to admins.</p>
                 <div class="list-group mb-2">
                    <a href="{{ route('makeVouchers', app()->getLocale()) }}" class="list-group-item list-group-item-action {{ (Route::is('makeVouchers') ? 'active' : '') }}">{{__('Generate Vouchers')}}</a>
                   @if(Auth::user()->role_id == 1)
                        <a href="{{ url('/') }}/{{app()->getLocale()}}/ticketadmin/tickets" class="list-group-item list-group-item-action {{ (Route::is('support') ? 'active' : '') }}">{{__('Manage Tickets')}}</a>
                    @endif
                    @if(Auth::user()->role_id == 1)
                        <a href="{{ url('/') }}/{{app()->getLocale()}}/update_rates" class="list-group-item list-group-item-action ">{{__('Update Exchange Rates')}}</a>
                    @endif
                </div>
                <a href="{{url('/')}}/administrator/new_dashboard" class="btn btn-primary btn-round">Go to admin dashboard</a>                  
            
        </div>
    </div> 
    @endif
    @if(Auth::user()->role_id == 3)
    <div class="card hidden-sm">
        <div class="header">
            <h2><strong>Agent</strong> area</h2>
            <ul class="header-dropdown">
                <li class="remove">
                    <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                </li>
            </ul>
        </div>
        <div class="body ">
            <h5 class="card-title">Howdy Mr. Agent {{Auth::user()->name}}</h5>
            <p class="card-text">In this section you have links that are only visible to Agents</p>
                <div class="list-group mb-2">
                <a href="{{ route('makeVouchers', app()->getLocale()) }}" class="list-group-item list-group-item-action {{ (Route::is('makeVouchers') ? 'active' : '') }}">{{__('Recharge Vouchers')}}</a>
            </div>
        </div>
    </div> 
    @endif
    @if(!Route::is('exchange.form'))
     
    <div class="list-group">
   
    </div>
    @endif
</div>

