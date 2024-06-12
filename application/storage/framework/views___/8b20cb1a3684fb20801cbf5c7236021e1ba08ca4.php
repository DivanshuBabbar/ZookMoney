

<?php $__env->startSection('content'); ?>
    <div class="row clearfix">
        <?php echo $__env->make('partials.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	    <div class="col-lg-9 col-md-12">
		    <div class="row">
				<div class="col" >
		        	<?php echo $__env->make('partials.flash', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

		        	<?php echo $__env->make('home.partials.requests', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

			        <?php echo $__env->make('home.partials.transactions_to_confirm', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
			        
			        <?php echo $__env->make('home.partials.transactions', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

		    	</div>
		    </div>
		</div>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
	<?php echo $__env->make('partials.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>