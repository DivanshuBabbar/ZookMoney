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
            <h2><strong>{{__('Strowallet Data')}}</strong></h2>
          </div>
          <div class="body">
            <div class="card">
              <div class="card-body">
                <div class="alert_message alert" style="display: none;"></div>
                <form action="{{route('buyStroDataRequest',app()->getLocale())}}" method="POST" id="submit_form">
                  @csrf
                  <input type="hidden" value="<?php echo isset($service_id) ? $service_id:''; ?>" class="service_id" name="service_id">
                  <input type="hidden" value="<?php echo isset($service_name) ? $service_name:''; ?>"class="service_name" name="service_name">
                  <input type="hidden" value="" class="amount" name="amount">
                  <input type="hidden" value="" class="variation_code" name="variation_code">
                  <div class="row">
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label>Choose Service</label>
                          <select class="form-control get_data_bundles" required="" name="service_id">
                              <option value="">--select--</option>
                              <option data-url="{{route('stro_get_data_bundles',[app()->getLocale(),'mtn-data'])}}" <?php echo (isset($service_id) && $service_id == 'mtn-data') ? 'selected':'mtn-data'; ?> value="mtn-data">
                                  MTN Data
                              </option>
                              <option data-url="{{route('stro_get_data_bundles',[app()->getLocale(),'airtel-data'])}}" <?php echo (isset($service_id) && $service_id == 'airtel-data') ? 'selected':'mtn-data'; ?> value="airtel-data">
                                  Airtel Data
                              </option>
                              <option data-url="{{route('stro_get_data_bundles',[app()->getLocale(),'glo-data'])}}" <?php echo (isset($service_id) && $service_id == 'glo-data') ? 'selected':'mtn-data'; ?> value="glo-data">
                                  GLO Data
                              </option>
                              <option data-url="{{route('stro_get_data_bundles',[app()->getLocale(),'etisalat-data'])}}" <?php echo (isset($service_id) && $service_id == 'etisalat-data') ? 'selected':'mtn-data'; ?> value="etisalat-data">
                                  9mobile Data
                              </option>
                              <option data-url="{{route('stro_get_data_bundles',[app()->getLocale(),'spectranet'])}}" <?php echo (isset($service_id) && $service_id == 'spectranet') ? 'selected':'spectranet'; ?> value="spectranet">
                                  Spectranet
                              </option>
                               <option data-url="{{route('stro_get_data_bundles',[app()->getLocale(),'smile-direct'])}}" <?php echo (isset($service_id) && $service_id == 'smile-direct') ? 'selected':'smile-direct'; ?> value="smile-direct">
                                  Smile Network Payment
                              </option>
                          </select>
                      </div>
                    </div>
                  @if(isset($service_id))
                      <div class="col-sm-4">
                          <div class="form-group">
                              <label>Choose Service Data</label>
                              <select class="form-control select_variation_code" required="" name="varation_name">
                                  <option value="">--select--</option>
                                  <?php if(!empty($varations)){
                                          foreach($varations as $key=>$value){ ?>
                                      <option data-variation_amount="<?php echo isset($value->variation_amount) ? $value->variation_amount:''; ?>" data-variation_code="<?php echo isset($value->variation_code) ? $value->variation_code:''; ?>" value="<?php echo isset($value->name) ? $value->name:''; ?>">
                                          <?php echo isset($value->name) ? $value->name:''; ?>
                                      </option>
                                  <?php } } ?>
                              </select>
                          </div>
                      </div>
                      <div class="col-sm-4">
                          <div class="form-group">
                              <label>Phone Number</label>
                              <input type="text" class="form-control phone" required="" name="phone">
                          </div>
                      </div>
                  @endif
                  </div>
                  <div class="row" style="margin-top:50px">
                      <div class="col-sm-3">
                          <!-- <button type="button" href="#" data-toggle="modal" data-target="#addMyModalQr" class="btn btn-info">Submit</button> -->
                          
                            <button type="submit" id="submit" class="btn btn-info">Submit</button> -
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
<div class="modal fade" id="addMyModalQr">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Confirm</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <form action="{{route('buyStroDataRequest',app()->getLocale())}}" method="POST">
          <div class="modal-body">
              <div class="form-group">
                  <center>
                      <h4>Dear {{Auth::user()->username}}</h4>
                      <p>You're about to Purchase Data</p>
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
        $('body').on('change','.get_data_bundles',function(){
          var selected_service = $(this).find(':selected').val();
          var url = $(this).find(':selected').attr('data-url');
          window.location.href=url;
        });
        $('body').on('click','#submit',function(event){
            event.preventDefault();
            var action="{{route('buyStroDataRequest',app()->getLocale())}}";
            $('body').find('#submit').html('Please Wait...');
            $('body').find("#submit").attr("disabled", true);
            $('body').find('.alert_message').hide();
            $('body').find('.alert_message').text('');
            var form = $('body').find('#submit_form');
            var formData = new FormData(form[0]);
            $.ajax({
                url: action,
                type: "POST",
                data:formData,
                processData: false,
                contentType: false,
                cache:false,
                success: function( response ) {
                  var result = jQuery.parseJSON(response);
                  if(result.success == 1)
                  {
                    $('body').find('.alert_message').show();
                    $('body').find('.alert_message').addClass('alert-success');
                    $('body').find('.alert_message').text(result.message);
                    window.location.reload();
                  }
                  else
                  {
                    $('body').find('.alert_message').show();
                    $('body').find('.alert_message').addClass('alert-danger');
                    $('body').find('.alert_message').text(result.message);
                    $('body').find('#submit').html('Submit');
                    $('body').find("#submit").attr("disabled", false);
                  }
                }
            });
        });
        $('body').on('change','.select_variation_code',function(){
            var variation_amount = $(this).find(':selected').attr('data-variation_amount');
            var variation_code = $(this).find(':selected').attr('data-variation_code');
            var service_name = $(this).find(':selected').text();
            service_name = (service_name) ? service_name.trim():'';
            $('body').find('.amount').val(variation_amount);
            $('body').find('.variation_code').val(variation_code);
            $('body').find('.service_name').val(service_name);
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
