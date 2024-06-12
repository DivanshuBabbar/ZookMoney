

<?php $__env->startSection('content'); ?> 
 

    <div class="row">
    	<div class="col">
       <h4 class="ml-5"><?php echo e(_('About')); ?> <?php echo e(setting('site.title')); ?></h4>
       </div>
    </div>
    <div class="row">
    	<div class="col">
       <img src="{{}}" alt="">
       </div>
    </div>
    <div class="row">
    	<div class="col">
        <div class="card">
          <div class="header">
             
          </div>
          <div class="body">
                                      
              <div class="col">
              <?php echo setting('pages.about'); ?>

             </div>

          </div>
        </div>
      </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
	<?php echo $__env->make('partials.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>