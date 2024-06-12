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
                <div class="row">
                  <div class="col-sm-6">
                    <p>
                      <strong>
                        Customer Name:
                      </strong>
                      @isset($post_data->customer_name){{$post_data->customer_name}}@endisset
                    </p>
                  </div>
                  <div class="col-sm-6">
                    <p>
                      <strong>
                        Customer district:
                      </strong>
                      @isset($post_data->customer_district){{$post_data->customer_district}}@endisset
                    </p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <p>
                      <strong>
                          Address:
                      </strong>
                      @isset($post_data->address){{$post_data->address}}@endisset
                    </p>
                  </div>
                  <div class="col-sm-6">
                    <p>
                      <strong>
                          Meter Number:
                      </strong>
                      @isset($post_data->meter_number){{$post_data->meter_number}}@endisset
                    </p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <p>
                      <strong>
                          Meter type:
                      </strong>
                      @isset($post_data->meter_type){{$post_data->meter_type}}@endisset
                    </p>
                  </div>
                  <div class="col-sm-6">
                    <p>
                      <strong>
                          Service:
                      </strong>
                      @isset($post_data->service_name){{strtoupper($post_data->service_name)}}@endisset
                    </p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <p>
                      <strong>
                          Phone:
                      </strong>
                      @isset($post_data->phone){{$post_data->phone}}@endisset
                    </p>
                  </div>
                  <div class="col-sm-6">
                    <p>
                      <strong>
                          Amount:
                      </strong>
                      @isset($post_data->amount){{$post_data->amount}}@endisset
                    </p>
                  </div>
                </div>
                <div class="row" style="margin-top:50px">
                  <div class="col-sm-12">
                    <p class="alert alert-success show_token_message" style="display:none;"></p>
                    <a href="{{route('stroElectricity',app()->getLocale())}}" style="display:none;" class="btn btn-info go_back">
                        Go Back
                    </a>
                    <button class="btn btn-info submit_form">Submit</button>
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
@endsection
@section('js')
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded',function(){
        $('body').on('click','.submit_form',function(event){
            event.preventDefault();
            var element = $(this);
            var action="{{route('stroPostElectricity',app()->getLocale())}}";
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
                      $('body').find('.show_token_message').show();
                      $('body').find('.go_back').show();
                      $('body').find('.show_token_message').html(result.message);
                      element.remove();
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
