<aside id="leftsidebar" class="sidebar h_menu">
    <div class="container">
        <div class="row clearfix">
            <style type="text/css">
                .ml-menu{
                    z-index:99999 !important;
                }
            </style>
            <div class="col-12">
                <div class="menu">
                    <ul class="list">
                        <?php if(auth()->guard()->guest()): ?>
                            <li><a class="nav-link" href="<?php echo e(route('login', app()->getLocale())); ?>"><?php echo e(__('Login')); ?></a></li>
                            <li><a class="nav-link" href="<?php echo e(route('register', app()->getLocale())); ?>"><?php echo e(__('Register')); ?></a></li>
                        <?php else: ?>
                        <li class="header">MAIN</li>
                        <li class="<?php echo e((Route::is('home') ? 'active open' : '')); ?> <?php echo e((Route::is('withdrawal.index') ? 'active open' : '')); ?> <?php echo e((Route::is('mydeposits') ? 'active open' : '')); ?>"> 
                            <a  href="javascript:void(0);" class="menu-toggle waves-effect waves-block"><i class=" icon-layers"></i><span><?php echo e(__('Transactions')); ?></span></a>
                            <ul class="ml-menu" style="z-index:99999 !important;">
                                <li><a href="<?php echo e(route('home', app()->getLocale())); ?>"><?php echo e(__('All Transactions')); ?></a></li>
                                <li><a href="<?php echo e(route('mydeposits',  app()->getLocale())); ?>"  class=" waves-effect waves-block"><span><?php echo e(__('My Deposits')); ?></span></a></li>                    
                                <li>  <a href="<?php echo e(route('withdrawal.index',  app()->getLocale())); ?>" class=" waves-effect waves-block"><span><?php echo e(__('My Withdrawals')); ?></span></a></li>                    
                            </ul>
                            
                        </li>
                        
                        <li class="<?php echo e((Route::is('sendMoneyForm') ? 'active open' : '')); ?>"> 
                            <a href="<?php echo e(route('sendMoneyForm', app()->getLocale())); ?>"><i class="icon-arrow-right"></i><span><?php echo e(__('Send')); ?></span></a>
                        </li>
                        
                        <li class="<?php echo e((Route::is('paymentlinks') ? 'active open' : '')); ?>"> 
                            <a href="<?php echo e(route('paymentlinks',  app()->getLocale())); ?>"><i class="icon-link"></i><span>
                            <?php echo e(__('Payment link')); ?></span></a>
                        </li>
                        
                        
                        <li class="<?php echo e((Route::is('escrow') ? 'active open' : '')); ?>"> 
                            <a href="<?php echo e(route('escrow',  app()->getLocale())); ?>"><i class="icon-arrow-right"></i><span><?php echo e(__('Escrow')); ?></span></a>
                        </li>
                        
                        
                        <li class="<?php echo e((Route::is('mymerchants') ? 'active open' : '')); ?>"> 
                            <a href="<?php echo e(route('mymerchants',  app()->getLocale())); ?>"><i class="icon-speedometer"></i><span>
                            <?php echo e(__('Integration')); ?></span></a>
                        </li>
                        <li class="<?php echo e((Route::is('vcard') ? 'active open' : '')); ?>"> 
                            <a href="<?php echo e(route('vcard',  app()->getLocale())); ?>"><i class="icon-credit-card"></i><span>
                            <?php echo e(__('Virtual Card')); ?></span></a>
                        </li>
                        <li class="<?php echo e((Route::is('loan') ? 'active open' : '')); ?>"> 
                            <a href="<?php echo e(route('loan',  app()->getLocale())); ?>"><i class="icon-credit-card"></i><span>
                            <?php echo e(__('Apply Loan')); ?></span></a>
                        </li>
                        <li class="<?php echo e((Route::is('loan.list') ? 'active open' : '')); ?>"> 
                            <a href="<?php echo e(route('loan.list',  app()->getLocale())); ?>"><i class="icon-credit-card"></i><span>
                            <?php echo e(__('Manage Loan')); ?></span></a>
                        </li>
                        <li class="<?php echo e((Route::is('stro_account') ? 'active open' : '')); ?>"> 
                            <a href="<?php echo e(route('stro_account',  app()->getLocale())); ?>"><i class="icon-credit-card"></i><span>
                            <?php echo e(__('Auto Bank')); ?></span></a>
                        </li>
                        <li class="<?php echo e((Route::is('stroAirtime') ? 'active open' : '')); ?>"> 
                            <a href="<?php echo e(route('stroAirtime',  app()->getLocale())); ?>"><i class="icon-credit-card"></i><span>
                            <?php echo e(__('Airtime')); ?></span></a>
                        </li>
                        <li class="<?php echo e((Route::is('stroData') ? 'active open' : '')); ?>"> 
                            <a href="<?php echo e(route('stroData',  app()->getLocale())); ?>"><i class="icon-credit-card"></i><span>
                            <?php echo e(__('Direct Data')); ?></span></a>
                        </li>
                        <li class="<?php echo e((Route::is('strotvSubscription') ? 'active open' : '')); ?>"> 
                            <a href="<?php echo e(route('strotvSubscription',  app()->getLocale())); ?>"><i class="icon-credit-card"></i><span>
                            <?php echo e(__('Cable')); ?></span></a>
                        </li>
                        <li class="<?php echo e((Route::is('stroElectricity') ? 'active open' : '')); ?>"> 
                            <a href="<?php echo e(route('stroElectricity',  app()->getLocale())); ?>"><i class="icon-credit-card"></i><span>
                            <?php echo e(__('Electricity')); ?></span></a>
                        </li>
                        <li class="<?php echo e((Route::is('stroEducation') ? 'active open' : '')); ?>"> 
                            <a href="<?php echo e(route('stroEducation',  app()->getLocale())); ?>"><i class="icon-credit-card"></i><span>
                            <?php echo e(__('Waec Pin')); ?></span></a>
                        </li>
                        <li class="<?php echo e((Route::is('stro_bank_transfer') ? 'active open' : '')); ?>"> 
                            <a href="<?php echo e(route('stro_bank_transfer',  app()->getLocale())); ?>"><i class="icon-credit-card"></i><span>
                            <?php echo e(__('Bank Transfer')); ?></span></a>
                        </li>
                        <?php if(Auth::user()->role_id != 1): ?>
                        <li class="<?php echo e((Route::is('my_vouchers') ? 'active open' : '')); ?>"> 
                            <a href="<?php echo e(url( app()->getLocale().'/')); ?>/my_vouchers"><i class="icon-speedometer"></i><span>
                            <?php echo e(__('Vouchers')); ?></span></a>
                        </li>
                        <?php endif; ?>

                        <li>
                            <a href="javascript:void(0);" class="menu-toggle waves-effect waves-block"><i class="icon-bar-chart"></i><span><?php echo e(__('P2P')); ?></span></a>
                            <ul class="ml-menu" style="z-index:99999 !important;">
                                <li><a href="<?php echo e(route('offerbook',  app()->getLocale())); ?>" class=" waves-effect waves-block"><?php echo e(__('Offer Book')); ?></a></li>
                                <li><a href="<?php echo e(route('mybook',  app()->getLocale())); ?>" class=" waves-effect waves-block"><?php echo e(__('My Positions')); ?></a></li>
                                 <li><a href="<?php echo e(route('myclosed',  app()->getLocale())); ?>" class=" waves-effect waves-block"><?php echo e(__('My Closed Trades')); ?></a></li>
                            </ul>
                        </li>
                        
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