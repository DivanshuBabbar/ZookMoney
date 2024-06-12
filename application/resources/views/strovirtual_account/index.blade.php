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
            <h2><strong>{{__('Strovirtual Account')}}</strong></h2>
          </div>
          <div class="body">
            <div class="card">
              @if($user_data->stro_account_name)
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <strong><label>Account Name:  </label></strong>
                                <td>{{$user_data->stro_account_name}}</td>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="form-group">
                                <strong><label>Account Number: </label></strong>
                                <td>{{$user_data->stro_account_number}}</td>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <strong><label>Bank Name: </label></strong>
                                <td>{{$user_data->stro_bank_name}}</td>
                            </div>
                        </div>
                    </div>
                </div>
              @else
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <a href="{{route('strovirtual_account', app()->getLocale())}}" class="btn btn-info">Create Strovirtual account</a>
                        </div>
                    </div>
                </div>
              @endif
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
