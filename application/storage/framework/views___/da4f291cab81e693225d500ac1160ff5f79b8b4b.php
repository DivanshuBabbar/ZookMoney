
<?php $__env->startSection('content'); ?>
<div class="row">
  <?php echo $__env->make('partials.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <div class="col-lg-9 col-md-12">
    <div class="row">
      <div class="col"  id="#sendMoney">
        <?php echo $__env->make('flash', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <div class="card">
          <div class="header">
            <h2><strong><?php echo e(__('Exchange Currency')); ?></strong></h2>
          </div>
          <div class="body">
            <div class="card">
              <div class="card-body">
                <div class="alert_message alert" style="display: none;"></div>
                  <form action="<?php echo e(url('/').'/'.app()->getLocale().'/save/currencyExchange'); ?>" method="POST" id="currencyExchangeForm">
                   <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>From Currency</label>
                                <select class="form-control from_currency_id" required="" name="from_currency_id">
                                    <option value="">--select--</option>
                                    <?php if(!empty($currencies)): ?>
                                    <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php if(isset($row->id)): ?><?php echo e($row->id); ?><?php endif; ?>"><?php if(isset($row->name)): ?><?php echo e($row->name); ?><?php endif; ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                         <div class="col-sm-4">
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="text" class="form-control amount" required="" name="amount">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                              <label>To Currency</label>
                                <select class="form-control " required="" name="to_currency_id" id="to_currency_id">
                                    <option value="">--select--</option>
                                    <?php if(!empty($currencies)): ?>
                                    <?php $__currentLoopData = $currencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php if(isset($row->id)): ?><?php echo e($row->id); ?><?php endif; ?>"><?php if(isset($row->name)): ?><?php echo e($row->name); ?><?php endif; ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-4">
                        <div class="exchange_rate-div mt-3" style="display:none;">
                          <label><?php echo e(__('Total Amount')); ?></label>
                          <input type="text" class="form-control total_amount" name="total_amount" readonly>
                          <input type="hidden" name="exchange_rate" class="exchange_rate">
                          <span>Exchange rate = 
                          <small style="color: red;"id="exchange_rate"></small>
                          </span>
                        </div>  
                      </div>
                    </div>
                    <div class="row" style="margin-top:50px">
                        <div class="col-sm-3">
                        <button  type="submit" id="submit" class="btn btn-info"><?php echo e(__('Submit')); ?></button>
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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('footer'); ?>
<?php echo $__env->make('partials.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<script type="text/javascript">
  document.addEventListener('DOMContentLoaded',function(){
     function exchangeRate()
     {
     	var body = $('body');
        var from_currency_id = body.find('.from_currency_id').find(':selected').val();
        var to_currency_id = body.find('#to_currency_id').find(':selected').val();
        var amount = body.find(".amount").val();
        if(amount > 0 && from_currency_id && to_currency_id)
        {
          	$.ajax({
	            url:'currencyExchange',
	            method:'GET',
	            data:{'from_currency_id':from_currency_id,'to_currency_id':to_currency_id,'amount':amount},
	            success:function(response)
	            {
	                if(response.total_amount && response.exchange_rate)
	                {
	                  body.find('.total_amount').val(response.total_amount);
	                  body.find('#exchange_rate').html(response.exchange_rate);
                    body.find('.exchange_rate').val(response.exchange_rate);
	                  body.find('.exchange_rate-div').show();
	                }
	                else
	                {
	                  body.find('.exchange_rate-div').hide();
	                }
	            }
          	}); 
        }
    }
    $('body').on("change",".from_currency_id",function(){
     	exchangeRate();
    });
    $('body').on("change","#to_currency_id",function(){
     	exchangeRate();
    });
    $('body').on("keyup",".amount",function(){
     	exchangeRate();
    });
});
</script>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>