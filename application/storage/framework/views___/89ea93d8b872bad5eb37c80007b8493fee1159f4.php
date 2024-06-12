<?php $__env->startSection('content'); ?>

<div class="row">
  <?php echo $__env->make('partials.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <div class="col-lg-9 col-md-12">
    <?php echo $__env->make('flash', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <div class="row">
      <div class="col">
        <div class="card bg-light" >
          <div class="header">
              <h2><strong><?php echo e(__('How to proceed with')); ?> <?php echo e($transferMethod->name); ?> <?php echo e(__('deposits')); ?> </strong></h2>
          </div>
          <div class="body">
            <div class="clearfix"></div>
              <div class="row mb-5">
                <div class="col-lg-12 ">
                    <label for=""></label>
                    <div  class="bg-white alert alert-secondary" role="alert" style="color: #383d41">
                        <?php echo $transferMethod->how_to_deposit; ?>

                    </div>
                </div>
              </div>
          </div>
        </div>
        <div class="card" >
          <div class="header">
              <h2><strong><?php echo e(__('Deposit Request Form')); ?></strong></h2>
          </div>
          <div class="body">
            <form action="<?php echo e(route('post.flutteraddcredit', app()->getLocale())); ?>" method="post" enctype="multipart/form-data" >
              <?php echo e(csrf_field()); ?>

              <input type="hidden" value="<?php echo e($transferMethod->id); ?>" name="tmid">
              <input type="hidden" value="<?php echo e($wid); ?>" name="wid">
              <?php if($errors->has('wid')): ?>
                  <span class="help-block">
                      <strong><?php echo e($errors->first('wid')); ?></strong>
                  </span>
              <?php endif; ?>
              <?php if($errors->has('tmid')): ?>
                <span class="help-block">
                    <strong><?php echo e($errors->first('tmid')); ?></strong>
                </span>
              <?php endif; ?>
              <div class="row bm-5">
                <div class="col">
                  <div class="form-group">
                    <label for="amount"><?php echo e(__('Amount')); ?></label>
                    <input type="text" class="form-control" id="amount" name="amount" value="<?php echo e(old('amount')); ?>" required>
                  </div>
                </div>
              </div>
              <div class="row mb-5">
                <div class="col mt-5 ">
                  <input type="submit" class="btn btn-primary float-right" value="<?php echo e(__('Save Deposit')); ?>">
                </div>
              </div>
              <div class="clearfix"></div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
<script>
$( "#deposit_method" )
  .change(function () {
    $( "#deposit_method option:selected" ).each(function() {
      window.location.replace("<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/addcredit/"+$(this).val());
    });
  });
  $( "#deposit_currency" )
  .change(function () {
    $( "#deposit_currency option:selected" ).each(function() {
      window.location.replace("<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/wallet/"+$(this).val());
    });
  })
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>