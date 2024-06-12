
<?php $__env->startSection('content'); ?>
<div class="row">
  <?php echo $__env->make('partials.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <style type="text/css">
    .modal-backdrop{
      z-index: 0 !important; 
    }
  </style>
  <div class="col-lg-9 col-md-12">
    <div class="row">
      <div class="col"  id="#sendMoney">
        <?php echo $__env->make('flash', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <div class="card">
          <div class="header">
            <h2><strong><?php echo e(__('Payment Detail')); ?></strong></h2>
          </div>
          <div class="body">
            <div class="card">
              <h5 class="card-header"><?php echo app('translator')->getFromJson('Loan Detail'); ?></h5>
              <div class="card-body">
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
            </div>
            <div class="card">
              <h5 class="card-header">Payment Repay Schedule</h5>
              <div class="table-responsive">
                <table class="table mb-0">
                  <thead>
                    <tr>
                      <th><?php echo app('translator')->getFromJson('Sr.No'); ?></th>
                      <th><?php echo app('translator')->getFromJson('Amount'); ?></th>
                      <th><?php echo app('translator')->getFromJson('Due Date'); ?></th>
                      <th><?php echo app('translator')->getFromJson('Return Date'); ?></th>
                      <th><?php echo app('translator')->getFromJson('Status'); ?></th>
                      <th><?php echo app('translator')->getFromJson('Action'); ?></th>
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
                            <td>
                                <?php if(isset($data->status) && $data->status == 0): ?>
                                    <a href="#" data-id="<?php echo e($data->id); ?>" data-amount="<?php echo e($data->amount); ?>" class="btn btn-success btn-xs approveBtn">
                                        Repay Now
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
      </div>
    </div>
  </div>
</div>

<div id="approveModal" class="modal fade" tabindex="-1" role="dialog" style="z-index:9999;margin-top: 269px;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo app('translator')->getFromJson('Repay Loan Confirmation'); ?></h5>
                <button type="button" class="close modal_close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo e(route('loan.repay_now',app()->getLocale())); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id">
                <div class="modal-body">
                    <p><?php echo app('translator')->getFromJson('Are you sure to'); ?> <span class="font-weight-bold"><?php echo app('translator')->getFromJson('repay'); ?></span> <span class="font-weight-bold amount text-success"></span> <?php echo app('translator')->getFromJson('loan'); ?> <span class="font-weight-bold username"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn--dark modal_close" data-dismiss="modal"><?php echo app('translator')->getFromJson('Close'); ?></button>
                    <button type="submit" class="btn btn--success"><?php echo app('translator')->getFromJson('Repay Now'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
<script type="text/javascript">
  document.addEventListener('DOMContentLoaded',function(){
    $('.approveBtn').on('click', function () {
        var modal = $('#approveModal');
        modal.find('input[name=id]').val($(this).data('id'));
        modal.find('.amount').text($(this).data('amount'));
        modal.modal('show');
    });
    $('.rejectBtn').on('click', function () {
        var modal = $('#rejectModal');
        modal.find('input[name=id]').val($(this).data('id'));
        modal.find('.amount').text($(this).data('amount'));
        modal.modal('show');
    });
    $('body').on('click','.modal_close',function(event){
        event.preventDefault();
        var modal = $(this).closest('.modal');
        modal.modal('hide');
    });
  });
</script>
<script>
$( "#currency" )
  .change(function () {
    $( "#currency option:selected").each(function() {
      window.location.replace("<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/wallet/"+$(this).val());
  });
})
</script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('footer'); ?>
  <?php echo $__env->make('partials.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>