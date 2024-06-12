
<?php $__env->startSection('content'); ?>
<div class="row">
  <?php echo $__env->make('partials.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <div class="col-lg-9 col-md-12">
    <div class="row">
      <div class="col"  id="#sendMoney">
        <?php echo $__env->make('flash', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <div class="card">
          <div class="header">
            <h2><strong><?php echo e(__('Strovirtual Account')); ?></strong></h2>
          </div>
          <div class="body">
            <div class="card">
              <?php if($user_data->stro_account_name): ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <strong><label>Account Name:  </label></strong>
                                <td><?php echo e($user_data->stro_account_name); ?></td>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <div class="form-group">
                                <strong><label>Account Number: </label></strong>
                                <td><?php echo e($user_data->stro_account_number); ?></td>
                            </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <strong><label>Bank Name: </label></strong>
                                <td><?php echo e($user_data->stro_bank_name); ?></td>
                            </div>
                        </div>
                    </div>
                </div>
              <?php else: ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <a href="<?php echo e(route('strovirtual_account', app()->getLocale())); ?>" class="btn btn-info">Create Strovirtual account</a>
                        </div>
                    </div>
                </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
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