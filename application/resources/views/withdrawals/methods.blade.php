@extends('layouts.app')

@section('content')
{{--  @include('partials.nav')  --}}
    <div class="row">
        @include('partials.sidebar')
      <div class="col-md-9 ">
        @include('partials.flash')
        <div class="card bg-light">
          <div class="header">
            <h2>{{__('Select an adress to send the money from withdraw')}} </h2>
          </div>
          <div class="body">
            <div class="row mb-4">
              <div class="col">
                <a href="{{url('/')}}/{{app()->getLocale()}}/transfer/{{$wallet->currency_id}}/methods" class="btn btn-primary btn-round bg-blue   ">+ Add account to receive withdraw</a>
              </div>
            </div>  
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-lg-12">
                  <div class="row">
                     @foreach($methods as $method)
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <a href="{{url('/')}}/{{app()->getLocale()}}/payout/request/{{$method->pivot->id}}">
                                <div class="card product_item ">
                                    <div class="body text-center" style="display: flex;align-items: center;justify-content: center">
                                        <div class="cp_img">
                                            <img src="{{$method->thumbnail}}" alt="Product" class="img-fluid" style="opacity: 0.2">
                                       
                                        </div>
                                        <h6 style="margin-top: 10px; position: absolute;">{{$method->name}}<br><span class="text-success"> {{$method->pivot->adress}}</span></h6>
                                     
                                    </div>
                                </div>
                            </a>                
                        </div>
                     @endforeach

                   
                  </div>
              </div>
            </div>
          </div>
        </div>
        
      </div>
    </div>
@endsection

@section('js')
@include('withdrawals.vue')
<script>
$( "#withdrawal_method" )
  .change(function () {
    $( "#withdrawal_method option:selected" ).each(function() {
      window.location.replace("{{url('/')}}/{{app()->getLocale()}}/withdrawal/request/"+$(this).val());
  });
});

$( "#withdrawal_currency" )
  .change(function () {
    $( "#withdrawal_currency option:selected" ).each(function() {
      window.location.replace("{{url('/')}}/{{app()->getLocale()}}/wallet/"+$(this).val());
  });
})
</script>

@endsection
@section('footer')
  @include('partials.footer')
@endsection