
<?php $__env->startSection('content'); ?>
<div class="row">
  <?php echo $__env->make('partials.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <div class="col-lg-9 col-md-12">
    <div class="row">
      <div class="col"  id="#sendMoney">
        <?php echo $__env->make('flash', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <div class="card">
          <div class="header">
            <h2><strong><?php echo e(__('Stro Electricity Bills')); ?></strong></h2>
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
                  <div class="col-sm-6">
                    <p>
                      <strong>
                        Customer district:
                      </strong>
                      <?php if(isset($post_data->customer_district)): ?><?php echo e($post_data->customer_district); ?><?php endif; ?>
                    </p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <p>
                      <strong>
                          Address:
                      </strong>
                      <?php if(isset($post_data->address)): ?><?php echo e($post_data->address); ?><?php endif; ?>
                    </p>
                  </div>
                  <div class="col-sm-6">
                    <p>
                      <strong>
                          Meter Number:
                      </strong>
                      <?php if(isset($post_data->meter_number)): ?><?php echo e($post_data->meter_number); ?><?php endif; ?>
                    </p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <p>
                      <strong>
                          Meter type:
                      </strong>
                      <?php if(isset($post_data->meter_type)): ?><?php echo e($post_data->meter_type); ?><?php endif; ?>
                    </p>
                  </div>
                  <div class="col-sm-6">
                    <p>
                      <strong>
                          Service:
                      </strong>
                      <?php if(isset($post_data->service_name)): ?><?php echo e(strtoupper($post_data->service_name)); ?><?php endif; ?>
                    </p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-sm-6">
                    <p>
                      <strong>
                          Phone:
                      </strong>
                      <?php if(isset($post_data->phone)): ?><?php echo e($post_data->phone); ?><?php endif; ?>
                    </p>
                  </div>
                  <div class="col-sm-6">
                    <p>
                      <strong>
                          Amount:
                      </strong>
                      <?php if(isset($post_data->amount)): ?><?php echo e($post_data->amount); ?><?php endif; ?>
                    </p>
                  </div>
                </div>
                <div class="row" style="margin-top:50px">
                  <div class="col-sm-12">
                    <p class="alert alert-success show_token_message" style="display:none;"></p>
                    <a href="<?php echo e(route('stroElectricity',app()->getLocale())); ?>" style="display:none;" class="btn btn-info go_back">
                        Go Back
                    </a>
                    <button class="btn btn-info submit_form">Submit</button>
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
            var action="<?php echo e(route('stroPostElectricity',app()->getLocale())); ?>";
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
                      $('body').find('.show_token_message').show();
                      $('body').find('.go_back').show();
                      $('body').find('.show_token_message').html(result.message);
                      element.remove();
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