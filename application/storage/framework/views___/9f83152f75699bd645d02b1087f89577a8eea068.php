<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo $__env->yieldContent('page'); ?> - <?php echo e(setting('site.site_name')); ?></title>

    <!-- Styles -->
    <!-- Fonts -->
    

    <!-- Styles --> 
    
    
   

    <link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/jquery-jvectormap-2.0.3.min.css')); ?>"/>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/morris.min.css')); ?>" />
    <!-- Custom Css -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/main.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/color_skins.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/bootstrap-select.min.css')); ?>">
    

    <style type="text/css">
    [v-cloak]{
        display:none;
    }
    .jqstooltip { position: absolute;left: 0px;top: 0px;visibility: hidden;background: rgb(0, 0, 0) transparent;background-color: rgba(0,0,0,0.6);filter:progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000);-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";color: white;font: 10px arial, san serif;text-align: left;white-space: nowrap;padding: 5px;border: 1px solid white;box-sizing: content-box;z-index: 10000;}
    .jqsfield { color: white;font: 10px arial, san serif;text-align: left;}
    .bitcoin .body {position: absolute;word-break: break-all;}
    .remove{cursor: pointer;}
    .top_navbar{border-bottom: none }
    .navbar-nav>li>a .label-count{position: unset;}
    .menu_dark .sidebar {box-shadow: none !important;}
    <?php if (app()["auth"]->check() && app()["auth"]->user()->isImpersonated()): ?>
    .top_navbar{background:#fff;}
    section.content::before{background:#fff;}
    .menu_dark .sidebar {background: #fff;box-shadow: none !important;}
    .navbar-nav>li>a .label-count {background-color: #50d38a;color: #fff;}
    .navbar-logo .navbar-brand span {color: #50d38a;}
    <?php endif; ?>
    <?php echo $__env->yieldContent('styles'); ?>
    </style>


    <?php echo $__env->make('partials.footerstyles', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <script src="<?php echo e(asset('js/vue.min.js')); ?>"></script>
    
</head>
<body class="<?php echo e(setting('site.color_theme')); ?> menu_dark" id="app">
<?php if(auth()->guard()->check()): ?>
<div class="modal fade" id="walletModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content bg-green">
            <div class="modal-header">
                
            </div>
            <div class="modal-body"> 

                    <div class="row ">
                <?php if(count(Auth::user()->wallets())): ?>
                    <?php $__currentLoopData = Auth::user()->wallets(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $someWallet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <a href="<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/wallet/<?php echo e($someWallet->id); ?>">
                            <div class="card info-box-2" style="cursor: pointer;min-height: auto;margin-bottom: 10px">
                                <div class="body mb-0">
                                    <ul class="follow_us list-unstyled mb-0">
                                        <li class="offline">
                                                <div class="media mb-0">
                                                    <img class="media-object " src="<?php echo e($someWallet->currency->thumb); ?>" alt="">
                                                    <div class="media-body">
                                                        <span class="name"><?php echo e($someWallet->currency->name); ?></span>
                                                        <span class="message"><?php echo e(\App\Helpers\Money::instance()->value($someWallet->amount, $someWallet->currency->symbol, $someWallet->currency->is_crypto)); ?></span>
                                                        <span class="badge badge-outline status"></span>
                                                    </div>
                                                </div>                         
                                        </li>                        
                                    </ul>
                                </div>
                            </div>
                            </a>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
                 </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline btn-primary btn-round waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="largeModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content  bg-green">
            <div class="modal-header">
                <h4 class="title" id="largeModalLabel"><?php echo e(__('Select the wallet currency')); ?></h4>
            </div>
            <div class="modal-body"> 
                <div class="row clearfix">
                <?php $__currentLoopData = \App\Models\Currency::orderby('is_crypto')->paginate(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-4 col-md-6 col-sm-12">
                            <a href="<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/wallet/create/<?php echo e($currency->id); ?>">
                            <div class="card info-box-2" style="cursor: pointer;min-height: auto;margin-bottom: 10px">
                                <div class="body mb-0">
                                    <ul class="follow_us list-unstyled mb-0">
                                        <li class="offline">
                                                <div class="media mb-0">
                                                    <img class="media-object " src="<?php echo e($currency->thumb); ?>" alt="">
                                                    <div class="media-body">
                                                        <span class="name"><?php echo e($currency->name); ?></span>
                                                        <span class="message"><?php echo e(\App\Helpers\Money::instance()->value(0, $currency->symbol, $currency->is_crypto)); ?></span>
                                                        <span class="badge badge-outline status"></span>
                                                    </div>
                                                </div>                         
                                        </li>                        
                                    </ul>
                                </div>
                            </div>
                            </a>
                        </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-round waves-effect" data-dismiss="modal">CLOSE</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="largePaymentLinkFormModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="title" id="largeModalLabel"><?php echo e(__('Payment Request')); ?></h6>
            </div>
            <div class="modal-body"> 
                <form method="POST" action="<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/paymentlink">
                <?php echo e(csrf_field()); ?>

                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="text" class="form-control" name="name" id="link_name" placeholder="Name of your link">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="text" class="form-control" name="amount" id="link_amount" placeholder="Payment request amount">
                            <span class="form-text ml-2"><small></small></span>
                        </div>
                    </div>
                    <div class="col-md-12">
                         <div class="card " style="box-shadow: none !important; border: 1px solid #e3e3e3;">
                            <div class="body" style="padding: 0">
                                <div class="form-group ">
                                    <textarea class="form-control" rows="5" id="description" name="description" placeholder="Tell your customer why you are requesting this payment" required=""></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" >
                    <div class="col">
                         <input type="submit"  class="btn btn-primary bg-blue btn-round  btn-block" value="<?php echo e(__('Create Payment Link')); ?>"//>
                    </div>
                </div>
                </form>

            </div>
            <div class="modal-footer">
               
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="largeVirtualCardFormModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="title" id="largeModalLabel"><?php echo e(__('New Virtual Card')); ?></h6>
            </div>
            <div class="modal-body"> 
                <form method="POST" action="<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/virtualcard">
                <?php echo e(csrf_field()); ?>

                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="text" class="form-control" name="amount" id="card_amount" placeholder="Card amount">
                        </div>
                    </div>
                </div>
                <div class="row d-none" id="errors">
                    <div class="col mt-3">
                        <ul>
                            <li class="text-danger">
                                <?php echo e(__('The amount must be between USD')); ?> <?php echo e(setting('cards.vt_min')); ?> | <?php echo e(setting('cards.vt_max')); ?>

                            </li>
                            <li class="text-danger">
                                The amount must be a valid number
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row" id="card_fees">
                    <div class="col mt-3">
                        <div class="card " style="box-shadow: none !important; border: 1px solid #50d38a;">
                            <div class="body">
                                <div class="row">
                                    <div class="col">
                                        <p class="">
                                           <strong> <?php echo e(__('Card Creation fee :')); ?></strong> <span class="text-primary"> USD </span>  <span id="card_fee"  class="text-primary"> 0.00</span>
                                        </p>
                                    </div>
                                </div>
                                 <div class="row">
                                    <div class="col">
                                        <p class="">
                                           <strong><?php echo e(__('Total :')); ?> </strong> <span  class="text-primary"> USD </span>  <span id="total_card_creation_amount"  class="text-primary"> 0.00</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" id="pay_card_button">
                    <div class="col">
                         <input type="submit"  class="btn btn-primary bg-blue btn-round  btn-block" value="<?php echo e(__('Pay')); ?>"//>
                    </div>
                </div>
                </form>

            </div>
            <div class="modal-footer">
               
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<div class="page-loader-wrapper">
    <div class="loader">
        <div class="m-t-30"><img class="zmdi-hc-spin" src="<?php echo e(asset('assets/images/logo.svg')); ?>" width="48" height="48" alt="sQuare"></div>
        <p>Please wait...</p>        
    </div>
</div>
<!-- Overlay For Sidebars -->
<div class="overlay"></div>
<?php echo $__env->make('layouts.topnavbar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php echo $__env->make('layouts.aside', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
<section class="content">
    <div class="container">
        <div class="row cleatfix">
            <div class="col-lg-12">
                 <?php echo $__env->yieldContent('pre_content'); ?>
            </div>
        </div>
        <?php if(auth()->guard()->check()): ?>
        <?php if(Route::is('show.transfermethods') == false and Route::is('show.createwalletform') == false): ?>
        <div class="row clearfix">
            <div class="col-lg-12">
                <div class="card">
                   
                    <div class="body block-header">
                        <div class="row">
                            <div class="col">
                                <h2 style="padding-top: 10px"><?php echo e(__('Welcome back')); ?> <?php echo e(Auth::user()->name); ?> ! </h2>
                               
                            </div>            
                            <div class="col text-right">
                                <a href="#largeModal" data-toggle="modal" data-target="#largeModal" class="btn btn-primary btn-round bg-blue float-right  m-l-10"><?php echo e(__('Create a Wallet')); ?></a>
                                
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php endif; ?>
        <?php echo $__env->yieldContent('content'); ?>
        <?php if(auth()->guard()->check()): ?>
        <?php if(Route::is('show.transfermethods') == false and Route::is('show.createwalletform') == false): ?>
        <div class="row clearfix">
           
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
      <!-- Scripts -->

    <?php echo $__env->yieldContent('footer'); ?>
</section>
    <!-- Jquery Core Js --> 
    <script src="<?php echo e(asset('assets/js/libscripts.bundle.js')); ?>"></script> <!-- Lib Scripts Plugin Js ( jquery.v3.2.1, Bootstrap4 js) --> 
    <script src="<?php echo e(asset('assets/js/vendorscripts.bundle.js')); ?>"></script> <!-- slimscroll, waves Scripts Plugin Js -->
    <script src="<?php echo e(asset('assets/js/morrisscripts.bundle.js')); ?>"></script><!-- Morris Plugin Js -->
    <script src="<?php echo e(asset('assets/js/jvectormap.bundle.js')); ?>"></script> <!-- JVectorMap Plugin Js -->
    <script src="<?php echo e(asset('assets/js/knob.bundle.js')); ?>"></script> <!-- Jquery Knob-->
    <script src="<?php echo e(asset('assets/js/mainscripts.bundle.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/infobox-1.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/index.js')); ?>"></script>
    <?php echo $__env->yieldContent('js'); ?>
    <script src="<?php echo e(asset('assets/js/form-validation.js')); ?>"></script>
</body>
</html>
