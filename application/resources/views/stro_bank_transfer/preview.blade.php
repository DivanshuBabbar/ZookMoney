@extends('layouts.app')
@section('content')
<div class="row">
  @include('partials.sidebar')
  <div class="col-lg-9 col-md-12">
    <div class="row">
      <div class="col" id="#sendMoney">
        @include('flash')
        <div class="card">
          <div class="header">
            <h2><strong>{{__('Detail')}}</strong></h2>
          </div>
          <div class="body">
            <div class="card">
              <div class="card-body">
                <div class="alert_message alert" style="display: none;"></div>
                  <div class="row">
                      <div class="col-sm-6">
                          <p>
                              <strong>
                                  Account Name:
                              </strong>
                              @isset($post_data->account_name){{$post_data->account_name}}@endisset
                          </p>
                      </div>
                      <div class="col-sm-6">
                          <p>
                              <strong>
                                  Account Number:
                              </strong>
                              @isset($post_data->account_number){{$post_data->account_number}}@endisset
                          </p>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-sm-6">
                          <p>
                              <strong>
                                  Bank Name:
                              </strong>
                              @isset($post_data->bank_name){{$post_data->bank_name}}@endisset
                          </p>
                      </div>
                      <div class="col-sm-6">
                          <p>
                              <strong>
                                  Amount:
                              </strong>
                             ₦@isset($post_data->amount){{$post_data->amount}}@endisset
                          </p>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-sm-6">
                          <p>
                              <strong>
                                  Fee:
                              </strong>
                            ₦@isset($post_data->fee){{$post_data->fee}}@endisset
                          </p>
                      </div>
                      <div class="col-sm-6">
                          <p>
                              <strong>
                                  Narration:
                              </strong>
                              @isset($post_data->narration){{$post_data->narration}}@endisset
                          </p>
                      </div>
                  </div>
                  <div class="row" style="margin-top:50px">
                      <div class="col-sm-12">
                          <button type="button" class="btn btn-info submit_form">Submit</button>
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
            var href = "{{route('stroBankTransfer',app()->getLocale())}}";
            $(this).html('Please Wait...');
            $(this).attr("disabled", true);
            window.location.href = href;
            // $.ajax({
            //     url: action,
            //     type: "GET",
            //     success: function( response ) {
            //         var result = jQuery.parseJSON(response);
            //         if(result.success == 1)
            //         {
            //             notify('success', result.message);
            //             $('body').find('.show_token_message').show();
            //             $('body').find('.go_back').show();
            //             $('body').find('.show_token_message').html(result.message);
            //             element.remove();
            //         }
            //         else
            //         {
            //             notify('error', result.message);
            //             element.html('Submit');
            //             element. attr("disabled", false);
            //         }
            //     }
            // });
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
