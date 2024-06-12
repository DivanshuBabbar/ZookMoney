<?php if($transactions->total() > 0): ?>
<div class="card user-account">
          <div class="header">
              <h2><strong>Complete</strong>Transactions</h2>
              
          </div>
          <div class="body">
              <div class="table-responsive">
                  <table class="table m-b-0">
                      <thead>
                          <tr>
                              <th></th>
                              <th><?php echo e(__('Id')); ?></th>
                              <th ><?php echo e(__('Date')); ?></th>
                              <th class="hidden-xs"><?php echo e(__('Name')); ?></th>
                              <th class="hidden-xs"><?php echo e(__('Gross')); ?></th>
                              <th class="hidden-xs"><?php echo e(__('Fee')); ?></th>
                              <th><?php echo e(__('Net')); ?></th>
                              <th><?php echo e(__('Balance')); ?></th>
                          </tr>
                      </thead>
                      <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                          <td><img src="<?php echo e($transaction->thumb()); ?>" alt="" class="rounded" loading="lazy"></td>
                          <td><?php echo e($transaction->id); ?><br></td>
                          <td><?php echo e($transaction->created_at->format('d M Y')); ?> <br> <?php echo $__env->make('home.partials.status', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?></td>
                          <td class="hidden-xs"> <?php echo $__env->make('home.partials.name', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?></td>
                          <td class="hidden-xs"><span class="float-right"><?php echo e($transaction->gross()); ?></span></td>
                          <td class="hidden-xs"> <span class="float-right"><?php echo e($transaction->fee()); ?></span></td>
                          <td><span class="float-right"><?php echo e($transaction->net()); ?></span></td>
                          <td><span class="float-right"><?php echo e($transaction->balance()); ?></span></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                  
                    <?php endif; ?>
                  </table>
              </div>
          </div>
          <?php if($transactions->LastPage() != 1): ?>
            <div class="footer">
                <?php echo e($transactions->links()); ?>

            </div>
          <?php else: ?>
          
          <?php endif; ?>
</div>
<?php endif; ?>
<?php if($transactions->count() == 0): ?>
  <div class="container">
    <div class="card bg-info">
      <div class="header">
        <h2><i class="zmdi zmdi-alert-circle-o text-white"></i> <strong class="text-white">Info</strong></h2>
          <ul class="header-dropdown">  
              <li class="remove">
                  <a role="button" class="boxs-close "><i class="zmdi zmdi-close text-white"></i></a>
              </li>
          </ul>
      </div>
      <div class="body block-header">
          <div class="row">
              <div class="col">
                  <p class="text-white"><strong> <?php echo e(__('Your account is Fresh and New !')); ?> </strong> <br><?php echo e(__('Start by requesting money from friends or by selling online and collecting payments in your wallet.')); ?></p>
              </div>   
          </div>
      </div>
    </div>
  </div>
<?php endif; ?>