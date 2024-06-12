
<?php $__env->startSection('content'); ?>
<div class="row">
  <?php echo $__env->make('partials.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <div class="col-lg-9 col-md-12">
    <div class="row">
      <div class="col"  id="#sendMoney">
        <?php echo $__env->make('flash', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <div class="card">
          <div class="header">
            <h2><strong><?php echo e(__('Strowallet Airtime')); ?></strong></h2>
          </div>
          <div class="body">
            <div class="card">
              <div class="card-body">
                <div class="alert_message alert" style="display: none;"></div>
                  <form action="<?php echo e(route('stroairtimeRequest',app()->getLocale())); ?>" method="POST" id="submit_form">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Choose Service</label>
                                <select class="form-control select_airtime_service" required="" name="service_name">
                                    <option value="">--select--</option>
                                    <option <?php echo (isset($service_id) && $service_id == 'mtn') ? 'selected':''; ?> value="mtn">MTN</option>
                                    <option <?php echo (isset($service_id) && $service_id == 'glo') ? 'selected':''; ?> value="glo">GLO</option>
                                    <option <?php echo (isset($service_id) && $service_id == 'airtel') ? 'selected':''; ?> value="airtel">Airtel</option>
                                    <option <?php echo (isset($service_id) && $service_id == 'etisalat') ? 'selected':''; ?> value="etisalat">9mobile</option>
                                    <!-- <option <?php echo (isset($service_id) && $service_id == 'foreign-airtime') ? 'selected':''; ?> value="foreign-airtime">International Airtime</option> -->
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input type="text" class="form-control phone" required="" name="phone">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="text" class="form-control amount" required="" name="amount">
                            </div>
                        </div>
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
        $('body').on('click','#submit',function(event){
            event.preventDefault();
            var action="<?php echo e(route('stroairtimeRequest',app()->getLocale())); ?>";
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