 <div class=" col-sm-12 col-md-12 col-lg-3">
    <div class="card ">
        <!-- overflowhidden -->
        <?php if(count(Auth::user()->wallets())): ?>
        <div class="header" style="padding-bottom: 0">
            <h2><strong>Main</strong> Wallet</h2>
            <ul class="header-dropdown">
                       
                <li class="dropdown profile"> 
                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="true"> <i class="zmdi zmdi-plus"></i> </a>
                     <ul class="dropdown-menu pullDown">
                    
                        <?php if(count(Auth::user()->wallets())): ?>
                            <?php $__currentLoopData = Auth::user()->wallets(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $someWallet): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li> 
                           <a href="<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/wallet/<?php echo e($someWallet->id); ?>">      
                            <span class="name"><?php echo e($someWallet->currency->name); ?></span>
                           
                            </a>
                        </li>              
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>                            
                      
                    </ul>
                </li>
            </ul>
        </div>

       
        <?php endif; ?>
        <div class="body mb-0">


            <ul class="follow_us list-unstyled mb-0">
                <li class="online">
                        <div class="media mb-0">
                            <img class="media-object " src="<?php echo e(Auth::user()->currentWallet()->currency->thumb); ?>" alt="">
                            <div class="media-body">
                                <span class="name"> <?php echo e(Auth::user()->currentWallet()->currency->name); ?> </span>
                                <span class="message"><?php echo e(\App\Helpers\Money::instance()->value(Auth::user()->balance(), Auth::user()->currentCurrency()->symbol, Auth::user()->currentCurrency()->is_crypto)); ?></span>
                                <span class="badge badge-outline status"></span>
                            </div>
                        </div>                         
                </li>                        
            </ul>
            <div class="content">
                 <a href="<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/deposit/<?php echo e(Auth::user()->currentWallet()->id); ?>"><span class="badge badge-success"><?php echo e(__('DEPOSIT')); ?></span></a> <a href="<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/payout/<?php echo e(Auth::user()->currentWallet()->id); ?>" ><span class="badge badge-success"><?php echo e(__('WITHDRAW')); ?></span></a>
            </div>
        </div>
       
        
    </div>
    <?php if(Route::is('home')): ?>

        <?php if(!empty($myEscrows)): ?>
        
            <?php $__currentLoopData = $myEscrows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $escrow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <div class="card">
                    <div class="header">
                        <h2><strong>On Hold</strong> #<?php echo e($escrow->id); ?></h2>
                        <ul class="header-dropdown">
                            <li class="remove">
                                <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <h3 class="mb-0 pb-0">
                       -  <?php echo e(\App\Helpers\Money::instance()->value( $escrow->gross, $escrow->currency_symbol )); ?>       
                        </h3>
                        Escrow money to  <a href="<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/escrow/<?php echo e($escrow->id); ?>"><span class="text-primary"><?php echo e($escrow->toUser->name); ?></span></a> <br> 
                        <form action="<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/escrow/release" method="post">
                            <?php echo e(csrf_field()); ?>

                            <input type="hidden" name="eid" value="<?php echo e($escrow->id); ?>">
                            <input type="submit" class="btn btn-sm btn-round btn-primary btn-simple" value="<?php echo e(('Release')); ?>">
                            
                        </form>
                    </div>
                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        
        <?php endif; ?> 
    
        <?php if(!empty($toEscrows)): ?>
        
            <?php $__currentLoopData = $toEscrows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $escrow): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                <div class="card">
                    <div class="header">
                        <h2><strong>On Hold</strong> #<?php echo e($escrow->id); ?></h2>
                        <ul class="header-dropdown">
                            <li class="remove">
                                <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                            </li>
                        </ul>
                    </div>
                    <div class="body">
                        <h3 class="mb-0 pb-0">
                        +  <?php echo e(\App\Helpers\Money::instance()->value( $escrow->gross, $escrow->currency_symbol )); ?>       
                        </h3>
                        Escrow money from <a href="<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/escrow/<?php echo e($escrow->id); ?>"><span class="text-primary"><?php echo e($escrow->User->name); ?></span></a> 
                        <form action="<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/escrow/refund" method="post">
                            <?php echo e(csrf_field()); ?>

                            <input type="hidden" name="eid" value="<?php echo e($escrow->id); ?>">
                            <input type="submit" class="btn btn-sm btn-round btn-danger btn-simple" value="<?php echo e(_('refund')); ?>">
                        </form>
                    </div>
                </div>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        
        <?php endif; ?> 

    <?php endif; ?>

 <?php if(Auth::user()->role_id == 2 or Auth::user()->is_ticket_admin ): ?>
    <div class="card hidden-sm">
        <div class="header">
            <h2><strong>Extra</strong> area</h2>
            <ul class="header-dropdown">
                <li class="remove">
                    <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                </li>
            </ul>
        </div>
        <div class="body">
                   <h5 class="card-title">Hello <?php echo e(Auth::user()->name); ?></h5>
                <p class="card-text">In this section you have links that are visible to you.</p>
                 <div class="list-group mb-2">
                    <a href="<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/my_tickets" class="list-group-item list-group-item-action <?php echo e((Route::is('makeVouchers') ? 'inactive' : '')); ?>"><?php echo e(__('Support Tickets')); ?></a>
                    
                        <a href="<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/new_ticket" class="list-group-item list-group-item-action <?php echo e((Route::is('support') ? 'inactive' : '')); ?>"><?php echo e(__('New Ticket')); ?></a>
                    
                   
                </div>
                                 
            
        </div>
    </div> 
    <?php endif; ?>
    
    <?php if(Auth::user()->role_id == 1 or Auth::user()->is_ticket_admin ): ?>
    <div class="card hidden-sm">
        <div class="header">
            <h2><strong>Admin</strong> area</h2>
            <ul class="header-dropdown">
                <li class="remove">
                    <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                </li>
            </ul>
        </div>
        <div class="body">
                   <h5 class="card-title">Howdy Mr. admin <?php echo e(Auth::user()->name); ?></h5>
                <p class="card-text">In this section you have links that are only visible to admins.</p>
                 <div class="list-group mb-2">
                    <a href="<?php echo e(route('makeVouchers', app()->getLocale())); ?>" class="list-group-item list-group-item-action <?php echo e((Route::is('makeVouchers') ? 'active' : '')); ?>"><?php echo e(__('Generate Vouchers')); ?></a>
                   <?php if(Auth::user()->role_id == 1): ?>
                        <a href="<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/ticketadmin/tickets" class="list-group-item list-group-item-action <?php echo e((Route::is('support') ? 'active' : '')); ?>"><?php echo e(__('Manage Tickets')); ?></a>
                    <?php endif; ?>
                    <?php if(Auth::user()->role_id == 1): ?>
                        <a href="<?php echo e(url('/')); ?>/<?php echo e(app()->getLocale()); ?>/update_rates" class="list-group-item list-group-item-action "><?php echo e(__('Update Exchange Rates')); ?></a>
                    <?php endif; ?>
                </div>
                <a href="<?php echo e(url('/')); ?>/admin/dashboard" class="btn btn-primary btn-round">Go to admin dashboard</a>                  
            
        </div>
    </div> 
    <?php endif; ?>
    <?php if(Auth::user()->role_id == 3): ?>
    <div class="card hidden-sm">
        <div class="header">
            <h2><strong>Agent</strong> area</h2>
            <ul class="header-dropdown">
                <li class="remove">
                    <a role="button" class="boxs-close"><i class="zmdi zmdi-close"></i></a>
                </li>
            </ul>
        </div>
        <div class="body ">
            <h5 class="card-title">Howdy Mr. Agent <?php echo e(Auth::user()->name); ?></h5>
            <p class="card-text">In this section you have links that are only visible to Agents</p>
                <div class="list-group mb-2">
                <a href="<?php echo e(route('makeVouchers', app()->getLocale())); ?>" class="list-group-item list-group-item-action <?php echo e((Route::is('makeVouchers') ? 'active' : '')); ?>"><?php echo e(__('Recharge Vouchers')); ?></a>
            </div>
        </div>
    </div> 
    <?php endif; ?>
    <?php if(!Route::is('exchange.form')): ?>
     
    <div class="list-group">
   
    </div>
    <?php endif; ?>
</div>