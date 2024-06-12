<aside id="leftsidebar" class="sidebar">
    <div class="container">
        <div class="row clearfix">
            <div class="col-12">
                <div class="menu">
                    <ul class="list">
                        <?php if(auth()->guard()->guest()): ?>
                            <li><a class="nav-link" href="<?php echo e(route('login', app()->getLocale())); ?>"><?php echo e(__('Login')); ?></a></li>
                            <li><a class="nav-link" href="<?php echo e(route('register', app()->getLocale())); ?>"><?php echo e(__('Register')); ?></a></li>
                        <?php else: ?>
                        <li class="header">MAIN</li>
                        
                       
                        <li class="<?php echo e((Route::is('home') ? 'active open' : '')); ?>"> 
                            <a href="<?php echo e(route('home', app()->getLocale())); ?>"><i class=" icon-layers"></i><span><?php echo e(__('Dashboard')); ?></span></a>
                        </li>
                        
                        <li class="<?php echo e((Route::is('sendMoneyForm') ? 'active open' : '')); ?>"> 
                            <a href="<?php echo e(route('sendMoneyForm', app()->getLocale())); ?>"><i class="icon-arrow-right"></i><span><?php echo e(__('Send')); ?></span></a>
                        </li>
                         <li class="<?php echo e((Route::is('vcard') ? 'active open' : '')); ?>"> 
                            <a href="<?php echo e(route('vcard',  app()->getLocale())); ?>"><i class="icon-credit-card"></i><span>
                            <?php echo e(__('Virtual Card')); ?></span></a>
                        </li>
                        <li class="<?php echo e((Route::is('airtime') ? 'active open' : '')); ?>"> 
                            <a href="<?php echo e(route('airtime',  app()->getLocale())); ?>"><i class="icon-speedometer"></i><span>
                            <?php echo e(__('Top-Up Mobile')); ?></span></a>
                        </li>
                        <li class="<?php echo e((Route::is('paymentlinks') ? 'active open' : '')); ?>"> 
                            <a href="<?php echo e(route('paymentlinks',  app()->getLocale())); ?>"><i class="icon-link"></i><span>
                            <?php echo e(__('Payment link')); ?></span></a>
                        </li>
                        
                        
                        <li class="<?php echo e((Route::is('requestMoneyForm') ? 'active open' : '')); ?>"> 
                            <a href="<?php echo e(route('requestMoneyForm',  app()->getLocale())); ?>"><i class="icon-arrow-left"></i><span><?php echo e(__('Request')); ?></span></a>
                        </li>
                        
                       
                        
                         <li class="<?php echo e((Route::is('escrow') ? 'active open' : '')); ?>"> 
                            <a href="<?php echo e(route('escrow',  app()->getLocale())); ?>"><i class="icon-folder-alt"></i><span>
                            <?php echo e(__('Escrow system')); ?></span></a>
                        </li>
                         <li class="<?php echo e((Route::is('exchange_currency.list') ? 'active' : '')); ?>"> 
                            <a href="<?php echo e(route('exchange_currency.list', app()->getLocale())); ?>"><i class="icon-arrow-right"></i>
                                <span><?php echo e(__('Exchange')); ?></span>
                            </a>
                        </li>
                        
                         
                         <li>
                            <a href="javascript:void(0);" class="menu-toggle waves-effect waves-block"><i class="icon-bar-chart"></i><span><?php echo e(__('P2P Trade')); ?></span></a>
                            <ul class="ml-menu" style="z-index:99999 !important;">
                                <li><a href="<?php echo e(route('offerbook',  app()->getLocale())); ?>" class=" waves-effect waves-block"><?php echo e(__('All Offers')); ?></a></li>
                                <li><a href="<?php echo e(route('mybook',  app()->getLocale())); ?>" class=" waves-effect waves-block"><?php echo e(__('My Trades')); ?></a></li>
                                 <li><a href="<?php echo e(route('myclosed',  app()->getLocale())); ?>" class=" waves-effect waves-block"><?php echo e(__('Completed Trades')); ?></a></li>
                            </ul>
                        </li>
                        <li class="<?php echo e((Route::is('my_tickets') ? 'active open' : '')); ?>"> 
                            <a href="<?php echo e(route('my_tickets',  app()->getLocale())); ?>"><i class="icon-speedometer"></i><span>
                            <?php echo e(__('Support Ticket')); ?></span></a>
                        </li>
                        <li class="<?php echo e((Route::is('mymerchants') ? 'active open' : '')); ?>"> 
                            <a href="<?php echo e(route('mymerchants',  app()->getLocale())); ?>"><i class="icon-speedometer"></i><span>
                            <?php echo e(__('Api docs')); ?></span></a>
                        </li>
                        <li class="<?php echo e((Route::is('mymerchants') ? 'active open' : '')); ?>"> 
                            <a href="<?php echo e(route('mydeposits',  app()->getLocale())); ?>"><i class="icon-speedometer"></i><span>
                            <?php echo e(__('My Deposits')); ?></span></a>
                        </li>
                        <li class="<?php echo e((Route::is('mymerchants') ? 'active open' : '')); ?>"> 
                            <a href="<?php echo e(route('withdrawal.index',  app()->getLocale())); ?>"><i class="icon-speedometer"></i><span>
                            <?php echo e(__('My Withdrawals')); ?></span></a>
                        </li>
                        <?php if(Auth::user()->role_id != 1): ?>
                        <li class="<?php echo e((Route::is('my_vouchers') ? 'active open' : '')); ?>"> 
                            <a href="<?php echo e(url( app()->getLocale().'/')); ?>/my_vouchers"><i class="icon-speedometer"></i><span>
                            <?php echo e(__('Vouchers')); ?></span></a>
                        </li>
                        <?php endif; ?>

                       
                        
                        <?php endif; ?>               
                    </ul>
                </div>
            </div>
        </div>
    </div>
</aside>

<!-- Right Sidebar -->
<aside id="rightsidebar" class="right-sidebar">
    <div class="slim_scroll">
        <div class="card">
            <h6>Demo Skins</h6>
            <ul class="choose-skin list-unstyled">
                <li data-theme="purple">
                    <div class="purple"></div>
                </li>                   
                <li data-theme="blue">
                    <div class="blue"></div>
                </li>
                <li data-theme="cyan">
                    <div class="cyan"></div>
                </li>
                <li data-theme="green" class="active">
                    <div class="green"></div>
                </li>
                <li data-theme="orange">
                    <div class="orange"></div>
                </li>
                <li data-theme="blush">
                    <div class="blush"></div>
                </li>
            </ul>
        </div>
        <div class="card theme-light-dark">
            <h6>Left Menu</h6>
            <button class="btn btn-default btn-block btn-round btn-simple t-light">Light</button>
            <button class="btn btn-default btn-block btn-round t-dark">Dark</button>
        </div> 
    </div>
</aside>