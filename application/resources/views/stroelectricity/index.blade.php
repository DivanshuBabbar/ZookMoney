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
            <h2><strong>{{__('Stro Electricity Bills')}}</strong></h2>
          </div>
          <div class="body">
            <div class="card">
              <div class="card-body">
                <div class="alert_message alert" style="display: none;"></div>
                <form action="{{route('stro_electricity_merchant_verify',app()->getLocale())}}" method="POST">
                  @csrf
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Electricity Name</label>
                        <select class="form-control" id="electricity_name" name="electricity_name" required="">
                          <option value="">--select--</option>
                          <option value="ikeja-electric">Ikeja Electric</option>
                          <option value="eko-electric">Eko Electric</option>
                          <option value="kano-electric">Kano Electric</option>
                          <option value="portharcourt-electric">Port Harcourt</option>
                          <option value="jos-electric">Jos Electric</option>
                          <option value="ibadan-electric">Ibadan Electricity</option>
                          <option value="kaduna-electric">Kaduna Electric</option>
                          <option value="abuja-electric">Abuja Electric</option>
                          <!-- <option value="ibadan-electric">Ibadan Electric</option> -->
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Amount</label>
                        <input type="text" class="form-control amount" required="" name="amount">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Meter Number</label>
                        <input type="text" class="form-control meter_number" required="" name="meter_number">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Phone Number</label>
                        <input type="text" class="form-control phone" required="" name="phone">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Meter Type</label>
                        <select class="form-control" id="meter_type" name="meter_type" required="">
                          <option value="">--select--</option>
                          <option value="prepaid">Prepaid</option>
                          <option value="postpaid">Postpaid</option>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row" style="margin-top:50px">
                    <div class="col-sm-3">
                      <button type="submit" class="btn btn-info">Submit</button>
                    </div>
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
$( "#currency" )
  .change(function () {
    $( "#currency option:selected" ).each(function() {
      window.location.replace("{{url('/')}}/{{app()->getLocale()}}/wallet/"+$(this).val());
  });
})
</script>
@endsection
@section('footer')
  @include('partials.footer')
@endsection
