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
            <h2><strong>{{__('Stro Cable TV')}}</strong></h2>
          </div>
          <div class="body">
            <div class="card">
              <div class="card-body">
                <div class="alert_message alert" style="display: none;"></div>
                <div class="row">
                    <div class="col-sm-6">
                        <p>
                            <strong>
                                Customer Name:
                            </strong>
                            @isset($post_data->customer_name){{$post_data->customer_name}}@endisset
                        </p>
                    </div>
                    <!-- <div class="col-sm-6">
                        <p>
                            <strong>
                                Customer Number:
                            </strong>
                            @isset($post_data->customer_number){{$post_data->customer_number}}@endisset
                        </p>
                    </div> -->
                    <div class="col-sm-6">
                        <p>
                            <strong>
                                Service:
                            </strong>
                            @isset($post_data->service_id){{strtoupper($post_data->service_id)}}@endisset
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <p>
                            <strong>
                                Smart Card Number:
                            </strong>
                            @isset($post_data->customer_id){{$post_data->customer_id}}@endisset
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <p>
                            <strong>
                                Phone:
                            </strong>
                            @isset($post_data->phone){{$post_data->phone}}@endisset
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <p>
                            <strong>
                                Amount:
                            </strong>
                            @isset($post_data->amount){{$post_data->amount}}@endisset
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <p>
                            <strong>
                                Verification Code:
                            </strong>
                            @isset($post_data->variation_code){{$post_data->variation_code}}@endisset
                        </p>
                    </div>
                </div>
                <div class="row" style="margin-top:50px">
                    <div class="col-sm-3">
                        <button class="btn btn-info submit_form">Submit</button>
                        <!-- <a href="{{url('/user/postCableRequest')}}" disabled class="btn btn-info submit_form">Submit</a> -->
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="addMyModalQr">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Confirm</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form action="{{route('stroairtimeRequest',app()->getLocale())}}" method="POST">
          <div class="modal-body">
              <div class="form-group">
                  <center>
                      <h4>Dear {{Auth::user()->username}}</h4>
                      <p>You're about to Purchase Airtime</p>
                      <p>Do you want to proceed?</p>
                  </center>
              </div>
              <div class="modal-footer">
                  <button type="button" id="submit" class="btn btn-info">@lang('Yes')</button>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('No')</button>
              </div>
          </div>
      </form>
    </div>
  </div>
</div>
@endsection
@section('js')
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded',function(){
        $('body').on('click','.submit_form',function(event){
            event.preventDefault();
            var element = $(this);
            var action="{{route('postCableRequest',app()->getLocale())}}";
            $(this).html('Please Wait...');
            $(this).attr("disabled", true);
            $('body').find('.alert_message').hide();
            $('body').find('.alert_message').text('');
            $.ajax({
                url: action,
                type: "GET",
                success: function( response ) {
                    var result = jQuery.parseJSON(response);
                    if(result.success == 1)
                    {
                        $('body').find('.alert_message').show();
                        $('body').find('.alert_message').addClass('alert-success');
                        $('body').find('.alert_message').text(result.message);
                        window.location.href = "{{route('strotvSubscription',app()->getLocale())}}";
                    }
                    else
                    {
                        $('body').find('.alert_message').show();
                        $('body').find('.alert_message').addClass('alert-danger');
                        $('body').find('.alert_message').text(result.message);
                        element.html('Submit');
                        element. attr("disabled", false);
                    }
                }
            });
        });
    },false);
</script>
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
