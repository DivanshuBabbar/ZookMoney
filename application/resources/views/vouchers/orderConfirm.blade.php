
@extends('layouts.app')
@section('styles')
    @include('wallet.styles')
@endsection
@section('content')
    <div class="row">
        @include('partials.sidebar')

        <div class="col-md-3">
        	<div class="card bg-light" style=" box-shadow: none; background: transparent !important;">
        		<div class="header">
        			<h2><strong>Buy Vouchers</strong></h2>
        		</div>
        		<div class="body">
				        	<p>You can buy Vouchers to add credit to your wallets automatically using popular payment methods. Fill the details correctly & the amount you want to deposit.</p>
				        
        		</div>
        	</div>
        </div>
        <div class="col-md-6 ">
        	<ul id="glbreadcrumbs-two" style="margin-bottom: 35px;">
                <li><a href="#" class="a"><strong>1.{{__('Create Order')}}</strong> .</a></li>     
                <li><a href="#" ><strong>2.</strong>{{__('Confirm')}}.</a></li>
                <li><a href="#" class="a"><strong>3.</strong> {{__('Finish')}}.</a></li>
            </ul>
			<div class="card">
				<div class="header">
					<h2>Deposit Money via <strong> {{$method['name']}}</strong></h2>
				</div>
				<div class="body">
					<div class="">
                        <div>
							
							<div class="mt-4">
								<p class="sub-title">Details</p>
							</div>

							<div>
								<div class="d-flex flex-wrap justify-content-between mt-2">
									<div>
										<p>Deposit Amount</p>
									</div>

									<div class="pl-2">
										<p>{{$amount}} {{$currency}}</p>
									</div>
								</div>

								<div class="d-flex flex-wrap justify-content-between mt-2">
									<div>
										<p>Fee : 
											<span class="text-primary"> 
												<small> ( {{$method['fees']['percentage']}}% + {{$method['fees']['fixed']}} {{$currency}})</small>
											</span>
										</p>
									</div>

									<div class="pl-2">
										<p>{{$fee}} {{$currency}}</p>
									</div>
								</div>
								<hr class="mb-2">

								<div class="d-flex flex-wrap justify-content-between">
									<div>
										<p class="font-weight-600">Total</p>
									</div>

									<div class="pl-2">
										<p class="font-weight-600">{{$total}} {{$currency}}</p>
									</div>
								</div>
							</div>


							<div class="row m-0 mt-4 justify-content-between">
								
								<div class="col">
									<form action="{{route('processVoucherOrder', app()->getLocale())}}" method="POST" style="display: block;" method="POST" accept-charset="UTF-8" id="deposit_form" novalidate="novalidate" enctype="multipart/form-data">
										{{csrf_field()}}
										<input value="{{$amount}}" name="amount"  type="hidden">
										<input  type="hidden" name="currency_code"   value="{{$currency}}" >
										<input  type="hidden" name="method"   value="{{$method['id']}}" >
										<div class="float-right">
											<input type="submit" class="btn btn-primary bg-blue px-4 py-2 mt-2 " id="deposit-money-confirm" value="{{__('Confirm')}}"/>
										</div>
									</form>
								</div>
							</div>
						</div>
                    </div>
				</div>
			</div>		
        </div>
        
    </div>
    
@endsection

@section('js')
	<script>
    	$( "#voucher_currency" )
		  .change(function () {
		    $( "#voucher_currency option:selected" ).each(function() {
		      window.location.replace("{{url('/')}}/{{app()->getLocale()}}/wallet/"+$(this).val());
		  });
		})
    </script>
@endsection

@section('footer')
  @include('partials.footer')
@endsection