

<?php $__env->startSection('content'); ?>

    <div class="row">
        <?php echo $__env->make('partials.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
      <div class="col-md-9 ">
        <?php echo $__env->make('partials.flash', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <div class="card bg-light">
          <div class="header">
            <h2><?php echo e(__('Select an adress to send the money from withdraw')); ?> </h2>
          </div>
          <div class="body">
            <div class="row mb-4">
              <div class="col">
                <a href="<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/transfer/<?php echo e($wallet->currency_id); ?>/methods" class="btn btn-primary btn-round bg-blue   ">+ Add account to receive withdraw</a>
              </div>
            </div>  
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-lg-12">
                  <div class="row">
                     <?php $__currentLoopData = $methods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <a href="<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/payout/request/<?php echo e($method->pivot->id); ?>">
                                <div class="card product_item ">
                                    <div class="body text-center" style="display: flex;align-items: center;justify-content: center">
                                        <div class="cp_img">
                                            <img src="<?php echo e($method->thumbnail); ?>" alt="Product" class="img-fluid" style="opacity: 0.2">
                                       
                                        </div>
                                        <h6 style="margin-top: 10px; position: absolute;"><?php echo e($method->name); ?><br><span class="text-success"> <?php echo e($method->pivot->adress); ?></span></h6>
                                     
                                    </div>
                                </div>
                            </a>                
                        </div>
                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                   
                  </div>
              </div>
            </div>
          </div>
        </div>
        
      </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<?php echo $__env->make('withdrawals.vue', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<script>
$( "#withdrawal_method" )
  .change(function () {
    $( "#withdrawal_method option:selected" ).each(function() {
      window.location.replace("<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/withdrawal/request/"+$(this).val());
  });
});

$( "#withdrawal_currency" )
  .change(function () {
    $( "#withdrawal_currency option:selected" ).each(function() {
      window.location.replace("<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/wallet/"+$(this).val());
  });
})
</script>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('footer'); ?>
  <?php echo $__env->make('partials.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>