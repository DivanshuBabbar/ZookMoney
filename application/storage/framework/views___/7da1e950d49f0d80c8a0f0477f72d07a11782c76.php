

<?php $__env->startSection('content'); ?>

    <div class="row">
        <?php echo $__env->make('partials.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
      <div class="col ">
        <?php echo $__env->make('partials.flash', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <div class="card bg">
          <div class="header">
            <h2><strong>  <?php echo e(__('Get a virtual card')); ?> </strong></h2>
          </div>
          <div class="body">
            <p>
              <?php echo e(__('Get USD virtual cards that work globally. Pay staff, interns, and recurring service providers instantly (at any time) so they can access salaries even on weekends.')); ?>

            </p>
            <a  href="#largeVirtualCardFormModal" data-toggle="modal" data-target="#largeVirtualCardFormModal"  class="btn btn-primary btn-simple"><?php echo e(__('Get your Virtual Card')); ?></a>
          </div>
        </div>

        <?php if( $virtualcards->total() > 0 ): ?>
        <div class="card bg-light">
          <div class="header">
            <h2><?php echo e(__('Virtualcard list')); ?></h2>
          </div>
          <div class="body">
            <div class="row">
            <?php $__currentLoopData = $virtualcards; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $virtualcard): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

              <?php if(isset($virtualcard->detail) and isset($virtualcard->detail->masked_pan)): ?>
              <div class="col-lg-4  col-sm-6 col-xs-12 col-md-4">
                <div class="card">
                  <div class="header">
                   <h2> <strong><?php echo e($virtualcard->detail->masked_pan); ?></strong></h2>
                   <ul class="header-dropdown">
                        <li class="dropdown "> <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> <i class="icon icon-credit-card"></i> </a>  
                        </li>
                    </ul>
                  </div>
                  <div class="body">
                    <div class="row">
                      <div class="col">
                        <span><?php echo e(__('Card Number')); ?></span><br>
                        <strong><?php echo e($virtualcard->detail->pan); ?></strong>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col mt-2">
                        <span><?php echo e(__('Expires')); ?></span><br>
                        <strong><?php echo e($virtualcard->detail->expiration); ?></strong>
                      </div>
                      <div class="col mt-2">
                        <span><?php echo e(__('CVV')); ?></span><br>
                        <strong><?php echo e($virtualcard->detail->cvv); ?></strong>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col mt-2">
                        <span><?php echo e(__('Status')); ?></span>
                        <?php if($virtualcard->status == 0): ?>
                          <small class="badge badge-danger"><?php echo e(__('Inactive')); ?></small>
                        <?php else: ?>
                          <small xlass="badge badge-success"><?php echo e(__('Active')); ?></small>
                        <?php endif; ?>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
          </div>
          <?php if($virtualcards->LastPage() != 1): ?>
            <div class="footer">
                <?php echo e($virtualcards->links()); ?>

            </div>
          <?php else: ?>
          
          <?php endif; ?>
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