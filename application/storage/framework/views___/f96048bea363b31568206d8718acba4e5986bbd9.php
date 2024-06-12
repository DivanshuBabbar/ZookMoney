

<?php $__env->startSection('styles'); ?>
    <?php echo $__env->make('wallet.styles', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <?php echo $__env->make('partials.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

        <div class="col-md-3">
        	<div class="card bg-light" style=" box-shadow: none; background: transparent !important;">
        		<div class="header">
        			<h2><strong>Buy Vouchers</strong></h2>
        		</div>
        		<div class="body">
				        	<p>You can buy Vouchers to add credit to your wallets automatically using popular payment methods. Fill the details correctly & the amount you want to deposit.</p>
				        
        		</div>
        	</div>
        </div>
        <div class="col-md-6 ">
        	<ul id="glbreadcrumbs-two" style="margin-bottom: 35px;">
                <li><a href="#" class="a"><strong>1.<?php echo e(__('Create Order')); ?></strong> .</a></li>     
                <li><a href="#" ><strong>2.</strong><?php echo e(__('Confirm')); ?>.</a></li>
                <li><a href="#" class="a"><strong>3.</strong> <?php echo e(__('Finish')); ?>.</a></li>
            </ul>
			<div class="card">
				<div class="header">
					<h2>Deposit Money via <strong> <?php echo e($method['name']); ?></strong></h2>
				</div>
				<div class="body">
					<div class="">
                        <div>
							
							<div class="mt-4">
								<p class="sub-title">Details</p>
							</div>

							<div>
								<div class="d-flex flex-wrap justify-content-between mt-2">
									<div>
										<p>Deposit Amount</p>
									</div>

									<div class="pl-2">
										<p><?php echo e($amount); ?> <?php echo e($currency); ?></p>
									</div>
								</div>

								<div class="d-flex flex-wrap justify-content-between mt-2">
									<div>
										<p>Fee : 
											<span class="text-primary"> 
												<small> ( <?php echo e($method['fees']['percentage']); ?>% + <?php echo e($method['fees']['fixed']); ?> <?php echo e($currency); ?>)</small>
											</span>
										</p>
									</div>

									<div class="pl-2">
										<p><?php echo e($fee); ?> <?php echo e($currency); ?></p>
									</div>
								</div>
								<hr class="mb-2">

								<div class="d-flex flex-wrap justify-content-between">
									<div>
										<p class="font-weight-600">Total</p>
									</div>

									<div class="pl-2">
										<p class="font-weight-600"><?php echo e($total); ?> <?php echo e($currency); ?></p>
									</div>
								</div>
							</div>


							<div class="row m-0 mt-4 justify-content-between">
								
								<div class="col">
									<form action="<?php echo e(route('processVoucherOrder', app()->getLocale())); ?>" method="POST" style="display: block;" method="POST" accept-charset="UTF-8" id="deposit_form" novalidate="novalidate" enctype="multipart/form-data">
										<?php echo e(csrf_field()); ?>

										<input value="<?php echo e($amount); ?>" name="amount"  type="hidden">
										<input  type="hidden" name="currency_code"   value="<?php echo e($currency); ?>" >
										<input  type="hidden" name="method"   value="<?php echo e($method['id']); ?>" >
										<div class="float-right">
											<input type="submit" class="btn btn-primary bg-blue px-4 py-2 mt-2 " id="deposit-money-confirm" value="<?php echo e(__('Confirm')); ?>"/>
										</div>
									</form>
								</div>
							</div>
						</div>
                    </div>
				</div>
			</div>		
        </div>
        
    </div>
    
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
	<script>
    	$( "#voucher_currency" )
		  .change(function () {
		    $( "#voucher_currency option:selected" ).each(function() {
		      window.location.replace("<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/wallet/"+$(this).val());
		  });
		})
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
  <?php echo $__env->make('partials.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>