
<?php $__env->startSection('css'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page_header'); ?>
    <h1 class="page-title">
        Payment Detail
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
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card b-radius--10 ">
                                <div class="card-body p-0">
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label>Amount:</label>
                                                <span>
                                                    <?php if(isset($model->loan_amount)): ?>
                                                        <?php echo e($model->loan_amount); ?>

                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label>Loan tenure:</label>
                                                <span>
                                                    <?php if(isset($model->loan_tenure)): ?>
                                                        <?php echo e($model->loan_tenure); ?>

                                                    <?php endif; ?>
                                                    <?php if(isset($model->tenure_type)): ?>
                                                        <?php echo e($model->tenure_type); ?>

                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label>Payback:</label>
                                                <span>
                                                    <?php if(isset($model->payback)): ?>
                                                        <?php echo e($model->payback); ?>

                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label>Total Repay:</label>
                                                <span>
                                                    <?php if(isset($model->repay_amount)): ?>
                                                        <?php echo e($model->repay_amount); ?>

                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Repay every <?php echo e($model->payback); ?>:</label>
                                                <span>
                                                    <?php if(isset($model->return_amount)): ?>
                                                        <?php echo e($model->return_amount); ?>

                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- card end -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card b-radius--10 ">
                                <div class="card-body p-0">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table mb-0">
                                                  <thead>
                                                    <tr>
                                                        <th><?php echo app('translator')->getFromJson('Sr.No'); ?></th>
                                                        <th><?php echo app('translator')->getFromJson('Amount'); ?></th>
                                                        <th><?php echo app('translator')->getFromJson('Due Date'); ?></th>
                                                        <th><?php echo app('translator')->getFromJson('Return Date'); ?></th>
                                                        <th><?php echo app('translator')->getFromJson('Status'); ?></th>
                                                    </tr>
                                                  </thead>
                                                    <tbody>
                                                        <?php if(!empty($detail)): ?>
                                                            <?php $__currentLoopData = $detail; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <tr>
                                                                    <td><?php echo e($key+1); ?></td>
                                                                    <td>
                                                                        <?php if(isset($data->amount)): ?><?php echo e($data->amount); ?><?php endif; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php if(isset($data->due_date)): ?><?php echo e(date('M d,Y',strtotime($data->due_date))); ?><?php endif; ?>
                                                                    </td>
                                                                    <td> 
                                                                        <?php if(isset($data->repay_date)): ?><?php echo e(date('M d,Y',strtotime($data->repay_date))); ?><?php endif; ?>
                                                                    </td>
                                                                    <td>
                                                                       <?php if(isset($data->status) && $data->status == 0): ?>
                                                                            <a href="#" class="btn btn-danger btn-xs">
                                                                                Unpaid
                                                                            </a>
                                                                       <?php endif; ?>
                                                                       <?php if(isset($data->status) && $data->status == 1): ?>
                                                                            <a href="#" class="btn btn-success btn-xs">
                                                                                Paid
                                                                            </a>
                                                                       <?php endif; ?>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- card end -->
                        </div>
                    </div>
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