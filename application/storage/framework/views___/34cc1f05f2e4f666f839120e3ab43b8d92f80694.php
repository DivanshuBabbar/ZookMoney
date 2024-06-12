
<?php $__env->startSection('content'); ?>
<div class="row">
    <?php echo $__env->make('partials.sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <div class="col-md-9 " style="padding-right: 0" id="#sendMoney">
      <?php if(isset($error)): ?>
        <div class="alert alert-danger"><?php echo e($error); ?></div>
      <?php endif; ?>
      <?php if(isset($succes)): ?>
        <div class="alert alert-success"><?php echo e($succes); ?></div>
      <?php endif; ?>
      <?php echo $__env->make('flash', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <div class="card">
            <h5 class="card-header"><?php echo app('translator')->getFromJson('Recharge Mobile'); ?></h5>
            <div class="card-body bg-white">
                <form action="<?php echo e(route('buyAirtimeRequest',app()->getLocale())); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" value="<?php echo isset($currency_code) ? $currency_code:''; ?>" name="currency_code">
                    <div class="row">
                        <div class="col-sm-6">
                            
                            <div class="form-group">
                               <p style="color:black;">  <label>Choose Countrydd</label></p>
                                <select class="form-control country" required="" name="country">
                                    <option value="">--choose--</option>
                                    <?php
                                        if(!empty($countries)){
                                            foreach($countries as $key=>$row) { ?>
                                                <option data-currency_code="<?php echo isset($row->currencyCode) ? $row->currencyCode:''; ?>" <?php echo (isset($country_code) && $country_code == $row->isoName) ? 'selected':''; ?> value="<?php echo isset($row->isoName) ? $row->isoName:''; ?>">
                                                    <?php echo isset($row->name) ? $row->name:''; ?>
                                                </option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <p style="color:black;"> <label>Operator</label></p>
                                <select class="form-control operator_id" required="" name="operator_id">
                                     <option value="">--choose--</option>
                                    <?php
                                        if(!empty($operators)){
                                            foreach($operators as $key=>$row) { ?>
                                                <option data-price="<?php echo isset($row->minAmount) ? $row->minAmount:''; ?>" value="<?php echo isset($row->operatorId) ? $row->operatorId:''; ?>">
                                                    <?php echo isset($row->name) ? $row->name:''; ?>
                                                </option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                               <p style="color:black;"> <label>Phone Number</label></p>
                                <input type="text" class="form-control phone_number" required="" name="phone_number">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                               <p style="color:black;"> <label>Amount in USD</label></p>
                                <input type="text" class="form-control amount" required="" name="amount">
                            </div>
                        </div>
                    </div>
                    <div class="row" style="margin-top:50px">
                        <div class="col-sm-3">
                            <button type="submit" class="btn btn-info">Recharge</button>
                        </div>
                    </div>
                </form>
            </div>
           <div class="card">
              
        </div>
    </div>
</div>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded',function(){
        $('body').on('change','.country',function(){
            var selected_country_code = $(this).find(':selected').val();
            var currency_code = $(this).find(':selected').attr('data-currency_code');
            window.location.href='<?php echo e(url("/")."/".app()->getLocale()."/getOperatores"); ?>'+'/'+selected_country_code+'/'+currency_code;
        });
        $('body').on('change','.operator_id',function(){
            var selected_operator_price = $(this).find(':selected').attr('data-price');
            // $('body').find('.amount').val(selected_operator_price);
        })
    },false);
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>