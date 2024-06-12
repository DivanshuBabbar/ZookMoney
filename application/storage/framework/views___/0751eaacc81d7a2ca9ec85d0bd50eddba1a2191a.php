



<?php $__env->startSection('content'); ?>
    <div class="row">
        <?php echo $__env->make('partials.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
         <div class="col-lg-9 col-md-12">
             
            <div class="row">
                <div class="col" >
                 
                   <div class="card bg-light" >
                    <div class="header">
                        <h2><strong><?php echo e(__('Choose your prefered manual deposit method')); ?></strong></h2>
                    </div>
                    <div class="body">
                     
                      <div class="row">
                        <?php $__empty_1 = true; $__currentLoopData = $transferMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="col-lg-4 col-md-4 col-sm-12">
                            <a href="<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/deposit/m/<?php echo e($method->id); ?>">
                                <div class="card product_item">
                                    <div class="body text-center">
                                        <div class="cp_img">
                                            <img src="<?php echo e($method->thumbnail); ?>" alt="Product" class="img-fluid">
                                       
                                        </div>
                                        <h6 style="margin-top: 10px"><?php echo e($method->name); ?> </h6>
                                     
                                    </div>
                                </div>
                            </a>                
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <?php endif; ?>
                        </div>
                    </div>
                  </div>

                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>