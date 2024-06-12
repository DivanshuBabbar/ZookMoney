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
            <h2><strong>{{__('Stro bank transfer')}}</strong></h2>
          </div>
          <div class="body">
            <div class="card">
              <div class="card-body">
                <div class="alert_message alert" style="display: none;"></div>
                <form action="{{route('stroBankPostRequest',app()->getLocale())}}" method="POST">
                  @csrf
                  <div class="row">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Banks</label>
                        <select class="form-control bank_name select2" name="bank_name" required>
                          <option value="">--select--</option>
                          <?php 
                            if(!empty($banksList)){
                              foreach($banksList as $key=>$row){ 
                          ?>
                            <option data-bank_code="<?php echo isset($row->bankCode) ? $row->bankCode:''; ?>" value="<?php echo isset($row->bankName) ? $row->bankName:''; ?>">
                                <?php echo isset($row->bankName) ? $row->bankName:''; ?>
                            </option>
                          <?php } } ?>
                        </select>
                        <input type="hidden" class="bank_code" name="bank_code">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Account Number</label>
                        <input type="text" class="form-control" required="" name="account_number">
                      </div>
                    </div>
                  </div>
                  <div class="row" style="margin-top:50px">
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Amount</label>
                        <input type="text" class="form-control amount" required="" name="amount">
                      </div>
                    </div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label>Narration</label>
                        <input type="text" class="form-control" required="" name="narration">
                      </div>
                    </div>
                  </div>
                  <div class="row" style="margin-top:50px">
                    <div class="col-sm-3">
                        <!-- <button type="button" href="#" data-toggle="modal" data-target="#addMyModalQr" class="btn btn-info">Submit</button> -->
                        
                        <button type="submit" class="btn btn-info">Submit</button> -
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
        $('body').on('change','.bank_name',function(event){
          var bank_code = $(this).find(':selected').attr('data-bank_code');
          $('body').find('.bank_code').val(bank_code);
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
