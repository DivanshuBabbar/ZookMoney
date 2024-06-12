
<?php $__env->startSection('content'); ?>
<div class="row">
  <?php echo $__env->make('partials.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <div class="col-md-9 " style="padding-right: 0">
        <?php echo $__env->make('flash', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <div class="card">
            <div class="row api_response" style="">
                <?php if(session()->has('detail')): ?>
                    <?php $res = session()->get('detail') ?>
                    <?php $__currentLoopData = $res['response']['TXN_EPIN']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="alert alert-success">
                            <span class="font-weight:600">Mobile Network : <?php echo e($item['mobilenetwork']); ?></span><br>
                            <span class="font-weight:600">Serial No. : <?php echo e($item['sno']); ?></span><br>
                            <span class="font-weight:600">Pin No. : <?php echo e($item['pin']); ?></span><br>
                            <span class="font-weight:600">Amount : <?php echo e($item['amount']); ?></span><br>
                            <span class="font-weight:600">Transaction Date : <?php echo e($item['transactiondate']); ?></span><br>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>
            <h5 class="card-header"><?php echo app('translator')->getFromJson('Buy Epin'); ?></h5>
            <div class="card-body bg-white">
                <?php $transaction_id = session()->get('transaction_id') ?>
                <?php if(isset($transaction_id)): ?>
                    <a href="<?php echo e(url('/').'/'.app()->getLocale().'/generatePDF'); ?>/<?php echo e($transaction_id); ?>" class="btn btn-info"><i class="fa fa-print"></i>Print</a>
                <?php endif; ?>
                <form action="<?php echo e(url('/').'/'.app()->getLocale().'/buyEpinAction'); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label style="color: black;">Mobile Network</label>
                                <select class="form-control" id="MobileNetwork" name="MobileNetwork" required="">
                                    <option value="">--Select--</option>
                                    <option value="01">MTN</option>
                                    <option value="02">Glo</option>
                                    <option value="03">Etisalat</option>
                                    <option value="04">Airtel</option>
                                </select>
                            </div>
                        </div>
                         <div class="col-sm-6">
                            <div class="form-group">
                                <label style="color: black;">Amount</label>
                                <select class="form-control" id="Value" name="Value" required="">
                                    <option value="">--Select--</option>
                                    <option value="100">#100</option>
                                    <option value="200">#200</option>
                                    <option value="500">#500</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label style="color: black;">Quantity</label>
                                <input type="number" class="form-control" required="" min="1" max="100" name="Quantity">
                            </div>
                        </div>
                        <input type="hidden" class="form-control meter_number" required="" name="UserID">
                        <input type="hidden" class="form-control meter_number" required="" name="UserID">
                    </div>
                    <div class="row" style="">
                        <div class="col-sm-3">
                            <button type="submit" class="btn btn-info">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
  <?php echo $__env->make('partials.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>