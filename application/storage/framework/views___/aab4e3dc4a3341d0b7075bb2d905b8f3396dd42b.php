
<?php $__env->startSection('content'); ?>

<div class="row">
  <?php echo $__env->make('partials.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <div class="col-md-9 " style="padding-right: 0">
        <?php echo $__env->make('flash', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <div class="card">
            <div class="header">
                <h2><strong><?php echo e(__('Add a new virtual card')); ?></strong></h2>
            </div>
            <div class="body">
                <form method="POST" action="<?php echo e(route('vcard.store',  app()->getLocale())); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="row mb-2">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <label>
                                   <strong>
                                       <?php echo e(__('Card creation fee : ')); ?><?php echo e($fee); ?>$</strong>
                                </label>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                                <label>
                                   <strong>
                                       <?php echo e(__('Minimum Pre-fund amount : 5$ ')); ?></strong>
                                </label>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 <?php echo e($errors->has('amount') ? ' has-error' : ''); ?>">
                            <label for="name">
                                <?php echo e(__('Amount to pre-fund the card')); ?>

                            </label>
                            <div class="input-group">
                                <span class="input-group-addon">$</span>
                                <input type="number" class="form-control" id="amount" name="amount" 
                                value="5" required="">
                            </div>
                            <?php if($errors->has('amount')): ?>
                                <span class="help-block text-danger">
                                    <strong><?php echo e($errors->first('amount')); ?></strong>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 <?php echo e($errors->has('holder') ? ' has-error' : ''); ?>">
                            <label for="holder">
                                <?php echo e(__('Card holder\'s name')); ?>

                            </label>
                            <input type="text" class="form-control" id="holder" name="holder" 
                                required="">
                            <?php if($errors->has('holder')): ?>
                                <span class="help-block text-danger">
                                    <strong>
                                        <?php echo e($errors->first('holder')); ?>      </strong>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="row">
                       <div class="col">
                                    <button type="submit" class="btn btn-primary float-right" id='submit'>
                                        <?php echo e(__('Add')); ?>

                                    </button>
                                    <div id="spinner" style="display:none;">
                                      <i class="fa fa-spinner fa-spin"></i> Processing...
                                    </div>
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
  <script>
    (function ($) {
        "use strict";
        $(document).ready(function () {
            $('body').on('submit','#submit_form',function(){
                $('body').find('#submit').hide();
                $('body').find("#spinner").show();
            });
            // $("form").on("submit",function(event){
            //     $("#submit").attr("disabled", true);
            //     $("#spinner").show();
            // });
           
        });
    })(jQuery);
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>