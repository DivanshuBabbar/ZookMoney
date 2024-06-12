<?php $__env->startSection('content'); ?>

<div class="row">
    <?php echo $__env->make('partials.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <div class="col-md-9 " style="padding-right: 0">
    <div class="card">
            <div class="header">
                <h2><strong><?php echo e(__('Add a new virtual card')); ?></strong></h2>
            </div>
            <div class="body">
                <div id="card-design" class="p-4 d-flex text-white flex-column mx-auto mb-4" style="max-width:300px;border-radius: .55em; background: #2a43aa;">
                    <div class="mb-4 d-flex flex-row justify-content-between">
                        <span class="h5">Company</span>
                        <span>
                            <?php if(isset($data['current_balance'])): ?><?php echo e('$'.$data['current_balance']); ?><?php endif; ?>
                        </span>
                    </div>   
                    <div class="d-flex mb-4 flex-row justify-content-between">
                        <div><?php echo e(substr($vcard->cardpan,0,4)); ?></div>
                        <div class="mx-1"><?php echo e(substr($vcard->cardpan,4,4)); ?></div>
                        <div class="mx-1"><?php echo e(substr($vcard->cardpan,8,4)); ?></div>
                        <div><?php echo e(substr($vcard->cardpan,12,4)); ?></div>
                    </div>
                    <div class="d-flex flex-row justify-content-between">
                        <div class="d-flex flex-column">
                            <div class="d-flex flex-row">
                                <span class="mr-2" style="font-size:8px;"><?php echo e(__('VALID')); ?><br><?php echo e(__('TILL')); ?></span>
                                <div class="align-self-center"><?php echo e($vcard->expiration); ?></div>
                            </div>
                            <div>
                                <?php if(isset($data['name_on_card'])): ?><?php echo e($data['name_on_card']); ?><?php endif; ?>
                            </div>
                        </div>
                        <img class="align-self-end mb-2" src="https://strowallet.com/assets/master.png" alt="" width="15%" height="15%">
                    </div>
                </div>
                <div class="card">
                    <div class="header">
                        <h2 class="text-black"><strong><?php echo e(__('current_balance: ').$data['current_balance'].'$'); ?></strong></h2>
                    </div>
                    <div class="body d-flex flex-column">
                        <div class="d-flex flex-row">
                            <span class="mr-3"><?php echo e(__('Card Number')); ?></span>&nbsp;&nbsp;
                            <span><strong><?php echo e(substr($vcard->cardpan,0,4).'-'.substr($vcard->cardpan,4,4).'-'.substr($vcard->cardpan,8,4).'-'.substr($vcard->cardpan,12,4)); ?></strong></span>
                        </div>
                        <div class="d-flex flex-row">
                            <span class="mr-3"><?php echo e(__('CVV')); ?></span>&nbsp;&nbsp;
                            <span><strong><?php echo e($data['cvc']); ?></strong></span>
                        </div>
                        <div class="d-flex flex-row">
                            <span class="mr-3"><?php echo e(__('Card Type')); ?></span>&nbsp;&nbsp;
                            <span><strong><?php echo e($vcard->type); ?></strong></span>
                        </div>
                        <div class="d-flex flex-row">
                            <span class="mr-3"><?php echo e(__('Valid Until')); ?></span>&nbsp;&nbsp;
                            <span><strong><?php echo e($vcard->expiration); ?></strong></span>
                        </div>
                        <div class="d-flex flex-row">
                            <span class="mr-3"><?php echo e(__('State')); ?></span>&nbsp;&nbsp;
                            <span><strong>CA</strong></span>
                        </div>
                        <div class="d-flex flex-row">
                            <span class="mr-3"><?php echo e(__('Country')); ?></span>&nbsp;&nbsp;
                            <span><strong>USA</strong></span>
                        </div>
                        <div class="d-flex flex-row">
                            <span class="mr-3"><?php echo e(__('Postal code')); ?></span>&nbsp;&nbsp;
                            <span><strong>94105</strong></span>
                        </div>
                        <div class="d-flex flex-row">
                            <span class="mr-3"><?php echo e(__('Address 1')); ?></span>&nbsp;&nbsp;
                            <span><strong>1088 Roosevelt Street</strong></span>
                        </div>
                        <div class="d-flex flex-row">
                            <span class="mr-3"><?php echo e(__('Phone Number')); ?></span>&nbsp;&nbsp;
                            <span><strong>+1(857) 5745195</strong></span>
                        </div>
                        <div class="d-flex flex-row">
                            <span class="mr-3"><?php echo e(__('Date of creation')); ?></span>&nbsp;&nbsp;
                            <span><strong><?php echo e($vcard->created_at); ?></strong></span>
                        </div>
                    </div>
                    <div class="footer">
                        <div class="float-right mr-3">
                            <a href="<?php echo e(url('/').'/'.app()->getLocale().'/vcard/fund/'.$vcard->id); ?>" class="btn btn-primary">Add Funds
                            </a>
                        </div>
                         <!-- <div class="float-right mr-3">
                            <a href="https://www.payzoft.com/page/9" class="btn btn-primary">Withdraw from Card
                            </a>
                        </div> -->
                    </div>
                </div>
                <div class="card">
                    <div class="header">
                        <h2><strong><?php echo e(__('Card Transactions')); ?></strong></h2>
                    </div>
                    <div class="body">
                        <div class="table-responsive">
                            <table class="table m-b-0">
                                <thead>
                                    <tr>
                                        <th class="hidden-xs text-center"><?php echo e(__('Transaction Type')); ?></th>
                                        <th class="hidden-xs text-center"><?php echo e(__('Details')); ?></th>
                                        <th class="hidden-xs text-center"><?php echo e(__('Amount')); ?>

                                        </th>
                                        <!-- <th class="hidden-xs text-center"><?php echo e(__('Fee')); ?>

                                        </th> -->
                                        <th class="hidden-xs text-center"><?php echo e(__('Date')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__empty_1 = true; $__currentLoopData = $tranxs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tranx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="text-center"><?php echo e($tranx['type']); ?></td>
                                        <td class="text-center"><?php echo e($tranx['narration']); ?></td>
                                        <!-- <td class="text-center"><?php echo e($tranx['amount']); ?></td> -->
                                        <td class="text-center"><?php echo e($tranx['amount']); ?> <?php echo e($tranx['currency']); ?></td>
                                        <td class="text-center">
                                            <?php echo e(date('Y-m-d  H:i:s',strtotime($tranx['narration']))); ?>

                                        </td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php if($tranxs->LastPage() != 1): ?>
                    <div class="footer">
                        <?php echo e($tranxs->links()); ?>

                    </div>
                    <?php endif; ?>
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