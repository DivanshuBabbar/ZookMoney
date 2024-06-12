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
            <h2><strong>{{__('Stro Educational Payment')}}</strong></h2>
          </div>
          <div class="body">
            <div class="card">
              <div class="card-body">
                <div class="alert_message alert" style="display: none;"></div>
                <form action="{{route('stroPostEducational',app()->getLocale())}}" method="POST" id="submit_form">
                  @csrf
                  <div class="row">
                      <div class="col-sm-6">
                          <div class="form-group">
                              <label>Amount</label>
                              <input type="text" readonly="" class="form-control" name="amount" value="@isset($amount){{$amount}}@endisset" required="">
                          </div>
                      </div>
                      <div class="col-sm-6">
                          <div class="form-group">
                              <label>Phone</label>
                              <input type="text" class="form-control" name="phone" required="">
                          </div>
                      </div>
                  </div>
                  <div class="row" style="margin-top:50px">
                      <div class="col-sm-12">
                          <p class="alert alert-success show_token_message" style="display:none;"></p>
                          <a href="{{route('stroEducation',app()->getLocale())}}" style="display:none;" class="btn btn-info go_back">
                              Go Back
                          </a>
                          <button type="submit" id="submit" class="btn btn-info">Submit</button>
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
<script type="text/javascript">
document.addEventListener('DOMContentLoaded',function(){
    $('body').on('submit','#submit_form',function(event){
        event.preventDefault();
        var action=$(this).attr('action');
        $('body').find('#submit').html('Please Wait...');
        $('body').find("#submit").attr("disabled", true);
        $('body').find('.alert_message').hide();
        $('body').find('.alert_message').text('');
        $.ajax({
            url: action,
            type: "POST",
            data: $('#submit_form').serialize(),
            success: function( response ) {
              var result = jQuery.parseJSON(response);
              if(result.success == 1)
              {
                $('body').find('.show_token_message').show();
                $('body').find('.go_back').show();
                $('body').find('.show_token_message').html(result.message);
                $('body').find('#submit').remove();
              }
              else
              {
                $('body').find('.alert_message').show();
                $('body').find('.alert_message').addClass('alert-danger');
                $('body').find('.alert_message').text(result.message);
                $('body').find('#submit').html('Submit');
                $('body').find("#submit"). attr("disabled", false);
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
