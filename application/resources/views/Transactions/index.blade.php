@extends('layouts.app')

@section('content')
@include('partials.nav')
    <div class="row">
        @include('partials.sidebar')
		    <div class="col-lg-9 col-md-12">
			    <div class="row">
					<div class="col " style="padding-right: 0">
			        
				        @include('home.partials.transactions_to_confirm')
				        
				        @include('home.partials.transactions')

			    	</div>
			    </div>
			</div>

    </div>
@endsection

@section('footer')
	@include('partials.footer')
@endsection
