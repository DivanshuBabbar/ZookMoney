@extends('layouts.app')
@section('content')
<div class="row">
    @include('partials.sidebar')
    <div class="col-md-9 " style="padding-right: 0" id="#sendMoney">
      @include('flash')
      <div class="card">
        <div class="header">
            <h2><strong>{{__("Gift Cards")}}</strong></h2>
        </div>
        <div class="body">
          <form action="{{url('/')}}/{{app()->getLocale()}}/order_gift_card" method="post" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="row">
                  <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                      <div class="form-group">
	                      <label for="deposit_method">Countries</label>
	                      <select class="form-control select_country" id="country" name="country_id" required="">
	                        <option>-select-</option>
	                        <?php if(!empty($countries)) {
	                                foreach($countries as $key=>$value){?>
	                                  <option <?php echo (isset($country_id) && $country_id == $value->id) ? 'selected':''; ?> data-code="<?php echo isset($value->iso3) ? $value->iso3:''; ?>" value="<?php echo isset($value->id) ? $value->id:''; ?>">
	                                    <?php echo isset($value->nicename) ? $value->nicename:''; ?>
	                                  </option>
	                        <?php } } ?>
	                      </select>
                      </div>
                  </div>
                  @if(!empty($giftcards))
	                  <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
	                      <div class="form-group">
		                      <label for="deposit_method">Gift Cards</label>
		                      <select class="form-control card_id" name="card_id" required="">
		                        <option>-select-</option>
		                        <?php if(!empty($giftcards)) {
		                                foreach($giftcards as $key=>$value){?>
		                                  <option <?php echo (isset($product_id) && $product_id == $value->productId) ? 'selected':''; ?> value="<?php echo isset($value->productId) ? $value->productId:''; ?>">
		                                    <?php echo isset($value->productName) ? $value->productName:''; ?>
		                                  </option>
		                        <?php } } ?>
		                      </select>
	                      </div>
	                  </div>
                  @endif
                  @if(!empty($price_detail))
	                  <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
	                      <div class="form-group">
		                      <label for="deposit_method">Price</label>
		                      <select class="form-control" name="price" required="">
		                        <option>-select-</option>
		                        <?php if(!empty($price_detail)) {
		                                foreach($price_detail as $key=>$value){?>
		                                  <option value="<?php echo isset($value) ? $value:''; ?>">
		                                    <?php echo isset($value) ? $value:''; ?>
		                                  </option>
		                        <?php } } ?>
		                      </select>
	                      </div>
	                  </div>
                  @endif
                  <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                      <div class="form-group">
	                      <label for="deposit_method">Fee</label>
	                      <input type="text" value="@isset($fee){{$fee}}@endisset" class="form-control" readonly="" required="" name="fee">
                      </div>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                      <div class="form-group">
	                      <label for="deposit_method">Customer Name</label>
	                      <input type="text" value="" class="form-control" required="" name="customer_name">
                      </div>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                      <div class="form-group">
	                      <label for="deposit_method">Quantity</label>
	                      <input type="number" value="1" class="form-control" readonly="" required="" name="qty">
                      </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-3">
                    <button type="submit" class="btn btn-primary">{{__('Get Gift Card')}}</button>
                  </div>
                </div>
                <div class="clearfix"></div>
          </form>
        </div>
      </div>
    </div>
</div>
<script type="text/javascript">
	document.addEventListener('DOMContentLoaded',function(){
		$('body').on('change','.select_country',function(event){
			var country_id = $(this).find(':selected').val();
			var country_code = $(this).find(':selected').attr('data-code');
			window.location.href= '{{url('/')}}/{{app()->getLocale()}}/getGiftCards'+'/'+country_code+'/'+country_id;
		});
		$('body').on('change','.card_id',function(event){
			var card_id = $(this).find(':selected').val();
			var country_id = $('body').find('.select_country').find(':selected').val();
			var country_code = $('body').find('.select_country').find(':selected').attr('data-code');
			window.location.href= '{{url('/')}}/{{app()->getLocale()}}/getGiftCards'+'/'+country_code+'/'+country_id+'/'+card_id;
		});
	},false);
</script>
@endsection
@section('footer')
  @include('partials.footer')
@endsection
