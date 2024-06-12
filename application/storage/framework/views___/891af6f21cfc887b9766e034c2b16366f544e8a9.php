
<?php $__env->startSection('content'); ?>
<div class="row">
  <?php echo $__env->make('partials.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <div class="col-lg-9 col-md-12">
    <div class="row">
      <div class="col"  id="#sendMoney">
        <?php echo $__env->make('flash', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <div class="card">
          <div class="header">
            <h2><strong><?php echo e(__('Stro Cable TV')); ?></strong></h2>
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
                            <?php if(isset($post_data->customer_name)): ?><?php echo e($post_data->customer_name); ?><?php endif; ?>
                        </p>
                    </div>
                    <!-- <div class="col-sm-6">
                        <p>
                            <strong>
                                Customer Number:
                            </strong>
                            <?php if(isset($post_data->customer_number)): ?><?php echo e($post_data->customer_number); ?><?php endif; ?>
                        </p>
                    </div> -->
                    <div class="col-sm-6">
                        <p>
                            <strong>
                                Service:
                            </strong>
                            <?php if(isset($post_data->service_id)): ?><?php echo e(strtoupper($post_data->service_id)); ?><?php endif; ?>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <p>
                            <strong>
                                Smart Card Number:
                            </strong>
                            <?php if(isset($post_data->customer_id)): ?><?php echo e($post_data->customer_id); ?><?php endif; ?>
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <p>
                            <strong>
                                Phone:
                            </strong>
                            <?php if(isset($post_data->phone)): ?><?php echo e($post_data->phone); ?><?php endif; ?>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <p>
                            <strong>
                                Amount:
                            </strong>
                            <?php if(isset($post_data->amount)): ?><?php echo e($post_data->amount); ?><?php endif; ?>
                        </p>
                    </div>
                    <div class="col-sm-6">
                        <p>
                            <strong>
                                Verification Code:
                            </strong>
                            <?php if(isset($post_data->variation_code)): ?><?php echo e($post_data->variation_code); ?><?php endif; ?>
                        </p>
                    </div>
                </div>
                <div class="row" style="margin-top:50px">
                    <div class="col-sm-3">
                        <button class="btn btn-info submit_form">Submit</button>
                        <!-- <a href="<?php echo e(url('/user/postCableRequest')); ?>" disabled class="btn btn-info submit_form">Submit</a> -->
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
        $('body').on('click','.submit_form',function(event){
            event.preventDefault();
            var element = $(this);
            var action="<?php echo e(route('postCableRequest',app()->getLocale())); ?>";
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
                        window.location.href = "<?php echo e(route('strotvSubscription',app()->getLocale())); ?>";
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
      window.location.replace("<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/wallet/"+$(this).val());
  });
})
</script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('footer'); ?>
  <?php echo $__env->make('partials.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>