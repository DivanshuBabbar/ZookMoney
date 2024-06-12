
<?php $__env->startSection('content'); ?>

<div class="row">
  <?php echo $__env->make('partials.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <style type="text/css">
    body {
      font-family: Sans-Serif;
    }

    #start-payment-button {
        cursor: pointer;
        position: relative;
        background-color: blueviolet;
        color: #fff;
        max-width: 30%;
        padding: 10px;
        font-weight: 600;
        font-size: 14px;
        border-radius: 10px;
        border: none;
        transition: all .1s ease-in;
    }
  </style>
  <div class="col-lg-9 col-md-12">
    <div class="row">
      <div class="col">
        <div class="card" >
          <div class="header">
              <h2><strong><?php echo e(__('Deposit Request Form')); ?></strong></h2>
          </div>
          <div class="body">
            <div class="row">
              <div class="col-sm-6">
                  <script src="https://checkout.flutterwave.com/v3.js"></script>
                  <form>
                    <div>
                      Your order is ₦<?php echo e($amount); ?>

                    </div>
                    <button type="button" id="start-payment-button" onclick="makePayment()">Pay Now</button>
                  </form>
                  <script>
                    function makePayment() {
                      FlutterwaveCheckout({
                        public_key: "FLWPUBK-8a",
                        tx_ref: "<?php echo e($tx_ref); ?>",
                        amount: "<?php echo e($amount); ?>",
                        currency: "NGN",
                        payment_options: "<?php echo e($transferMethod->how_to_deposit); ?>",
                        redirect_url: "https://phpwallet.codesviral.com/en/flutteraddredirect",
                        meta: {
                          consumer_id: "<?php echo e($userDetails->id); ?>",
                        },
                        customer: {
                          email: "<?php echo e($userDetails->email); ?>",
                          phone_number: "<?php echo e($userDetails->phonenumber); ?>",
                          name: '<?php echo e($userDetails->name); ?>',
                        },
                        onclose: function(incomplete) {
                          if(incomplete === true) 
                          {
                            window.location.reload();
                          }
                        }
                      });
                    }
                  </script>
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
$( "#deposit_method" )
  .change(function () {
    $( "#deposit_method option:selected" ).each(function() {
      window.location.replace("<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/addcredit/"+$(this).val());
    });
  });
  $( "#deposit_currency" )
  .change(function () {
    $( "#deposit_currency option:selected" ).each(function() {
      window.location.replace("<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/wallet/"+$(this).val());
    });
  })
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>