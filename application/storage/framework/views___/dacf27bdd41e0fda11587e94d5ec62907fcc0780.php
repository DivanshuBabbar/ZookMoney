
<?php $__env->startSection('content'); ?>
<div class="row">
  <?php echo $__env->make('partials.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <div class="col-lg-9 col-md-12">
    <div class="row">
      <div class="col"  id="#sendMoney">
        <?php echo $__env->make('flash', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <div class="card">
          <div class="header">
            <h2><strong><?php echo e(__('Strowallet Cable')); ?></strong></h2>
          </div>
          <div class="body">
            <div class="card">
              <div class="card-body">
                <div class="alert_message alert" style="display: none;"></div>
                  <form action="<?php echo e(route('cable_merchant_verify',app()->getLocale())); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" value="<?php echo isset($service_id) ? $service_id:''; ?>" class="service_id" name="service_id">
                    <input type="hidden" value="<?php echo isset($service_name) ? $service_name:''; ?>"class="service_name" name="service_name">
                    <input type="hidden" value="" class="amount" name="amount">
                    <input type="hidden" value="" class="variation_code" name="variation_code">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Choose Service</label>
                                <select name="service_id" class="form-control service_id" required="">
                                    <option value="">--select--</option>
                                    <option data-url="<?php echo e(route('strogetCablePlan',[app()->getLocale(),'dstv'])); ?>" <?php echo (isset($service_id) && $service_id == 'dstv') ? 'selected':''; ?> value="dstv">
                                      DSTV
                                    </option>
                                    <option data-url="<?php echo e(route('strogetCablePlan',[app()->getLocale(),'gotv'])); ?>" <?php echo (isset($service_id) && $service_id == 'gotv') ? 'selected':''; ?> value="gotv">
                                      GOTV
                                    </option>
                                    <option data-url="<?php echo e(route('strogetCablePlan',[app()->getLocale(),'startimes'])); ?>" <?php echo (isset($service_id) && $service_id == 'startimes') ? 'selected':''; ?> value="startimes">
                                      STARTIMES
                                    </option>
                                    <option data-url="<?php echo e(route('strogetCablePlan',[app()->getLocale(),'showmax'])); ?>" <?php echo (isset($service_id) && $service_id == 'showmax') ? 'selected':''; ?> value="showmax">
                                      ShowMax
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Choose Variation</label>
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
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Smart Card Number</label>
                                <input type="text" class="form-control customer_id" required="" name="customer_id">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" class="form-control phone" required="" name="phone">
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top:50px">
                        <div class="col-sm-3">
                            <button type="submit" class="btn btn-info">Submit</button>
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
      <form action="<?php echo e(route('stroairtimeRequest',app()->getLocale())); ?>" method="POST">
          <div class="modal-body">
              <div class="form-group">
                  <center>
                      <h4>Dear <?php echo e(Auth::user()->username); ?></h4>
                      <p>You're about to Purchase Airtime</p>
                      <p>Do you want to proceed?</p>
                  </center>
              </div>
              <div class="modal-footer">
                  <button type="button" id="submit" class="btn btn-info"><?php echo app('translator')->getFromJson('Yes'); ?></button>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo app('translator')->getFromJson('No'); ?></button>
              </div>
          </div>
      </form>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
<script type="text/javascript">
document.addEventListener('DOMContentLoaded',function(){
  $('body').on('change','.service_id',function(event){
    event.preventDefault();
    var id = $(this).find(':selected').val();
    if(!id)
    {
      alert('Please select service');
      return false;
    }
    var service_id = $(this).find(':selected').val();
    var url = $(this).find(':selected').attr('data-url');
    window.location.href=url;
  });
  $('body').on('change','.select_variation_code',function(){
    var variation_amount = $(this).find(':selected').attr('data-variation_amount');
    var variation_code = $(this).find(':selected').attr('data-variation_code');
    var service_name = $(this).find(':selected').text();
    service_name = (service_name) ? service_name.trim():'';
    $('body').find('.service_name').val(service_name);
    $('body').find('.amount').val(variation_amount);
    $('body').find('.variation_code').val(variation_code);
  });
  $('body').on('click','#submit',function(event){
    event.preventDefault();
    var action="<?php echo e(route('cable_merchant_verify',app()->getLocale())); ?>";
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
          window.location.href = "<?php echo e(route('cablePreview',app()->getLocale())); ?>";
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
},false);
</script>
<script>
$( "#currency" )
  .change(function () {
    $( "#currency option:selected" ).each(function() {
      window.location.replace("<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/wallet/"+$(this).val());
  });
})
</script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('footer'); ?>
  <?php echo $__env->make('partials.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>