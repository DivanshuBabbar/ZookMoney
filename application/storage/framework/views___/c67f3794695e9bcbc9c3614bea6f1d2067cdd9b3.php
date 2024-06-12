<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(setting('site.title')); ?></title>

    <!-- Styles -->
    
    	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap.min.css')); ?>">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js" type="text/javascript"></script>
    <!-- <link rel="stylesheet" href="<?php echo e(asset('assets/css/jquery-jvectormap-2.0.3.min.css')); ?>"/>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/morris.min.css')); ?>" />
    <!-- Custom Css -->
    <!-- <link rel="stylesheet" href="<?php echo e(asset('assets/css/main.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/color_skins.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-select.min.css')); ?>"> -->
   <?php echo $__env->make('paymentlinks.styles', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

</head>
<body class="theme-blue">
    <div id="app">
      
        <div class="container">
        	<div class="row">
		        <div class="col">
		            <?php echo $__env->make('partials.flash', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		        </div>
		    </div>
		    <div class="row justify-content-md-center">
		    <?php echo $__env->make('paymentlinks.paymentForm', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
		    </div>
		</div>
    </div>
    <form id="logout-form" action="<?php echo e(route('logout', app()->getLocale())); ?>" method="POST" style="display: none;">
        <?php echo e(csrf_field()); ?>

    </form>
    <!-- Scripts -->
    <script src="<?php echo e(asset('js/app.js')); ?>"></script>
    
</body>
</html>
