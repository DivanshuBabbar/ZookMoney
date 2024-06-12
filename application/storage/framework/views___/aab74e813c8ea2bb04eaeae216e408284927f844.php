

<?php $__env->startSection('content'); ?>

    <div class="row">
        <?php echo $__env->make('partials.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
      <div class="col-lg-9 ">
        <?php echo $__env->make('partials.flash', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
       		<?php if($paymentlinks->total() > 0): ?>
            <div class="row">
              <div class="col mb-5">
                 <a  href="#largePaymentLinkFormModal" data-toggle="modal" data-target="#largePaymentLinkFormModal"  class="btn btn-primary bg-teal float-right"><?php echo e(__('Request Payment')); ?></a>
              </div>
            </div>
            <div class="row">
         			<?php $__currentLoopData = $paymentlinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
  	       		<div class="col-md-6 col-lg-4 col-sm-6">
  		       		<div class="card">
      						<div class="header">
      							<h2><?php echo e($link->name); ?></h2>
      							<ul class="header-dropdown">
                          <li class="dropdown"> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="zmdi zmdi-more"></i> </a>
                              <ul class="dropdown-menu dropdown-menu-right float-right">
                                  <li><a href="<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/web/payment/<?php echo e($link->paymentlink_id); ?>">preview</a></li>
                                  
                              </ul>
                          </li>
                      </ul>
      						</div>
      						<div class="body">
      							<p>
      							<?php if($link->amount == 0): ?>
      								<strong><?php echo e(__('Amount')); ?></strong> : <?php echo e(__('Decided by Payer')); ?>

      							<?php else: ?>
      								<strong><?php echo e(__('Amount')); ?></strong> : <?php echo e(\App\Helpers\Money::instance()->value($link->amount, $link->currency->symbol, $link->currency->is_crypto)); ?>

      							<?php endif; ?>
      							<br>
      								<strong><?php echo e(__('Currency')); ?></strong> : <?php echo e($link->currency->name); ?> <span class="text-primary">  <?php echo e($link->currency->code); ?></span>
      							<br>
      								<strong><?php echo e(__('Created')); ?></strong> : <?php echo e(\Carbon\Carbon::parse($link->createdAt)->diffForHumans()); ?>

      							<br>
      								<strong><?php echo e(__('Link')); ?></strong> : <a href="<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/web/payment/<?php echo e($link->paymentlink_id); ?>"> <?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/web/payment/<?php echo e($link->paymentlink_id); ?> </a>
      							</p>
      							<p class="mb-0">
      								<?php if($link->payment_status == 1): ?>
      									<button class="btn btn-sm btn-round btn-simple btn-primary"><?php echo e(__('Active')); ?></button>
      								<?php else: ?>
      									<button class="btn btn-sm btn-round btn-simple btn-warning"><?php echo e(__('iddle')); ?></button>
      								<?php endif; ?>
      							</p>
      						</div>
  		        	</div>
  		        </div>
  	        	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php if($paymentlinks->LastPage() != 1): ?>
            <div class="row">
              <div class="col">
                <div class="card">
                  <div class="body">
                     <?php echo e($paymentlinks->links()); ?>

                  </div>
                </div>
              </div>
            </div>
            <?php endif; ?>
	        <?php endif; ?>
			<?php if($paymentlinks->count() == 0): ?>
			  <div class="container">
			    <div class="card ">
			      <div class="header">
			        <h2><i class="zmdi zmdi-alert-circle-o "></i> <strong class="">Info</strong></h2>
			          <ul class="header-dropdown">  
			              <li class="remove">
			                  <a role="button" class="boxs-close "><i class="zmdi zmdi-close "></i></a>
			              </li>
			          </ul>
			      </div>
			      <div class="body block-header">
			          <div class="row">
			              <div class="col">
			                  <p class=""><strong> <?php echo e(__('Your account is Fresh and New !')); ?> </strong> <br><?php echo e(__('Start by requesting money from Friends and Businesses . Create your first Payment Link ')); ?></p>
                        <p>
                           <a  href="#largePaymentLinkFormModal" data-toggle="modal" data-target="#largePaymentLinkFormModal"  class="btn btn-primary bg-teal float-right"><?php echo e(__('Request Payment')); ?></a>
                        </p>
			              </div>   
			          </div>
			      </div>
			    </div>
			  </div>
			<?php endif; ?>
        
        
      </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
<script>

$(document).ready(function(){

  var card_fee = $('#card_fee');
  var total_fee = $('#total_card_creation_amount');
  var card_amount = $('#card_amount');
  var calc_fee = 0;
  var card_form_error = false ;

  // containers

  var card_fees = $('#card_fees');
  var pay_card_button = $('#pay_card_button');
  var errors = $('#errors');

  card_amount.on('input',function(event){

      if(isNaN(event.target.value) === false ){
        card_form_error = false;
      }else {
        card_form_error = true;
      }

      if(event.target.value  < <?php echo e(setting('cards.vt_min')); ?> || event.target.value > <?php echo e(setting('cards.vt_max')); ?> ){
        card_form_error = true;
      }

      if(card_form_error == true ){
       card_fees.addClass('d-none');
       pay_card_button.addClass('d-none');
       errors.removeClass('d-none');
      } 

      if(card_form_error == false ) {
         card_fees.removeClass('d-none');
       pay_card_button.removeClass('d-none');
       errors.addClass('d-none');
      }

      calc_fee = <?php echo e(setting('cards.vt_fixed_fee')); ?> + ( ( event.target.value * <?php echo e(setting('cards.vt_fercentage_fee')); ?>)  / 100 );
      total_calc_fee = (event.target.value * 1 ) + calc_fee; 
      card_fee.text(calc_fee.toFixed(2));
      total_fee.text(total_calc_fee.toFixed(2));
  });
});


</script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
  <?php echo $__env->make('partials.footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>