@extends('layouts.app')
@section('styles')
    @include('wallet.styles')
@endsection
@section('content')
    <div class="row">
        @include('partials.sidebar')
         <div class="col-lg-9 col-md-12">
            <div class="row">
        <div class="col col-md-12">
            <div class="row">
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
                <div class="col-md-9 ">
                	<ul id="glbreadcrumbs-two" style="margin-bottom: 35px;">
                        <li><a href="#"><strong>1.{{__('Create Order')}}</strong> .</a></li>     
                        <li><a href="#" class="a"><strong>2.</strong>{{__('Confirm')}}.</a></li>
                        <li><a href="#" class="a"><strong>3.</strong> {{__('Finish')}}.</a></li>
                    </ul>
        			<div class="card">
        				@include('vouchers.components.vue')
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