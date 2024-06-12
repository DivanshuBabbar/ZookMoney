
<?php $__env->startSection('styles'); ?>
    <?php echo $__env->make('wallet.styles', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <?php echo $__env->make('partials.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
         <div class="col-lg-9 col-md-12">
            <div class="row">
        <div class="col col-md-12">
            <div class="row">
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
                <div class="col-md-9 ">
                	<ul id="glbreadcrumbs-two" style="margin-bottom: 35px;">
                        <li><a href="#"><strong>1.<?php echo e(__('Create Order')); ?></strong> .</a></li>     
                        <li><a href="#" class="a"><strong>2.</strong><?php echo e(__('Confirm')); ?>.</a></li>
                        <li><a href="#" class="a"><strong>3.</strong> <?php echo e(__('Finish')); ?>.</a></li>
                    </ul>
        			<div class="card">
        				<?php echo $__env->make('vouchers.components.vue', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
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