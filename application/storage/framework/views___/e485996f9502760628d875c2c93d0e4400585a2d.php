
<?php $__env->startSection('content'); ?>

<div class="row">
    <?php echo $__env->make('partials.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <div class="col-md-9 " style="padding-right: 0">
        <div class="card">
            <div class="body">
                <a href="<?php echo e(url('/').'/'.app()->getLocale().'/exchange_currency'); ?>" class="btn btn-primary  mr-1">Exchange Currency
                </a>
            </div>
        </div>
        <div class="card">
            <div class="header">
                <h2><strong><?php echo e(__('Exchange Currencies List')); ?></strong></h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table m-b-0">
                        <thead>
                            <tr>
                                <th><?php echo e(__('SN')); ?></th>
                                <th class="hidden-xs text-center"><?php echo e(__('From Currency')); ?>

                                </th>
                                <th  class="hidden-xs text-center"><?php echo e(__('To Currency')); ?>

                                </th>
                                <th class="hidden-xs text-center"><?php echo e(__('Amount')); ?></th>
                                <th class="hidden-xs text-center"><?php echo e(__('Exchange rate')); ?></th>
                                <th class="hidden-xs text-center"><?php echo e(__('Total amount')); ?></th>
                                <th class="hidden-xs text-center"><?php echo e(__('Date')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $exchangeCurrenyDetail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=> $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td class="text-center"><?php echo e($key+1); ?></td>
                                <td class="text-center"><?php if(isset($row->from_currency_id)): ?><?php echo e($row->first_currency_name->name); ?><?php endif; ?></td>
                                <td class="text-center"><?php if(isset($row->to_currency_id)): ?><?php echo e($row->second_currency_name->name); ?><?php endif; ?></td>
                                <td class="text-center"><?php if(isset($row->amount)): ?><?php echo e($row->amount); ?><?php endif; ?></td>
                                <td class="text-center"><?php if(isset($row->exchange_rate)): ?><?php echo e($row->exchange_rate); ?><?php endif; ?></td>
                                <td class="text-center"><?php if(isset($row->total_amount)): ?><?php echo e($row->total_amount); ?><?php endif; ?></td>
                                <td class="text-center"><?php echo e($row->created_at->format('d M Y')); ?></td>
                                <!-- <td class="text-center">
                                    <a href="<?php echo e(url('/').'/'.app()->getLocale().'/generatePDF'); ?>/<?php echo e($row->id); ?>" class="btn btn-info">
                                        <i class="fa fa-print"></i>
                                        Print Again
                                    </a> -->
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php if($exchangeCurrenyDetail->LastPage() != 1): ?>
            <div class="footer">
                <?php echo e($exchangeCurrenyDetail->links()); ?>

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