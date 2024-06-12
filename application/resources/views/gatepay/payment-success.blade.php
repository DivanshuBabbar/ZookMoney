@extends('layouts.app')
@section('content')
{{--  @include('partials.nav')  --}}
<div class="row">
  @include('partials.sidebar')
  <div class="col-md-9 ">
    @include('flash')
    <div class="card" >
      <div class="header">
          <h2><strong>{{__('Auto Deposit') }}</strong></h2>
      </div>
      <div class="body">
            <div class="text-center mt-5">
                <div class="col-lg-12 text-center">
                    <!-- Thanks message -->
                    <h3>Thanks for your payment</h3>
                    <!-- Success Icon -->
                    <i class="fa fa-check-square-o fa-5x text-success"></i>
                    <!-- /Success Icon -->
                    <h1>Payment succeed</h1>
                    <!-- /Thanks message -->
                    <!-- URL for back to checkout form -->
                    <a href="{{route('gatepay', app()->getLocale())}}" title="Back">Back</a>
                    <!-- /URL for back to checkout form -->
                </div>
            </div>                          
      </div>
    </div>
  </div>
</div>
@endsection
@section('js')
@endsection