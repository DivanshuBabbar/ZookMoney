
<?php $__env->startSection('css'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page_header'); ?>
    <h1 class="page-title">
        Rejected Loan
    </h1>
    <?php echo $__env->make('voyager::multilingual.language-selector', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="page-content edit-add container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-push-1">
        <div class="clearfix"></div>
            <?php echo $__env->make('flash', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        </div>
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <table class="table table--light style--two">
                        <thead>
                        <tr>
                            <th scope="col"><?php echo app('translator')->getFromJson('Sr.No'); ?></th>
                            <th scope="col"><?php echo app('translator')->getFromJson('User Name'); ?></th>
                            <th scope="col"><?php echo app('translator')->getFromJson('Loan Amount'); ?></th>
                            <th scope="col"><?php echo app('translator')->getFromJson('Loan Tenuer'); ?></th>
                            <th scope="col"><?php echo app('translator')->getFromJson('Payback'); ?></th>
                            <th scope="col"><?php echo app('translator')->getFromJson('Date'); ?></th>
                            <th scope="col"><?php echo app('translator')->getFromJson('Reason'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $rejected_loan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                             <td><?php echo e($key+1); ?></td>
                            <td data-label="<?php echo app('translator')->getFromJson('Username'); ?>"><?php echo e($data->user->name); ?></td>
                            <td data-label="<?php echo app('translator')->getFromJson('Loan Amount'); ?>"><?php if(isset($data->loan_amount)): ?><?php echo e($data->loan_amount); ?><?php endif; ?></td>
                            <td data-label="<?php echo app('translator')->getFromJson('Loan Tenuer'); ?>">
                                <?php if(isset($data->loan_tenure)): ?><?php echo e($data->loan_tenure); ?><?php endif; ?>
                                <?php if(isset($data->tenure_type)): ?><?php echo e($data->tenure_type); ?><?php endif; ?>
                            </td>
                            <td data-label="<?php echo app('translator')->getFromJson('Payback'); ?>"> <?php if(isset($data->payback)): ?><?php echo e(ucfirst($data->payback)); ?><?php endif; ?></td>
                            <td data-label="<?php echo app('translator')->getFromJson('Date'); ?>"> <?php if(isset($data->created_at)): ?><?php echo e(date('M d,Y',strtotime($data->created_at))); ?><?php endif; ?></td>
                            <td>
                               <?php if(isset($data->reason)): ?><?php echo e($data->reason); ?><?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td class="text-muted text-center" colspan="100%"><?php echo e(__($empty_message)); ?></td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                    <?php if($rejected_loan->LastPage() != 1): ?>
                      <div class="card-footer py-4">
                          <?php echo e($rejected_loan->links()); ?>

                      </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
    <!-- End Delete File Modal -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('javascript'); ?>
    
<?php $__env->stopSection(); ?>

<?php echo $__env->make('voyager::master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>