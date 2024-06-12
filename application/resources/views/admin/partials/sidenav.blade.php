<aside class="main-sidebar sidebar-dark-info elevation-4">
    <a href="#" class="brand-link">
        <img src="{{general_setting('site_icon')}}" alt="Fleet Logo" class="brand-image"
            style="opacity: .8">
        <span class="brand-text font-weight-light">{{general_setting('site_name')}}</span>
    </a>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            
            <div class="info">
                <a href="#" class="d-block">Administrator</a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ url('administrator/new_dashboard') }}" class="nav-link">
                        <i class="nav-icon fa fa-dashboard"></i>
                        <p>
                            Dashboard <span class="right badge badge-danger"></span>
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview ">
                    <a href="#" class="nav-link <?php echo isset($active) && $active == 'users' ? 'active' : ''; ?>">
                        <i class="nav-icon fa fa-users"></i>
                        <p>
                            Users <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="display:<?php echo isset($active) && $active == 'users' ? 'block' : ''; ?>">
                        <li class="nav-item">
                            <a href="{{ route('admin.user.list') }}" class="nav-link <?php echo isset($active) && $active == 'users' ? 'active' : ''; ?>">
                                <i class="fa fa-user nav-icon"></i>
                                <p>Users</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item has-treeview ">
                    <a href="#" class="nav-link <?php echo isset($active) && ($active == 'transaction' || $active == 'payout') ? 'active' : ''; ?>">
                        <i class="nav-icon fa fa-money"></i>
                        <p>
                            Transactions <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="display:<?php echo isset($active) && ($active == 'transaction' || $active == 'payout') ? 'block' : ''; ?>">
                        <li class="nav-item">
                            <a href="{{ route('admin.transaction.list') }}" class="nav-link <?php echo isset($active) && $active == 'transaction' ? 'active' : ''; ?>">
                                <i class="fa fa-newspaper-o nav-icon"></i>
                                <p>All Transactions</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.payoutList') }}" class="nav-link <?php echo isset($active) && $active == 'payout' ? 'active' : ''; ?>">
                                <i class="fa fa-credit-card-alt nav-icon"></i>
                                <p>All Payout</p>
                            </a>
                        </li>
                    </ul>

                </li>
                <li class="nav-item has-treeview ">
                    <a href="#" class="nav-link <?php echo isset($active) && $active == 'general_setting' ? 'active' : ''; ?>">
                        <i class="nav-icon fa fa-gear"></i>
                        <p>
                            Settings <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="display:<?php echo isset($active) && $active == 'general_setting' ? 'block' : ''; ?>">
                        <li class="nav-item">
                            <a href="{{ route('admin.setting') }}" class="nav-link <?php echo isset($active) && $active == 'general_setting' ? 'active' : ''; ?>">
                                <i class="nav-icon fa fa-gear"></i>
                                <p>General Settings</p>
                            </a>
                        </li>
                    </ul>
                </li>
                {{-- Deposit --}}
                <li class="nav-item has-treeview ">
                    <a href="#" class="nav-link <?php echo (isset($active) && ($active == 'deposits'|| $active == 'deposit_method')) ? 'active':'';?>">
                        <i class="nav-icon fa fa-wallet"></i>
                                <p>Deposit<i class="right fa fa-angle-left"></i></p>
                            
                        
                    </a>
                    <ul class="nav nav-treeview" style="display:<?php echo (isset($active) && ($active == 'deposits' || $active == 'deposit_method')) ? 'block':''; ?>">
                        <li class="nav-item">
                            <a href="{{ route('admin.deposits.list') }}" class="nav-link <?php echo isset($active) && $active == 'deposits' ? 'active' : ''; ?>">
                                <i class=" nav-icon fa fa-wallet" aria-hidden="true"></i>
                                <p>Deposits  <span class="right badge badge-danger"></span></p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.deposit.method.list') }}" class="nav-link <?php echo isset($active) && $active == 'deposit_method' ? 'active' : ''; ?>">
                                <i class=" nav-icon fa fa-wallet" aria-hidden="true"></i>
                                <p>Deposit Method<span class="right badge badge-danger"></span> </p>
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Withdraw --}}
                <li class="nav-item has-treeview ">
                    <a href="#" class="nav-link <?php echo (isset($active) && ($active == 'withdrawal'|| $active == 'withdraw_method')) ? 'active':''; ?>">
                        <i class="fa fa-credit-card-alt" aria-hidden="true"></i>
                            <p>
                                Withdraw <i class="right fa fa-angle-left"></i>
                            </p>
                    </a>
                    <ul class="nav nav-treeview" style="display:<?php echo (isset($active) && ($active == 'withdrawal'|| $active == 'withdraw_method')) ? 'block':''; ?>">
                        <li class="nav-item">
                            <a href="{{ route('admin.withdrawal.list') }}" class="nav-link <?php echo isset($active) && $active == 'withdrawal' ? 'active' : ''; ?>">
                                <i class="fa fa-credit-card-alt" aria-hidden="true"></i>
                                    <p>
                                        Withdrawals
                                    </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.withdraw.method.list') }}" class="nav-link <?php echo isset($active) && $active == 'withdraw_method' ? 'active' : ''; ?>">
                                <i class="fa fa-credit-card-alt" aria-hidden="true"></i>
                                    <p>  Withdraw Method</p>
                            </a>
                        </li>
                    </ul>
                </li>


                <!-- <li class="nav-item">
                    <a href="{{ route('admin.deposit.method.list') }}" class="nav-link <?php echo isset($active) && $active == 'deposit_method' ? 'active' : ''; ?>">
                        <i class="fa fa-gear nav-icon"></i>
                        <p>Deposit Method</p>
                    </a>
                </li> -->
              <!--   <li class="nav-item">
                    <a href="{{ route('admin.withdraw.method.list') }}" class="nav-link <?php echo isset($active) && $active == 'withdraw_method' ? 'active' : ''; ?>">
                        <i class="fa fa-gear nav-icon"></i>
                        <p>Withdraw Method</p>
                    </a>
                </li> -->
                <!-- Currencies exchange -->
                <li class="nav-item">
                    <a href="{{ route('admin.currency.list') }}" class="nav-link <?php echo isset($active) && $active == 'currency' ? 'active' : ''; ?>">
                        <i class="nav-icon fa fa-inr"></i>
                        <p>
                            Currencies <span class="right badge badge-danger"></span>
                        </p>
                    </a>
                </li>
                {{-- currency Exchange --}}
                <li class="nav-item">
                    <a href="{{ route('admin.exchange-rate.list') }}" class="nav-link <?php echo isset($active) && $active == 'currency_exchange' ? 'active' : ''; ?>">
                        <i class="nav-icon fa fa-exchange" aria-hidden="true"></i>
                        <p>
                            Conversions <span class="right badge badge-danger"></span>
                        </p>
                    </a>
                </li>
                {{-- countries --}}
                <li class="nav-item">
                    <a href="{{ route('admin.countries.list') }}" class="nav-link <?php echo isset($active) && $active == 'countries' ? 'active' : ''; ?>">
                        <i class=" nav-icon fa fa-globe" aria-hidden="true"></i>
                        <p>Countries <span class="right badge badge-danger"></span> </p>
                    </a>
                </li>
                {{-- Deposits --}}
               <!--  <li class="nav-item">
                    <a href="{{ route('admin.deposits.list') }}" class="nav-link <?php echo isset($active) && $active == 'deposits' ? 'active' : ''; ?>">
                        <i class=" nav-icon fa fa-wallet" aria-hidden="true"></i>
                        <p>Deposits<span class="right badge badge-danger"></span> </p>
                    </a>
                </li> -->
                <!-- Merchant-->
                <li class="nav-item ">
                    <a href="{{ route('admin.merchant.list') }}" class="nav-link <?php echo isset($active) && $active == 'merchant' ? 'active' : ''; ?>">
                        <i class="nav-icon fa fa-balance-scale"></i>
                        <p>
                            Merchant <span class="right badge badge-danger"></span>
                        </p>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="{{ route('admin.ticketlist') }}" class="nav-link <?php echo isset($active) && $active == 'ticket' ? 'active' : ''; ?>">
                        <i class="nav-icon fa fa-address-card-o"></i>
                        <p>
                            Support Tickets <span class="right badge badge-danger"></span>
                        </p>
                    </a>
                </li>
            <li class="nav-item">
                <a href="{{ route('admin.developertoollist') }}" class="nav-link <?php echo isset($active) && $active == 'developer_tools' ? 'active' : ''; ?>">
                    <i class="nav-icon fa fa-code"></i>
                    <p>
                        Developer Tools <span class="right badge badge-danger"></span>
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.bulk_payout.index') }}" class="nav-link <?php echo isset($active) && $active == 'bulk_payout' ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-money-bill-wave"></i>
                    <p>
                        Bulk Payout<span class="right badge badge-danger"></span>
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.whitelistaccount.index') }}" class="nav-link <?php echo isset($active) && $active == 'whitelist_account' ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-user-check"></i>
                    <p>
                        Whitelist Bank Account<span class="right badge badge-danger"></span>
                    </p>
                </a>
            </li>
             <li class="nav-item">
                <a href="{{ route('admin.ourbankaccount.index') }}" class="nav-link <?php echo isset($active) && $active == 'our_bank_account' ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-piggy-bank"></i>
                    <p>
                        Our Bank Account<span class="right badge badge-danger"></span>
                    </p>
                </a>
            </li>

                 
                <!-- Escrows-->
                {{-- <li class="nav-item">
                    <a href="{{ route('admin.escrow.list') }}" class="nav-link {{ isset($active) && $active == 'escrow' ? 'active' : ''; }}">
                        <i class="nav-icon fa fa-dashboard"></i>
                        <p>
                            Escrows <span class="right badge badge-danger"></span>
                        </p>
                    </a>
                </li> --}}
                <!-- Withdraws -->
               <!--  <li class="nav-item">
                    <a href="{{ route('admin.withdrawal.list') }}" class="nav-link <?php echo isset($active) && $active == 'withdrawal' ? 'active' : ''; ?>">
                        <i class="nav-icon fa fa-dashboard"></i>
                        <p>
                            Withdrawals <span class="right badge badge-danger"></span>
                        </p>
                    </a>
                </li> -->
               
            </ul>
        </nav>
    </div>
</aside>
