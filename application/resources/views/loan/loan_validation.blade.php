@extends('layouts.app')
@section('content')
<div class="row">
  @include('partials.sidebar')
  <div class="col-lg-9 col-md-12">
    <div class="row">
      <div class="col"  id="#sendMoney">
        @include('flash')
        <div class="card">
          <div class="header">
            <h2><strong>{{__('Loan')}}</strong></h2>
          </div>
          <div class="body">
            <div class="row">
              <div class="col-sm-12">
                <div class="alert alert-danger">
                    <h6>You've already applied for loan, please repay to be able to apply for another</h6>
                </div>
              </div>
            </div>
            <div class="row" style="text-align:right;">
              <div class="col-sm-12">
                  <a href="{{route('loan.list',app()->getLocale())}}" class="btn btn-primary btn-xs">Back</a>
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
$( "#currency" )
  .change(function () {
    $( "#currency option:selected").each(function() {
      window.location.replace("{{url('/')}}/{{app()->getLocale()}}/wallet/"+$(this).val());
  });
})
</script>
@endsection
@section('footer')
  @include('partials.footer')
@endsection
