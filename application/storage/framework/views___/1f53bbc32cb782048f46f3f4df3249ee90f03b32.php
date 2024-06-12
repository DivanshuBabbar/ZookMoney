
<?php $__env->startSection('content'); ?>

<div class="row">
  <?php echo $__env->make('partials.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <div class="col-md-9 " style="padding-right: 0">
        <div class="card">
            <div class="body">
                <div class="media border border-radius">
                    <div class="media-body">
                        <p>
                            <strong class="title pt-2 pl-2 float-left"><?php echo e(__('Add a new virtual card')); ?> 
                            </strong>
                            <a href="<?php echo e(route('vcard.create',  app()->getLocale())); ?>" class="btn btn-primary float-right mr-1">Add
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="header">
                <h2><strong><?php echo e(__('My virtual cards')); ?></strong></h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table m-b-0">
                        <thead>
                            <tr>
                                <th><?php echo e(__('Id')); ?></th>
                                <th class="hidden-xs text-center"><?php echo e(__('Card Number')); ?>

                                </th>
                                <th class="hidden-xs text-center"><?php echo e(__('Card Type')); ?>

                                </th>
                                <th class="hidden-xs text-center"><?php echo e(__('Action')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $vcards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vcard): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="text-center"><?php echo e($vcard->rave_id); ?></td>
                                <td class="text-center"><?php echo e($vcard->cardpan); ?></td>
                                <td class="text-center"><?php echo e($vcard->type); ?></td>
                                <td class="text-center">
                                    <a class="btn btn-success mr-1 text-white" href="<?php echo e(url('/').'/'.app()->getLocale().'/vcard/details/'.$vcard->id); ?>"><?php echo e(__('View Card')); ?></a>
                                    <a class="btn btn-danger text-white" href="<?php echo e(url('/').'/'.app()->getLocale().'/vcard/fund/'.$vcard->id); ?>" ><?php echo e(__('Fund Card')); ?></a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php if($vcards->LastPage() != 1): ?>
            <div class="footer">
                <?php echo e($vcards->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
  <?php echo $__env->make('partials.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>