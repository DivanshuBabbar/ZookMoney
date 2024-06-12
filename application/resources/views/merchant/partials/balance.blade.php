
<div class="" >
    <div class="body-wrapper">
        <div class="img-wrapper">
            <img src="{{ general_setting('site_logo') }}" height="141px" width="141px" alt="logo" />
        </div>
        <div class="content-wrapper">
            <p class="m-logo">
                
                 <img src="{{$merchant->logo ?? ''}}" class="rounded-circle" alt="" style="width: 100px;">
                
            </p>
      
            <b><p class="depositing-txt">Hello {{ Auth::user()->name }}</p></b>
            <div class="discount-wrapper" >
                <div>
                    <p class="pay-txt">You have to pay </span></p>
                    <p class="pay-amt">&#8377;<b>{{isset($amount) ? $amount : ''}}</b></p> 
                </div>
                <div class="col-sm-4 box" style="margin:auto; ">
                    <p class="groove item"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-wallet" viewBox="0 0 16 16"><path d="M0 3a2 2 0 0 1 2-2h13.5a.5.5 0 0 1 0 1H15v2a1 1 0 0 1 1 1v8.5a1.5 1.5 0 0 1-1.5 1.5h-12A2.5 2.5 0 0 1 0 12.5zm1 1.732V12.5A1.5 1.5 0 0 0 2.5 14h12a.5.5 0 0 0 .5-.5V5H2a1.99 1.99 0 0 1-1-.268M1 3a1 1 0 0 0 1 1h12V2H2a1 1 0 0 0-1 1"/>
                    </svg>&nbsp;&nbsp;<b>Balance</b></p><p class="groove item" id="availableBalance"><b>{{ $availableBalance }}</b></p><p><button id="depositFund" type="button" class="btn btn-primary d-flex align-items-center mr-2"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16"><path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/></svg></button></p><p><button type="button" id="refreshBalance" data-action="{{ route('ipn.refresh', ['ref' => $ref, 'language' => app()->getLocale()]) }}"class="btn btn-primary d-flex align-items-center mr-2"><svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-arrow-counterclockwise" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2z"/><path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466"/></svg></button></p>
                </div>
    
               
            </div>
            <form class="form-horizontal col-sm-4" style="margin: auto;" method="POST" action="{{ route('logandpay', ['language' => app()->getLocale(), 'ref' => $ref]) }}">
            @csrf
                <input type="hidden" name="ref" value="{{ $ref }}" >
                <input type="hidden" name="amount" value="{{ $amount }}" >
                <button type="submit" class="btn btn-warning btn-block btn-lg" style="font-weight: bold; margin-bottom: 20px; background-color: coral;     margin-top: 27px; color: aliceblue;">{{__('PAY NOW')}}</button>                           
            </form>
            
            <form action="{{ route('ipn.logout', app()->getLocale()) }}" method="POST" class="col-sm-4" style="margin: auto;">
                @csrf
                <button class="btn btn-light btn-block btn-lg" type="submit" onclick="event.preventDefault(); this.closest('form').submit();" style="font-weight: bold; border: groove; color: coral;">
                    {{ __('USE DIFFERENT ACCOUNT') }}
                </button>                                                    
            </form>
        </div>

        <p class="bottom-txt" style="margin-top:20px;">Do not close or refresh the page if you have made payment, you will be automatically
            redirected to the merchant website. If you have any issues with any transaction please email at
            <b>grievances@zookpe.com</b>
        </p>
    </div>
</div>

{{--<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center" >
            <div class="">
                <h5 class="card-title">{{ Auth::user()->name }}</h5>
            </div>
            
            <button id="depositFund" type="button" class="btn btn-primary d-flex align-items-center mr-2" 
                style="color: #fff;
                background-color: #343a40;
                border-color: #343a40;
                font-weight: bold; 
                margin-bottom: 20px" > 
                <i class="icon-plus mr-2" style="font-size: 1rem;"></i> ADD FUND
            </button>
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <div class="mr-2">
                <h6 class="mb-0"><small>Available Balance</small></h6>
                <h5 class="card-title" id="availableBalance">{{ $availableBalance }}</h5>
            </div>
            <button type="button" id="refreshBalance" data-action="{{ route('ipn.refresh', ['ref' => $ref, 'language' => app()->getLocale()]) }}"
                style="color: #fff;
                background-color: #343a40;
                border-color: #343a40;
                font-weight: bold; 
                margin-bottom: 20px" class="btn btn-primary d-flex align-items-center mr-2" >
                <i class="icon-reload mr-2" style="font-size: 1rem;"></i> REFRESH  m
            </button>
        </div>
        <form class="form-horizontal" method="POST" action="{{ route('logandpay', ['language' => app()->getLocale(), 'ref' => $ref]) }}">
            @csrf
            <input type="hidden" name="ref" value="{{ $ref }}" >
            <button type="submit" class="btn btn-warning btn-block btn-lg" style="font-weight: bold; margin-bottom: 20px">{{__('PAY NOW')}}</button>                           
        </form>
        
        <form action="{{ route('ipn.logout', app()->getLocale()) }}" method="POST">
            @csrf
            <button class="btn btn-light btn-block btn-lg" type="submit" onclick="event.preventDefault(); this.closest('form').submit();" style="font-weight: bold">
                {{ __('USE DIFFERENT ACCOUNT') }}
            </button>                                                    
        </form>
    </div>
</div>--}}