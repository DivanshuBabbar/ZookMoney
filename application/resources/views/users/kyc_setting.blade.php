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
            <h2><strong>{{__('KYC Setting')}}</strong></h2>
          </div>
          <div class="body">
            <div class="card">
              <div class="card-body">
                <div class="alert_message alert" style="display: none;"></div>
                  <form class="user-profile-form" action="{{route('submitKyc',app()->getLocale())}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @if($user_detail->kyc_approved == 2)
                        <div class="row">
                            <div class="col-lg-12 mt-4">
                                <h5 class="title mb-2" style="color:red">
                                    Document Rejected, please upload again!
                                </h5>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-lg-12 mt-4">
                            <h5 class="title mb-2">@lang('KYC Setting')</h5>
                            <div class="row">   
                                <div class="col-sm-6">
                                    <label for="InputFirstname" class="col-form-label">@lang("First Name"):</label>
                                    <input type="text" class="form-control" name="first_name" value="@isset($user_detail->first_name){{$user_detail->first_name}}@endisset">
                                </div>
                            </div>
                            <div class="row">   
                                <div class="col-sm-6">
                                    <label for="InputFirstname" class="col-form-label">@lang("Last Name"):</label>
                                    <input type="text" class="form-control" name="last_name" value="@isset($user_detail->last_name){{$user_detail->last_name}}@endisset">
                                </div>
                            </div>
                            <div class="row">   
                                <div class="col-md-6 mb-3">
                                    <label for="InputFirstname" class="col-form-label">@lang("Government Issued ID Card (Front)"):</label>
                                    <input type="file" class="form-control" name="govt_id_card_front">
                                </div>
                                <div class="col-md-6 mb-3">
                                    @if(isset($user_detail->govt_id_card_front))
                                        <img style="height: 100px;width: 124px;" src="{{url('/assets/images/user/kyc/'.$user_detail->govt_id_card_front)}}" class="">
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="col-form-label">@lang("Government Issued ID Card (Back)")</label>
                                    <input type="file" class="form-control" name="govt_id_card_back">
                                </div>
                                <div class="col-md-6 mb-3">
                                    @if(isset($user_detail->govt_id_card_back))
                                        <img style="height: 100px;width: 124px;" src="{{url('/assets/images/user/kyc/'.$user_detail->govt_id_card_back)}}" class="">
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="col-form-label">@lang("Upload a recent selfie of yourself")</label>
                                    <input type="file" class="form-control" name="selfi">
                                </div>
                                <div class="col-md-6 mb-3">
                                    @if(isset($user_detail->selfi))
                                        <img style="height: 100px;width: 124px;" src="{{url('/assets/images/user/kyc/'.$user_detail->selfi)}}" class="">
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3 mb-3 ml-auto text-right mt-4">
                                    @if($user_detail->kyc_approved == 1)
                                        <a href="javascript:void(0)" class="w-100 cmn-btn btn btn-success">
                                            @lang('Document Approved and Locked')
                                        </a>
                                    @else
                                    <button type="submit" class="w-100 cmn-btn btn btn-primary">@lang('Update')</button>
                                    @endif
                                </div>
                            </div>
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
