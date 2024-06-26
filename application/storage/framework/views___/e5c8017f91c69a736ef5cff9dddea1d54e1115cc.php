<!doctype html>
<html class="no-js" lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title><?php echo e(setting('site.site_name')); ?></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo e(asset('landing/favicon.ico')); ?>" type="image/x-icon"> <!-- Favicon-->    
    <link rel="stylesheet" href="<?php echo e(asset('landing/css/normalize.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('landing/css/bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('landing/css/jquery.fancybox.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('landing/css/flexslider.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('landing/css/styles.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('landing/css/queries.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('landing/css/etline-font.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('landing/bower_components/animate.css/animate.min.css')); ?>">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <script src="<?php echo e(asset('landing/landing/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js')); ?>"></script>
    <style>
        .intro-icon {
            display: block;
            vertical-align: middle;
            padding: 6px 0 0 0;
            width: 100%;
            text-align: center;
        }
        .intro-content {
            display: inline-block;
            width: 100%;
        }
        .btn-white:hover, .btn-white:focus {
            color: #50d38a;
            border-color: #fff;
            background: #fff;
        }
        a.login:hover{
            color: #373D4A !important;
        }
    </style>
</head>
<body id="top" >
    <section class="hero">
        <section class="navigation">
            <header>
                <div class="header-content">
                    <div class="logo"><a href="<?php echo e(url('/')); ?>"><img src="<?php echo e(setting('site.welcome_page_logo_url')); ?>" alt="infinio logo"></a></div>
                    <div class="header-nav">
                        <nav>
                            <ul class="primary-nav hidden">
                                <li><a href="#features">Assets</a></li>
                                <li><a href="#assets">Features</a></li>
                                <li><a href="#demo">Demo</a></li>                                
                            </ul>
                                
                             <?php if(Route::has('login')): ?>
                            <ul class="member-actions">
                                    <?php if(Auth::check()): ?>
                                        <li><a href="<?php echo e(route('logout', app()->getLocale())); ?>" class="login" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><?php echo e(__('Logout')); ?> <form id="logout-form" action="<?php echo e(route('logout',  app()->getLocale())); ?>" method="POST" style="display: none;">
                                        <?php echo csrf_field(); ?>
                                    </form></a></li>
                                     <?php if(Auth::user()->role_id == 1): ?>
                                             <li><a href="<?php echo e(url('/admin/dashboard')); ?> " class="login"><?php echo e(('Admin')); ?></a></li>
                                        <?php endif; ?>
                                        <li><a href="<?php echo e(url( app()->getLocale(),'home')); ?> " class="btn-white btn-small"><?php echo e(('My')); ?> <?php echo e(setting('site.site_name')); ?></a></li>

                                    <?php else: ?>
                                        <li><a href="<?php echo e(url( app()->getLocale(), 'register')); ?>" class="login"><?php echo e(__('Register')); ?></a></li>
                                        <li><a href="<?php echo e(url( app()->getLocale(), 'login')); ?>" class="btn-white btn-small"><?php echo e(__('Log in')); ?></a></li>
                                    <?php endif; ?>
                            </ul>
                            <?php endif; ?>
                        </nav>
                    </div>
                    <div class="navicon">
                        <a class="nav-toggle" href="#"><span></span></a>
                    </div>
                </div>
            </header>
        </section>
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="hero-content text-center">
                        <h1><?php echo e(setting('site.site_name')); ?>, the smart choice for your business.</h1>
                        <h2>Sell using your countries currency and cryptocurrency.</h2>
                        <p class="intro">Pay in a snap with the easy and elegant interface which gives you an outstanding experience.<br>
                            Don't believe us? Take a tour on your on and don't miss a perk.</p>
                            <?php if(Auth::check()): ?>
                                <a href="<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/home" class="btn btn-fill btn-large btn-margin-right">dashboard</a>
                            <?php else: ?>
                               <a href="<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/login" class="btn btn-large btn-margin-right">Get Started</a>
                            <?php endif; ?>
                        
                        <!-- <a href="../html/dark/index.html" target="_blank" class="btn btn-accent btn-large btn-margin-right">Dark Version</a>
                        <a href="../html/left-menu/index.html" target="_blank" class="btn btn-accent btn-large">Left Sidebar</a>
                    </div> -->
                </div>
            </div>
        </div>
        <div class="down-arrow floating-arrow"><a href="#"><i class="fa fa-angle-down"></i></a></div>
    </section>
    <section class="intro section-padding">
        <div class="container">
            <div class="row mb-5" style="margin-bottom: 80px;">
                <div class="col-lg-12 text-center mb-5">
                    <h2 class="mb-5 pb-5">Better for you and your customers</h2>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-md-4 intro-feature">
                    <div class="intro-icon">
                        <span data-icon="&#xe033;" class="icon"></span>
                    </div>
                    <div class="intro-content">
                        <h5 class="text-center">Customer Support.</h5>
                        <p> We’re here to help you and your customers with anything, from setting up your business account to Seller Protection and queries with transactions.</p>
                        
                    </div>
                </div>                
                <div class="col-md-4 intro-feature">
                    <div class="intro-icon">
                        <span data-icon="&#xe046;" class="icon"></span>
                    </div>
                    <div class="intro-content last">
                        <h5 class="text-center">Quicker and simpler access to funds.</h5>
                        <p>Payments you receive go to your <?php echo e(setting('site.site_name')); ?> Balance in moments, and you can withdraw funds to your bank account.</p>
                    </div>
                </div>
                <div class="col-md-4 intro-feature">
                    <div class="intro-icon">
                        <span data-icon="&#xe030;" class="icon"></span>
                    </div>
                    <div class="intro-content">
                        <h5 class="text-center">Sell on your website. with your currency</h5>
                        <p>Accept payments from customers in unlimited currencies or cryptocurencies and build markets without the hassle of accepting foreign cards.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="features section-padding" id="features">
        <div class="container">
            <div class="row">
                <div class="col-md-5 col-md-offset-7">
                    <div class="feature-list">
                        <h3><?php echo e(setting('site.site_name')); ?> will drive your product forward</h3>
                        <p>Present your product, start up, or portfolio in a beautifully modern way. Turn your visitors in to clients.</p>
                        <ul class="features-stack">
                            <li class="feature-item">
                                <div class="feature-icon">
                                    <span data-icon="&#xe03e;" class="icon"></span>
                                </div>
                                <div class="feature-content">
                                    <h5>Responsive Design</h5>
                                    <p><?php echo e(setting('site.site_name')); ?> is universal and will look smashing on any device.</p>
                                </div>
                            </li>
                            <li class="feature-item">
                                <div class="feature-icon">
                                    <span data-icon="&#xe040;" class="icon"></span>
                                </div>
                                <div class="feature-content">
                                    <h5>User Design</h5>
                                    <p><?php echo e(setting('site.site_name')); ?> takes advantage of common design patterns, allowing for a seamless experience for users of all levels.</p>
                                </div>
                            </li>
                            <li class="feature-item">
                                <div class="feature-icon">
                                    <span data-icon="&#xe03c;" class="icon"></span>
                                </div>
                                <div class="feature-content">
                                    <h5>Clean and Re-Usable code</h5>
                                    <p>Download and re-use the <?php echo e(setting('site.site_name')); ?> open source code for any other project you like.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="device-showcase">
            <div class="devices">
                <div class="ipad-wrap wp1"></div>
                <div class="iphone-wrap wp2"></div>
            </div>
        </div>
        <div class="responsive-feature-img"><img src="<?php echo e(asset('landing/img/devices.png')); ?>" alt="responsive devices"></div>
    </section>
    <section class="features-extra section-padding" id="assets">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <div class="feature-list">
                        <h3>Main Features</h3>
                        <p>The best script for building the modern web fintech application.</p>
                        <ul class="main_features">
                            <li>--  Bootstrap 4 Stable</li>
                            <li>--  E-commerce</li>
                            <li>--  Unlimited ( Withdrawal / Deposit ) Methods</li>
                            <li>--  ( Send / Receive ) Money</li>
                            <li>--  ( Create / Load ) Vouchers</li>
                            <li>--  6 Color Skins</li>
                            <li>--  Currency Exchange</li>
                            <li>--  Unlimited Currencies</li>
                            <li>--  Earn by transaction fees</li>
                            <li>--  Crossbrowser</li>
                            <li>--  User Roles</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="macbook-wrap wp3"></div>
        <div class="responsive-feature-img"><img src="<?php echo e(asset('landing/img/macbook-pro.png')); ?>" alt="responsive devices"></div>
    </section>
    <section class="hero-strip section-padding">
        <div class="container">
            <div class="col-md-12 text-center">
                <h2>Why spend lots of time and money <br>on design and development when we have created one for you.</h2>
                <a href="javascript:history.go(-1)" class="btn btn-ghost btn-accent btn-large">Buy Now!</a>                
            </div>
        </div>
    </section>
    <section class="to-top">
        <div class="container">
            <div class="row">
                <div class="to-top-wrap">
                    <a href="#top" class="top"><i class="fa fa-angle-up"></i></a>
                </div>
            </div>
        </div>
    </section>
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <div class="footer-links">
                        <ul class="footer-group">
                             <?php if(Auth::check()): ?>
                                <li> 
                                    <a href="<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/home" >dashboard</a>
                                </li>
                            <?php else: ?>
                               <a href="<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/login" >Get Started</a>
                            <?php endif; ?>
                            
                        </ul>
                        <p>Copyright © 2018 <a href="#"><?php echo e(setting('site.site_name')); ?></a></p>
                    </div>
                </div>
                <div class="social-share">
                    <p>Share <?php echo e(setting('site.site_name')); ?> with your friends</p>
                    <a href="#" class="twitter-share"><i class="fa fa-twitter"></i></a>
                    <a href="#" class="facebook-share"><i class="fa fa-facebook"></i></a>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="<?php echo e(asset('landing/js/vendor/jquery-1.11.2.min.js')); ?>"><\/script>')</script>    
    <script src="<?php echo e(asset('landing/js/jquery.fancybox.pack.js')); ?>"></script>
    <script src="<?php echo e(asset('landing/js/vendor/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('landing/js/scripts.js')); ?>"></script>
    <script src="<?php echo e(asset('landing/js/jquery.flexslider-min.js')); ?>"></script>
    <script src="<?php echo e(asset('landing/bower_components/classie/classie.js')); ?>"></script>
    <script src="<?php echo e(asset('landing/bower_components/jquery-waypoints/lib/jquery.waypoints.min.js')); ?>"></script>
    
</body>
</html>

