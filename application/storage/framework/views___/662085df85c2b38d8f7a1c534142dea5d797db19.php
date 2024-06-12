
<?php $__env->startSection('css'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('page_header'); ?>
    <h1 class="page-title">
        Loan Preview
    </h1>
    <?php echo $__env->make('voyager::multilingual.language-selector', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<style type="text/css">
    .modal-backdrop{
      z-index: 0 !important; 
    }
    .thumb_img {
      border: 1px solid #ddd; /* Gray border */
      border-radius: 4px;  /* Rounded border */
      padding: 5px; /* Some padding */
      width: 150px; /* Set a small width */
    }

    /* Add a hover effect (blue shadow) */
    .thumb_img:hover {
      box-shadow: 0 0 2px 1px rgba(0, 140, 186, 0.5);
    }
  </style>
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
                                                    <?php if(isset($loans_details->loan_amount)): ?>
                                                        <?php echo e($loans_details->loan_amount); ?>

                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label>Loan tenure:</label>
                                                <span>
                                                    <?php if(isset($loans_details->loan_tenure)): ?>
                                                        <?php echo e($loans_details->loan_tenure); ?>

                                                    <?php endif; ?>
                                                    <?php if(isset($loans_details->tenure_type)): ?>
                                                        <?php echo e($loans_details->tenure_type); ?>

                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label>Payback:</label>
                                                <span>
                                                    <?php if(isset($loans_details->payback)): ?>
                                                        <?php echo e($loans_details->payback); ?>

                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label>Total Repay:</label>
                                                <span>
                                                    <?php if(isset($loans_details->repay_amount)): ?>
                                                        <?php echo e($loans_details->repay_amount); ?>

                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Repay every <?php echo e($loans_details->payback); ?>:</label>
                                                <span>
                                                    <?php if(isset($loans_details->return_amount)): ?>
                                                        <?php echo e($loans_details->return_amount); ?>

                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card b-radius--10 ">
                                <div class="card-body p-0">
                                    <div class="row" >
                                        <div class="col-sm-4">
                                            <div class="card">
                                                <h5 class="card-header"><?php echo app('translator')->getFromJson('Customer Detail'); ?></h5>
                                                <div class="card-body bg-white">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>BVN:</label>
                                                                <span>
                                                                    <?php if(isset($loans_details->bvn)): ?>
                                                                        <?php echo e($loans_details->bvn); ?>

                                                                    <?php endif; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>ID type and the number ( NIN and DRIVER'S LICENSE ONLY):</label>
                                                                <span>
                                                                    <?php if(isset($loans_details->id_type_number)): ?>
                                                                        <?php echo e($loans_details->id_type_number); ?>

                                                                    <?php endif; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Full Name:</label>
                                                                <span>
                                                                    <?php if(isset($loans_details->full_name)): ?>
                                                                        <?php echo e($loans_details->full_name); ?>

                                                                    <?php endif; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Residential address:</label>
                                                                <span>
                                                                    <?php if(isset($loans_details->residential_address)): ?>
                                                                        <?php echo e($loans_details->residential_address); ?>

                                                                    <?php endif; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Account Number:</label>
                                                                <span>
                                                                    <?php if(isset($loans_details->account_number)): ?>
                                                                        <?php echo e($loans_details->account_number); ?>

                                                                    <?php endif; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Contact:</label>
                                                                <span>
                                                                    <?php if(isset($loans_details->contact)): ?>
                                                                        <?php echo e($loans_details->contact); ?>

                                                                    <?php endif; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Home address:</label>
                                                                <span>
                                                                    <?php if(isset($loans_details->home_address)): ?>
                                                                        <?php echo e($loans_details->home_address); ?>

                                                                    <?php endif; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php if(isset($loans_details->selfie_passport)): ?>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                              <div class="form-group">
                                                                <label>Selfie or passport:</label>
                                                              </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                              <div class="form-group">
                                                                <span>
                                                                  <a download="<?php echo e($loans_details->selfie_passport); ?>" href="<?php echo e(url('assets/images/loan/'.$loans_details->selfie_passport)); ?>">
                                                                    <img class="thumb_img" src="<?php echo e(url('assets/images/loan/'.$loans_details->selfie_passport)); ?>" alt="image">
                                                                  </a>
                                                                </span>
                                                              </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="card">
                                                <h5 class="card-header"><?php echo app('translator')->getFromJson('Guarantors'); ?> (First)</h5>
                                                <div class="card-body bg-white">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>BVN:</label>
                                                                <span>
                                                                    <?php if(isset($loans_details->c_bvn)): ?>
                                                                        <?php echo e($loans_details->c_bvn); ?>

                                                                    <?php endif; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>ID type and the number (voters card not acceptable):</label>
                                                                <span>
                                                                    <?php if(isset($loans_details->c_id_type_number)): ?>
                                                                        <?php echo e($loans_details->c_id_type_number); ?>

                                                                    <?php endif; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Full Name:</label>
                                                                <span>
                                                                    <?php if(isset($loans_details->c_full_name)): ?>
                                                                        <?php echo e($loans_details->c_full_name); ?>

                                                                    <?php endif; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Residential address:</label>
                                                                <span>
                                                                    <?php if(isset($loans_details->c_residential_address)): ?>
                                                                        <?php echo e($loans_details->c_residential_address); ?>

                                                                    <?php endif; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Contact:</label>
                                                                <span>
                                                                    <?php if(isset($loans_details->c_contact)): ?>
                                                                        <?php echo e($loans_details->c_contact); ?>

                                                                    <?php endif; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Business address:</label>
                                                                <span>
                                                                    <?php if(isset($loans_details->c_business_address)): ?>
                                                                        <?php echo e($loans_details->c_business_address); ?>

                                                                    <?php endif; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php if(isset($loans_details->c_upload_passport)): ?>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                              <div class="form-group">
                                                                <label>Upload passport:</label>
                                                              </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                              <div class="form-group">
                                                                <span>
                                                                  <a download="<?php echo e($loans_details->c_upload_passport); ?>" href="<?php echo e(url('assets/images/loan/'.$loans_details->c_upload_passport)); ?>">
                                                                    <img class="thumb_img" src="<?php echo e(url('assets/images/loan/'.$loans_details->c_upload_passport)); ?>" alt="image">
                                                                  </a>
                                                                </span>
                                                              </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="card">
                                                <h5 class="card-header"><?php echo app('translator')->getFromJson('Guarantors'); ?> (Second)</h5>
                                                <div class="card-body bg-white">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>BVN:</label>
                                                                <span>
                                                                    <?php if(isset($loans_details->b_bvn)): ?>
                                                                        <?php echo e($loans_details->b_bvn); ?>

                                                                    <?php endif; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>ID type and the number (voters card not acceptable):</label>
                                                                <span>
                                                                    <?php if(isset($loans_details->b_id_type_number)): ?>
                                                                        <?php echo e($loans_details->b_id_type_number); ?>

                                                                    <?php endif; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Full Name:</label>
                                                                <span>
                                                                    <?php if(isset($loans_details->b_full_name)): ?>
                                                                        <?php echo e($loans_details->b_full_name); ?>

                                                                    <?php endif; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Residential address:</label>
                                                                <span>
                                                                    <?php if(isset($loans_details->b_residential_address)): ?>
                                                                        <?php echo e($loans_details->b_residential_address); ?>

                                                                    <?php endif; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Contact:</label>
                                                                <span>
                                                                    <?php if(isset($loans_details->b_contact)): ?>
                                                                        <?php echo e($loans_details->b_contact); ?>

                                                                    <?php endif; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="form-group">
                                                                <label>Business address:</label>
                                                                <span>
                                                                    <?php if(isset($loans_details->b_business_address)): ?>
                                                                        <?php echo e($loans_details->b_business_address); ?>

                                                                    <?php endif; ?>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?php if(isset($loans_details->b_upload_passport)): ?>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                              <div class="form-group">
                                                                <label>Upload passport:</label>
                                                              </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                              <div class="form-group">
                                                                <span>
                                                                  <a download="<?php echo e($loans_details->b_upload_passport); ?>" href="<?php echo e(url('assets/images/loan/'.$loans_details->b_upload_passport)); ?>">
                                                                    <img class="thumb_img" src="<?php echo e(url('assets/images/loan/'.$loans_details->b_upload_passport)); ?>" alt="image">
                                                                  </a>
                                                                </span>
                                                              </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- card end -->
                        </div>
                    </div>
                    <div class="row">
                        <?php if(isset($loans_details->status) && $loans_details->status == 0): ?>
                            <div class="col-sm-2">
                                <a href="javascript:void(0)" data-id="<?php echo e($loans_details->id); ?>" data-amount="<?php echo e($loans_details->loan_amount); ?>" data-username="<?php echo e($loans_details->user->username); ?>" class="btn btn-info approveBtn">
                                    Approve
                                </a>
                                <a href="javascript:void(0)" data-id="<?php echo e($loans_details->id); ?>" data-amount="<?php echo e($loans_details->loan_amount); ?>" data-username="<?php echo e($loans_details->user->username); ?>" class="btn btn-danger rejectBtn">
                                    Reject
                                </a>
                            </div>
                        <?php endif; ?>
                        <?php if(isset($loans_details->status) && $loans_details->status == 1): ?>
                            <a href="javascript:void(0)"class="btn btn-success">
                                Approved
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="approveModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo app('translator')->getFromJson('Approve Loan Confirmation'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo e(route('loan.approve',app()->getLocale())); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p><?php echo app('translator')->getFromJson('Are you sure to'); ?> <span class="font-weight-bold"><?php echo app('translator')->getFromJson('approve'); ?></span> <span class="font-weight-bold amount text-success"></span> <?php echo app('translator')->getFromJson('loan of'); ?> <span class="font-weight-bold username"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal"><?php echo app('translator')->getFromJson('Close'); ?></button>
                    <button type="submit" class="btn btn--success"><?php echo app('translator')->getFromJson('Approve'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="rejectModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo app('translator')->getFromJson('Reject Loan Confirmation'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo e(route('loan.reject',app()->getLocale())); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p><?php echo app('translator')->getFromJson('Are you sure to'); ?> <span class="font-weight-bold"><?php echo app('translator')->getFromJson('reject'); ?></span> <span class="font-weight-bold amount text-success"></span> <?php echo app('translator')->getFromJson('loan of'); ?> <span class="font-weight-bold username"></span>?</p>
                    <div class="form-group">
                        <label class="font-weight-bold mt-2"><?php echo app('translator')->getFromJson('Reason for Rejection'); ?></label>
                        <textarea name="reason" id="message" placeholder="<?php echo app('translator')->getFromJson('Reason for Rejection'); ?>" class="form-control" rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark" data-dismiss="modal"><?php echo app('translator')->getFromJson('Close'); ?></button>
                    <button type="submit" class="btn btn--danger"><?php echo app('translator')->getFromJson('Reject'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
    <!-- End Delete File Modal -->
<?php $__env->stopSection(); ?>
<?php $__env->startSection('javascript'); ?>
    <script>
    (function($){
        "use strict";
        $('.approveBtn').on('click', function () {
            var modal = $('#approveModal');
            modal.find('input[name=id]').val($(this).data('id'));
            modal.find('.amount').text($(this).data('amount'));
            modal.find('.username').text($(this).data('username'));
            modal.modal('show');
        });
        $('.rejectBtn').on('click', function () {
            var modal = $('#rejectModal');
            modal.find('input[name=id]').val($(this).data('id'));
            modal.find('.amount').text($(this).data('amount'));
            modal.find('.username').text($(this).data('username'));
            modal.modal('show');
        });
        })(jQuery);
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('voyager::master', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>