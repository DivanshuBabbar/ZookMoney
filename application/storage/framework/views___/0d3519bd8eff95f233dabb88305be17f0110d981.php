

<?php if(session()->has('flash_message')): ?>

<div class="alert alert-<?php echo e(session()->get('flash_message_level')); ?> alert-dismissible fade show mt-2">
    
    <?php if(session()->get('flash_message_level') == 'danger'): ?>
    <a href="javascript:void(0);" class="alert-link"><strong>Error! </strong></a>  
    <?php elseif(session()->get('flash_message_level') == 'info'): ?>
    <a href="javascript:void(0);" class="alert-link"><strong>Info!</strong></a> 
    <?php elseif(session()->get('flash_message_level') == 'success'): ?>
    <a href="javascript:void(0);" class="alert-link"><strong>Success!</strong></a> 
    <?php elseif(session()->get('flash_message_level') == 'warning'): ?>
    <a href="javascript:void(0);" class="alert-link"><strong>Warning!</strong></a> 
    <?php endif; ?>
    <span> &nbsp;&nbsp;&nbsp;<?php echo session()->get('flash_message'); ?> </span>

    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>

</div>


<?php endif; ?>