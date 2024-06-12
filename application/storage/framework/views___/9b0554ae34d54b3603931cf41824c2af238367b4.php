
<?php $__env->startSection('content'); ?>
<div class="row">
  <?php echo $__env->make('partials.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <div class="col-lg-9 col-md-12">
    <div class="row">
      <div class="col" id="#sendMoney">
        <?php echo $__env->make('flash', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <div class="card">
          <div class="header">
            <h2><strong><?php echo e(__('Detail')); ?></strong></h2>
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
                              <?php if(isset($post_data->account_name)): ?><?php echo e($post_data->account_name); ?><?php endif; ?>
                          </p>
                      </div>
                      <div class="col-sm-6">
                          <p>
                              <strong>
                                  Account Number:
                              </strong>
                              <?php if(isset($post_data->account_number)): ?><?php echo e($post_data->account_number); ?><?php endif; ?>
                          </p>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-sm-6">
                          <p>
                              <strong>
                                  Bank Name:
                              </strong>
                              <?php if(isset($post_data->bank_name)): ?><?php echo e($post_data->bank_name); ?><?php endif; ?>
                          </p>
                      </div>
                      <div class="col-sm-6">
                          <p>
                              <strong>
                                  Amount:
                              </strong>
                             ₦<?php if(isset($post_data->amount)): ?><?php echo e($post_data->amount); ?><?php endif; ?>
                          </p>
                      </div>
                  </div>
                  <div class="row">
                      <div class="col-sm-6">
                          <p>
                              <strong>
                                  Fee:
                              </strong>
                            ₦<?php if(isset($post_data->fee)): ?><?php echo e($post_data->fee); ?><?php endif; ?>
                          </p>
                      </div>
                      <div class="col-sm-6">
                          <p>
                              <strong>
                                  Narration:
                              </strong>
                              <?php if(isset($post_data->narration)): ?><?php echo e($post_data->narration); ?><?php endif; ?>
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
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded',function(){
        $('body').on('click','.submit_form',function(event){
            event.preventDefault();
            var element = $(this);
            var href = "<?php echo e(route('stroBankTransfer',app()->getLocale())); ?>";
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
      window.location.replace("<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/wallet/"+$(this).val());
  });
})
</script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('footer'); ?>
  <?php echo $__env->make('partials.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>