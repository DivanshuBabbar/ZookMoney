@extends('admin.layouts.master')
@push('styles')
<style>
    #toggleButton {
    background-color: #FF6400;
    color: white; 
    border: 2px solid #FF6400; 
    border-radius: 5px; 
    padding: 10px 20px; 
    font-size: 16px; 
    cursor: pointer; 
}

#toggleButton:hover {
    background-color: #FF7F00;
}

#today_stat {
    background-color: #FF6400;
    color: white; 
    border: 2px solid #FF6400; 
    border-radius: 5px; 
    padding: 10px 20px; 
    font-size: 16px; 
    cursor: pointer; 
}

#today_stat:hover {
    background-color: #FF7F00;
}

#transaction_stat {
    background-color: #FF6400;
    color: white; 
    border: 2px solid #FF6400; 
    border-radius: 5px; 
    padding: 10px 20px; 
    font-size: 16px; 
    cursor: pointer; 
}

#transaction_stat:hover {
    background-color: #FF7F00;
}

#overall_stat {
    background-color: #FF6400;
    color: white; 
    border: 2px solid #FF6400; 
    border-radius: 5px; 
    padding: 10px 20px; 
    font-size: 16px; 
    cursor: pointer; 
}

#overall_stat:hover {
    background-color: #FF7F00;
}

#monthly_stat {
    background-color: #FF6400;
    color: white; 
    border: 2px solid #FF6400; 
    border-radius: 5px; 
    padding: 10px 20px; 
    font-size: 16px; 
    cursor: pointer; 
}

#monthly_stat:hover {
    background-color: #FF7F00;
}

#changeback_stat {
    background-color: #FF6400;
    color: white; 
    border: 2px solid #FF6400; 
    border-radius: 5px; 
    padding: 10px 20px; 
    font-size: 16px; 
    cursor: pointer; 
}

#changeback_stat:hover {
    background-color: #FF7F00;
}

    </style>
@endpush
@section('content')
<section class="content">
    <div class="content-wrapper">
    <div class="card">
    <div class="card-header">
        <h3 class="card-title" style="font-size: 24px; font-weight: bold; color: #00a49e;">Dashboard <span id="exchange-rate" style="font-size: 18px; color: #333; float:right">1 USDT = {{$exchangeRateUSDTtoINR}} INR</span> </h3> 
    </div>
    <div class="card-body">
        <div class="row mb-2">
            <div class="col-sm-12">
                <button id="toggleButton" class="btn" style="background-color:#00a49e; color: white; font-weight: bold; border: 2px solid white;" data-toggle="tooltip" data-placement="top" title="Click to view overall data">
                    <i class="fas fa-users"></i> Users and Merchant 
                </button>
                <button id="today_stat" class="btn" style="background-color:#00a49e; color: white; font-weight: bold; border: 2px solid white; " data-toggle="tooltip" data-placement="top" title="Click to view today stats">
                    <i class="far fa-calendar-alt"></i> Today Stats
                </button>
                <button id="transaction_stat" class="btn" style="background-color:#00a49e; color: white; font-weight: bold; border: 2px solid white;" data-toggle="tooltip" data-placement="top" title="Click to view transaction counts">
                    <i class="fa fa-exchange"></i> Transactions Count
                </button>
                <button id="overall_stat" class="btn" style="background-color:#00a49e; color: white; font-weight: bold; border: 2px solid white;" data-toggle="tooltip" data-placement="top" title="Click to view overall stats">
                    <i class="fa fa-chart-bar"></i> Overall Stats
                </button>
                <button id="monthly_stat" class="btn" style="background-color:#00a49e; color: white; font-weight: bold; border: 2px solid white;" data-toggle="tooltip" data-placement="top" title="Click to view monthly stats">
                    <i class="fa fa-chart-line"></i> Monthly Stats
                </button> 
                <button id="changeback_stat" class="btn" style="background-color:#00a49e; color: white; font-weight: bold; border: 2px solid white;" data-toggle="tooltip" data-placement="top" title="Click to view changeback stats">
                    <i class="fa fa-pie-chart"></i> Chargeback Stats
                </button> 
            </div>
        </div>
    </div>
</div>

<div class="row" id="toggleRow" style="display: none;">
    <div class="col-md-12">
        <div class="card" >
            <div class="card-body">
                <div class="col-md-12 text-right" style ="margin-top:-17px;">
                    <span id="last-update-time" style="margin-right: 20px;color:red;"></span> 
                    <button id="refresh-button" class="btn btn-info" style="margin-bottom:10px;">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-default">
                        <div class="card-header">
                            <h5 class="m-0"><i class="fas fa-users" style="margin-right: 5px;"></i> Users</h5>
                        </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="info-box bg-info">
                                            <span class="info-box-icon"><i class="fas fa-users"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Total</span>
                                                <span class="info-box-number" id="total-count" ><i class="fas fa-spinner fa-spin"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="info-box bg-success">
                                            <span class="info-box-icon"><i class="fas fa-user-check"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Active</span>
                                                <span class="info-box-number" id="active-count"><i class="fas fa-spinner fa-spin"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="info-box bg-warning">
                                            <span class="info-box-icon"><i class="fas fa-user-lock"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Verified</span>
                                                <span class="info-box-number" id="verified-count"><i class="fas fa-spinner fa-spin"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="info-box bg-danger">
                                            <span class="info-box-icon"><i class="fas fa-user-times"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Blocked</span>
                                                <span class="info-box-number" id="blocked-count"><i class="fas fa-spinner fa-spin"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="info-box bg-primary">
                                            <span class="info-box-icon"><i class="fas fa-chart-bar"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Inactive</span>
                                                <span class="info-box-number" id="inactive"><i class="fas fa-spinner fa-spin"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="info-box bg-dark">
                                            <span class="info-box-icon"><i class="fas fa-envelope"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Spam</span>
                                                <span class="info-box-number" id="spam"><i class="fas fa-spinner fa-spin"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="info-box bg-light">
                                            <span class="info-box-icon"><i class="fas fa-exclamation-triangle"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Suspicious</span>
                                                <span class="info-box-number" id="suspicious"><i class="fas fa-spinner fa-spin"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         </div>
                    </div>
                 </div>
          
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-default">
                            <div class="card-header">
                            <h5 class="m-0"><i class="fas fa-user-circle" style="margin-right: 5px;"></i>Admins</h5>
                        </div>
                            <div class="card-body">
                                <div class="row">
                                <div class="col-md-3">
                                        <div class="info-box bg-info">
                                            <span class="info-box-icon"><i class="fas fa-users"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Total</span>
                                                <span class="info-box-number" id="total-admin_count"><i class="fas fa-spinner fa-spin"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-box bg-success">
                                            <span class="info-box-icon"><i class="fas fa-user-check"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Active</span>
                                                <span class="info-box-number" id="active-admin_count"><i class="fas fa-spinner fa-spin"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-box bg-warning">
                                            <span class="info-box-icon"><i class="fas fa-user-lock"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Verified</span>
                                                <span class="info-box-number" id="verified-admin_count"><i class="fas fa-spinner fa-spin"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-box bg-danger">
                                            <span class="info-box-icon"><i class="fas fa-user-times"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Blocked</span>
                                                <span class="info-box-number" id="blocked-admin_count"><i class="fas fa-spinner fa-spin"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-default">
                            <div class="card-header">
                            <h5 class="m-0"><i class="fas fa-user-circle" style="margin-right: 5px;"></i> Normal Users</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                <div class="col-md-3">
                                        <div class="info-box bg-info">
                                            <span class="info-box-icon"><i class="fas fa-users"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Total</span>
                                                <span class="info-box-number" id="total-normal_count"><i class="fas fa-spinner fa-spin"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-box bg-success">
                                            <span class="info-box-icon"><i class="fas fa-user-check"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Active</span>
                                                <span class="info-box-number" id="active-normal_count"><i class="fas fa-spinner fa-spin"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-box bg-warning">
                                            <span class="info-box-icon"><i class="fas fa-user-lock"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Verified</span>
                                                <span class="info-box-number" id="verified-normal_count"><i class="fas fa-spinner fa-spin"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-box bg-danger">
                                            <span class="info-box-icon"><i class="fas fa-user-times"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Blocked</span>
                                                <span class="info-box-number" id="blocked-normal_count"><i class="fas fa-spinner fa-spin"></i></span>
                                            </div>
                                        </div>
                                    </div>  
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            <div class="card-body" style="margin-top:-43px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-default">
                            <div class="card-header">
                            <h5 class="m-0"><i class="fas fa-store" style="margin-right: 5px;"></i> Merchant</h5>
                        </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="info-box bg-info">
                                            <span class="info-box-icon"><i class="fas fa-users"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Total</span>
                                                <span class="info-box-number" id="total-merchant_count"><i class="fas fa-spinner fa-spin"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-box bg-success">
                                            <span class="info-box-icon"><i class="fas fa-user-check"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Active</span>
                                                <span class="info-box-number" id="active-merchant_count"><i class="fas fa-spinner fa-spin"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-box bg-warning">
                                            <span class="info-box-icon"><i class="fas fa-user-lock"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Verified</span>
                                                <span class="info-box-number" id="verified-merchant_count"><i class="fas fa-spinner fa-spin"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-box bg-danger">
                                            <span class="info-box-icon"><i class="fas fa-user-times"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Blocked</span>
                                                <span class="info-box-number" id="blocked-merchant_count"><i class="fas fa-spinner fa-spin"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-default">
                            <div class="card-header">
                            <h5 class="m-0"><i class="fas fa-shopping-bag" style="margin-right: 5px;"></i> Resellers</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                <div class="col-md-3">
                                        <div class="info-box bg-info">
                                            <span class="info-box-icon"><i class="fas fa-users"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Total</span>
                                                <span class="info-box-number" id="total-resellar_count"><i class="fas fa-spinner fa-spin"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-box bg-success">
                                            <span class="info-box-icon"><i class="fas fa-user-check"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Active</span>
                                                <span class="info-box-number" id="active-resellar_count"><i class="fas fa-spinner fa-spin"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-box bg-warning">
                                            <span class="info-box-icon"><i class="fas fa-user-lock"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Verified</span>
                                                <span class="info-box-number" id="verified-resellar_count"><i class="fas fa-spinner fa-spin"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="info-box bg-danger">
                                            <span class="info-box-icon"><i class="fas fa-user-times"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Blocked</span>
                                                <span class="info-box-number" id="blocked-resellar_count" ><i class="fas fa-spinner fa-spin"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>

                <div class="card-body" style="margin-top:-43px;">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-default">
                            <div class="card-header">
                            <h5 class="m-0"><i class="fas fa-store" style="margin-right: 5px;"></i> Merchant</h5>
                        </div>
                            <div class="card-body">
                                <div class="row">
                                        <div class="col-md-2">
                                        <div class="info-box bg-light">
                                                <span class="info-box-icon"><i class="fab fa-angellist"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Inprinciple Approval</span>
                                                    <span class="info-box-number" id="merchant_approval_count"><i class="fas fa-spinner fa-spin"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                        <div class="info-box bg-light">
                                                <span class="info-box-icon"><i class="far fa-check-circle"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Approved</span>
                                                    <span class="info-box-number" id="merchant_approval_counts"><i class="fas fa-spinner fa-spin"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon"><i class="fa fa-close"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Rejected</span>
                                                    <span class="info-box-number" id="merchant_rejected_counts"><i class="fas fa-spinner fa-spin"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="info-box bg-light">
                                                <span class="info-box-icon"><i class="fas fa-archway"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Payin Enabled</span>
                                                    <span class="info-box-number" id="payin_status"><i class="fas fa-spinner fa-spin"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                        <div class="info-box bg-light">
                                                <span class="info-box-icon"><i class="fab fa-asymmetrik"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">White Label Enabled</span>
                                                    <span class="info-box-number" id="white_label_status_zero"><i class="fas fa-spinner fa-spin"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                        <div class="info-box bg-light">
                                                <span class="info-box-icon"><i class="fas fa-university"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Wire Enabled</span>
                                                    <span class="info-box-number" id="wire_transfer_status_zero"><i class="fas fa-spinner fa-spin"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                        <div class="info-box bg-light">
                                                <span class="info-box-icon"><i class="fa fa-credit-card-alt"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Payout Enabled</span>
                                                    <span class="info-box-number" id="payout_status"><i class="fas fa-spinner fa-spin"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                        <div class="info-box bg-light">
                                                <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">Bulk Payout Enabled</span>
                                                    <span class="info-box-number" id="bulk_payout"><i class="fas fa-spinner fa-spin"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                        <div class="info-box bg-light">
                                                <span class="info-box-icon"><i class="fas fa-hourglass-start"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">T+0</span>
                                                    <span class="info-box-number" id="t0"><i class="fas fa-spinner fa-spin"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                        <div class="info-box bg-light">
                                                <span class="info-box-icon"><i class="fas fa-hourglass-start"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">T+1</span>
                                                    <span class="info-box-number" id="t1"><i class="fas fa-spinner fa-spin"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                        <div class="info-box bg-light">
                                                <span class="info-box-icon"><i class="fas fa-hourglass-start"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">T+2</span>
                                                    <span class="info-box-number" id="t2"><i class="fas fa-spinner fa-spin"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                        <div class="info-box bg-light">
                                                <span class="info-box-icon"><i class="fas fa-hourglass-start"></i></span>
                                                <div class="info-box-content">
                                                    <span class="info-box-text">T+3</span>
                                                    <span class="info-box-number" id="t3" ><i class="fas fa-spinner fa-spin"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                     </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row" id="today_stats" style="display: none;">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row" style="margin-left: 384px;">
                <div class="col-md-12 text-right" style ="margin-top:-17px;">
                    <span id="last-update-today-time" style="margin-right: 20px;color:red;"></span> 
                    <button id="refresh-today-button" class="btn btn-info" style="margin-bottom:10px;">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
                <div class="col-md-4" id="gross_incoming">
                    <div class="info-box bg-light">
                        <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Gross Incoming</span>
                            <span class="info-box-number" id="gross_incomings">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                        </div>
                    </div>
                </div>
                    <div class="col-md-4" id="gross_outgoing">
                        <div class="info-box bg-light">
                            <span class="info-box-icon"><i class="fas fa-dollar-sign"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Gross Outgoing</span>
                                <span class="info-box-number" id="gross_outgoings">
                                    <i class="fas fa-spinner fa-spin"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
               <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-header">
                                <h5 class="m-0"><i class="fas fa-shopping-cart" style="margin-right: 5px;"></i> Payin</h5>
                            </div>
                            <div class="row" style="margin-top:20px;">
                                <div class="col-md-6">
                                    <div class="card shadow">
                                        <div class="card-header">
                                            <h6 class="m-0"><i class="fa fa-cloud-download" style="margin-right: 5px;"></i> Incoming/Deposited</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row" style="margin-top:20px;">
                                                <div class="col-md-4">
                                                    <div class="info-box bg-light">
                                                        <span class="info-box-icon"><i class="fas fa-cash-register"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Manual Deposit</span>
                                                            <span class="info-box-number" id="manual-deposit"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="info-box bg-light">
                                                        <span class="info-box-icon"><i class="fas fa-university"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">UPI</span>
                                                            <span class="info-box-number" id="upi"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="info-box bg-light">
                                                        <span class="info-box-icon"><i class="fas fa-wrench"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Wire</span>
                                                            <span class="info-box-number" id="wire_count"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>      
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card shadow">
                                        <div class="card-header">
                                            <h6 class="m-0"><i class="fas fa-handshake" style="margin-right: 5px;"></i> Settlement</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row" style="margin-top:20px;">
                                                <div class="col-md-6">
                                                    <div class="info-box bg-light">
                                                        <span class="info-box-icon"><i class="fas fa-money-bill-alt"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Withdrawals</span>
                                                            <span class="info-box-number" id="withdrawals"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info-box bg-light">
                                                        <span class="info-box-icon"><i class="fas fa-exchange-alt"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Transferrred To Payout</span>
                                                            <span class="info-box-number" id="transferred_to_payout"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>      
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-header">
                                <h5 class="m-0"><i class="fas fa-money-check" style="margin-right: 5px;"></i>Payout</h5>
                            </div>
                            <div class="row" style="margin-top:20px;">
                                <div class="col-md-6">
                                    <div class="card shadow">
                                        <div class="card-header">
                                            <h6 class="m-0"><i class="fas fa-download" style="margin-right: 5px;"></i>Incoming</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row" style="margin-top:20px;">
                                                <div class="col-md-6">
                                                    <div class="info-box bg-light">
                                                        <span class="info-box-icon"><i class="fas fa-wallet"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Deposited</span>
                                                            <span class="info-box-number" id="payout_deposit"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info-box bg-light">
                                                        <span class="info-box-icon"><i class="fas fa-money-check-alt"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Transferred</span>
                                                            <span class="info-box-number" id="payout_transfer"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>      
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card shadow">
                                        <div class="card-header">
                                            <h6 class="m-0"><i class="fas fa-hand-holding-usd" style="margin-right: 5px;"></i>Outgoing</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row" style="margin-top:20px;">
                                                <div class="col-md-6">
                                                    <div class="info-box bg-light">
                                                        <span class="info-box-icon"><i class="fas fa-money-bill-wave"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Payouts</span>
                                                            <span class="info-box-number" id="payout_amount"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="info-box bg-light">
                                                        <span class="info-box-icon"><i class="fas fa-exchange-alt"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Bulk Payouts</span>
                                                            <span class="info-box-number" id="bulk_payouts"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>      
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" id="transaction_stats" style="display: none;">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row" style="margin-left: 384px;">
                    <div class="col-md-12 text-right" style ="margin-top:-17px;">
                        <span id="last-update-transaction-time" style="margin-right: 20px;color:red;"></span> 
                        <button id="refresh-transaction-button" class="btn btn-info" style="margin-bottom:10px;">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>
               <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-header">
                                <h5 class="m-0"><i class="fas fa-shopping-cart" style="margin-right: 5px;"></i>Payin</h5>
                            </div>
                            <div class="row" style="margin-top:20px;">
                                <div class="col-md-6">
                                    <div class="card shadow">
                                        <div class="card-header">
                                            <h6 class="m-0"><i class="fas fa-university" style="margin-right: 5px;"></i>Upi</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row" style="margin-top:20px;">
                                                <div class="col-md-3">
                                                    <div class="info-box bg-success">
                                                        <span class="info-box-icon"><i class="fa fa-check"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Success</span>
                                                            <span class="info-box-number" id="upi_completed"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-warning">
                                                        <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Pending</span>
                                                            <span class="info-box-number" id="upi_pending"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-info">
                                                        <span class="info-box-icon"><i class="fa fa-hourglass-2"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text" style="margin-left:-5px;">Partially Completed</span>
                                                            <span class="info-box-number" id="upi_partially_completed"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-danger">
                                                        <span class="info-box-icon"><i class="fa fa-close"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Cancelled</span>
                                                            <span class="info-box-number" id="upi_cancelled"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>      
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card shadow">
                                        <div class="card-header">
                                            <h6 class="m-0"><i class="fas fa-wrench" style="margin-right: 5px;"></i>Wire</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row" style="margin-top:20px;">
                                                <div class="col-md-3">
                                                    <div class="info-box bg-success">
                                                        <span class="info-box-icon"><i class="fa fa-check"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Success</span>
                                                            <span class="info-box-number" id="wire_completed"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-warning">
                                                        <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Pending</span>
                                                            <span class="info-box-number" id="wire_pending"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-info">
                                                        <span class="info-box-icon"><i class="fa fa-hourglass-2"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text" style="margin-left:-5px;">Partially Completed</span>
                                                            <span class="info-box-number" id="wire_partially_completed"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-danger">
                                                        <span class="info-box-icon"><i class="fa fa-close"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Cancelled</span>
                                                            <span class="info-box-number" id="wire_cancelled"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>      
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:20px;">
                                <div class="col-md-6">
                                    <div class="card shadow">
                                        <div class="card-header">
                                            <h6 class="m-0"><i class="fas fa-cash-register" style="margin-right: 5px;"></i>Manual deposit</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row" style="margin-top:20px;">
                                                <div class="col-md-3">
                                                    <div class="info-box bg-success">
                                                        <span class="info-box-icon"><i class="fa fa-check"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Success</span>
                                                            <span class="info-box-number" id="manual_completed"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-warning">
                                                        <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Pending</span>
                                                            <span class="info-box-number" id="manual_pending"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-info">
                                                        <span class="info-box-icon"><i class="fa fa-hourglass-2"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text" style="margin-left:-5px;">Partially Completed</span>
                                                            <span class="info-box-number" id="manual_partially_completed"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-danger">
                                                        <span class="info-box-icon"><i class="fa fa-close"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Cancelled</span>
                                                            <span class="info-box-number" id="manual_cancelled"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>      
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card shadow">
                                        <div class="card-header">
                                            <h6 class="m-0"><i class="fas fa-money-bill-alt " style="margin-right: 5px;"></i>Withdrawals </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row" style="margin-top:20px;">
                                                <div class="col-md-3">
                                                    <div class="info-box bg-success">
                                                        <span class="info-box-icon"><i class="fa fa-check"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Success</span>
                                                            <span class="info-box-number" id="settlement_completed"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-warning">
                                                        <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Pending</span>
                                                            <span class="info-box-number" id="settlement_pending"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-info">
                                                        <span class="info-box-icon"><i class="fa fa-hourglass-2"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text" style="margin-left:-5px;">Partially Completed</span>
                                                            <span class="info-box-number" id="settlement_partially_completed"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-danger">
                                                        <span class="info-box-icon"><i class="fa fa-close"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Cancelled</span>
                                                            <span class="info-box-number" id="settlement_cancelled"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>      
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:20px;">
                                <div class="col-md-6">
                                    <div class="card shadow">
                                        <div class="card-header">
                                            <h6 class="m-0"><i class="fa fa-handshake-o" style="margin-right: 5px;"></i>Transferred</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row" style="margin-top:20px;">
                                                <div class="col-md-3">
                                                    <div class="info-box bg-success">
                                                        <span class="info-box-icon"><i class="fa fa-check"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Success</span>
                                                            <span class="info-box-number" id="transferred_completed"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-warning">
                                                        <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Pending</span>
                                                            <span class="info-box-number" id="transferred_pending"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-info">
                                                        <span class="info-box-icon"><i class="fa fa-hourglass-2"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text" style="margin-left:-5px;">Partially Completed</span>
                                                            <span class="info-box-number" id="transferred_partially_completed"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-danger">
                                                        <span class="info-box-icon"><i class="fa fa-close"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Cancelled</span>
                                                            <span class="info-box-number" id="transferred_cancelled"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>      
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-header">
                                <h5 class="m-0"><i class="fas fa-money-check" style="margin-right: 5px;"></i>Payout</h5>
                            </div>
                            <div class="row" style="margin-top:20px;">
                                <div class="col-md-6">
                                    <div class="card shadow">
                                        <div class="card-header">
                                            <h6 class="m-0"><i class="fa fa-cloud-download" style="margin-right: 5px;"></i>Deposited</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row" style="margin-top:20px;">
                                                <div class="col-md-3">
                                                    <div class="info-box bg-success">
                                                        <span class="info-box-icon"><i class="fa fa-check"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Success</span>
                                                            <span class="info-box-number" id="payout_deposit_completed"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-warning">
                                                        <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Pending</span>
                                                            <span class="info-box-number" id="payout_deposit_pending"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-info">
                                                        <span class="info-box-icon"><i class="fa fa-hourglass-2"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text" style="margin-left:-5px;">Partially Completed</span>
                                                            <span class="info-box-number" id="payout_deposit_partially_completed"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-danger">
                                                        <span class="info-box-icon"><i class="fa fa-close"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Cancelled</span>
                                                            <span class="info-box-number" id="payout_deposit_cancelled"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>      
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card shadow">
                                        <div class="card-header">
                                            <h6 class="m-0"><i class="fa fa-handshake-o" style="margin-right: 5px;"></i>Transferred</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row" style="margin-top:20px;">
                                                <div class="col-md-3">
                                                    <div class="info-box bg-success">
                                                        <span class="info-box-icon"><i class="fa fa-check"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Success</span>
                                                            <span class="info-box-number" id="payout_transferred_completed"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-warning">
                                                        <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Pending</span>
                                                            <span class="info-box-number" id="payout_transferred_pending"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-info">
                                                        <span class="info-box-icon"><i class="fa fa-hourglass-2"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text" style="margin-left:-5px;">Partially Completed</span>
                                                            <span class="info-box-number" id="payout_transferred_partially_completed"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-danger">
                                                        <span class="info-box-icon"><i class="fa fa-close"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Cancelled</span>
                                                            <span class="info-box-number" id="payout_transferred_cancelled"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>      
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top:20px;">
                                <div class="col-md-6">
                                    <div class="card shadow">
                                        <div class="card-header">
                                            <h6 class="m-0"><i class="fa fa-envelope-o" style="margin-right: 5px;"></i>Bulk payouts</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row" style="margin-top:20px;">
                                                <div class="col-md-3">
                                                    <div class="info-box bg-success">
                                                        <span class="info-box-icon"><i class="fa fa-check"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Success</span>
                                                            <span class="info-box-number" id="bulk_payout_completed"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-warning">
                                                        <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Pending</span>
                                                            <span class="info-box-number" id="bulk_payout_pending"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-info">
                                                        <span class="info-box-icon"><i class="fa fa-hourglass-2"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text" style="margin-left:-5px;">Partially Completed</span>
                                                            <span class="info-box-number" id="bulk_payout_partially_completed"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-danger">
                                                        <span class="info-box-icon"><i class="fa fa-close"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Cancelled</span>
                                                            <span class="info-box-number" id="bulk_payout_cancelled"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>      
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card shadow">
                                        <div class="card-header">
                                            <h6 class="m-0"><i class="fa fa-level-up" style="margin-right: 5px;"></i>Payouts</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row" style="margin-top:20px;">
                                                <div class="col-md-3">
                                                    <div class="info-box bg-success">
                                                        <span class="info-box-icon"><i class="fa fa-check"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Success</span>
                                                            <span class="info-box-number" id="payout_completed"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-warning">
                                                        <span class="info-box-icon"><i class="fa fa-clock-o"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Pending</span>
                                                            <span class="info-box-number" id="payout_pending"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-info">
                                                        <span class="info-box-icon"><i class="fa fa-hourglass-2"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text" style="margin-left:-5px;">Partially Completed</span>
                                                            <span class="info-box-number" id="payout_partially_completed"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="info-box bg-danger">
                                                        <span class="info-box-icon"><i class="fa fa-close"></i></span>
                                                        <div class="info-box-content">
                                                            <span class="info-box-text">Cancelled</span>
                                                            <span class="info-box-number" id="payout_cancelled"><i class="fas fa-spinner fa-spin"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>      
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row" id="overall_stats" style="display: none;">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row" style="margin-left: 384px;">
                    <div class="col-md-12 text-right" style ="margin-top:-17px;">
                        <span id="last-overall-update-time" style="margin-right: 20px;color:red;"></span> 
                        <span id="last_update_hard" style="margin-right: 20px; color: red; display: none;"></span>
                        <button id="refresh-overall-button" class="btn btn-info" style="margin-bottom:10px;">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                        <button id="refreshard-overall-button" class="btn btn-danger" style="margin-bottom:10px;">
                            <i class="fas fa-sync-alt"></i>Hard Refresh
                        </button>
                    </div>
                </div>
               <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-header">
                                <h5 class="m-0"><i class="fa fa-chart-bar" style="margin-right: 5px;"></i>Overall Stats</h5>
                            </div>
                            <div class="row" style="margin-top: 10px;">
                                <div class="col-md-6" id="payin_legers">
                                    <div class="info-box bg-light">
                                        <span class="info-box-icon"><i class="fas fa-briefcase"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Payin Available Balance</span>
                                            <span class="info-box-number" id="payin_alltime"><i class="fas fa-spinner fa-spin"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="payout_legers">
                                    <div class="info-box bg-light">
                                        <span class="info-box-icon"><i class="fas fa-briefcase"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Payout Available Balance</span>
                                            <span class="info-box-number" id="alltimepayout"><i class="fas fa-spinner fa-spin"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row" id="monthly_stats" style="display: none;">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row" style="margin-left: 384px;">
                    <div class="col-md-12 text-right" style ="margin-top:-17px;">
                        <span id="last-monthly-update-time" style="margin-right: 20px;color:red;"></span> 
                        <span id="last_update_hard" style="margin-right: 20px; color: red; display: none;"></span>
                        <button id="refreshard-monthly-button" class="btn btn-info" style="margin-bottom:10px;">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </div>
               <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-header">
                                <h5 class="m-0"><i class="fa fa-chart-bar" style="margin-right: 5px;"></i>Monthly Stats</h5>
                            </div>
                            <div class="row" style="margin-top: 10px;">
                                <div class="col-md-6" id="payin_legers">
                                    <div class="info-box bg-light shadow">
                                        <span class="info-box-icon"><i class="fas fa-briefcase"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Payin Available Balance</span>
                                            <span class="info-box-number" id="montly_payin_alltime"><i class="fas fa-spinner fa-spin"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="payout_legers">
                                    <div class="info-box bg-light shadow">
                                        <span class="info-box-icon"><i class="fas fa-briefcase"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Payout Available Balance</span>
                                            <span class="info-box-number" id="monthly_alltimepayout"><i class="fas fa-spinner fa-spin"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 10px;">
                                <div class="col-md-4" id="top_merchants">
                                    <div class="info-box bg-light shadow">
                                        <span class="info-box-icon"><i class="fas fa-star"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Top merchant</span>
                                            <span class="info-box-number" id="top_merchant"><i class="fas fa-spinner fa-spin"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4" id="active_merchants">
                                    <div class="info-box bg-light shadow">
                                        <span class="info-box-icon"><i class="fas fa-user-check"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Active merchant</span>
                                            <span class="info-box-number" id="active_merchant"><i class="fas fa-spinner fa-spin"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4" id="new_merchant">
                                    <div class="info-box bg-light shadow">
                                        <span class="info-box-icon"><i class="fas fa-plus-circle"></i></span>
                                        <div class="info-box-content" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <span class="info-box-text">New merchant</span>
                                            <span class="info-box-number" id="new_merchants"><i class="fas fa-spinner fa-spin"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row" id="changeback_stats" style="display: none;">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row" style="margin-left: 384px;">
                    <div class="col-md-12 text-right" style ="margin-top:-17px;">
                        <span id="last-chargeback-update-time" style="margin-right: 20px;color:red;"></span> 
                        <button id="refresh-chargeback-button" class="btn btn-info" style="margin-bottom:10px;">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                        <button id="refreshard-chargeback-button" class="btn btn-danger" style="margin-bottom:10px;">
                            <i class="fas fa-sync-alt"></i>Hard Refresh
                        </button>
                    </div>
                </div>
               <div class="row">
                    <div class="col-md-12">
                        <div class="card shadow">
                            <div class="card-header">
                                <h5 class="m-0"><i class="fa fa-pie-chart" style="margin-right: 5px;"></i>Chargeback Stats</h5>
                            </div>
                            <div class="row" style="margin-top: 10px;">
                                <div class="col-md-6" id="overall_chargebacks">
                                    <div class="info-box bg-light shadow">
                                        <span class="info-box-icon"><i class="fas fa-suitcase"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Overall Chargebacks</span>
                                            <span class="info-box-number" id="overall_changeback"><i class="fas fa-spinner fa-spin"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6" id="todays_chargebacks">
                                    <div class="info-box bg-light shadow">
                                        <span class="info-box-icon"><i class="fas fa-suitcase"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Today's Chargebacks</span>
                                            <span class="info-box-number" id="today_changeback"><i class="fas fa-spinner fa-spin"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 20px;">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header bg-light text-white">
                                            <h5 class="card-title"><i class="fas fa-chart-bar" style="margin-right: 5px;"></i>User-wise Chargeback Counts</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped" id="user_chargeback_table">
                                                    <thead class="thead-info">
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Name</th>
                                                            <th>Email</th>
                                                            <th>Chargeback Count</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="user_chargeback_body">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


</section>
@endsection
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
 
    $(document).ready(function () {
        function updateCounts() {
            $.ajax({
                url: '{{ route('admin.getCounts') }}',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    updateHTML(data);
                    localStorage.setItem('lastUpdateTime', new Date().getTime());
                    localStorage.setItem('lastCounts', JSON.stringify(data));
                    updateLastUpdateTime();
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching counts:', error);
                }
            });
        }

        function updateHTML(counts) {
            $('#total-count').text(counts.total);
            $('#active-count').text(counts.active);
            $('#verified-count').text(counts.verified);
            $('#blocked-count').text(counts.blocked);

            $('#total-admin_count').text(counts.total_admins);
            $('#active-admin_count').text(counts.active_admins);
            $('#verified-admin_count').text(counts.verified_admins);
            $('#blocked-admin_count').text(counts.blocked_admins);

            $('#total-normal_count').text(counts.total_normal);
            $('#active-normal_count').text(counts.active_normal);
            $('#verified-normal_count').text(counts.verified_normal);
            $('#blocked-normal_count').text(counts.blocked_normal);

            $('#total-merchant_count').text(counts.total_merchant);
            $('#active-merchant_count').text(counts.active_merchant);
            $('#verified-merchant_count').text(counts.verified_merchant);
            $('#blocked-merchant_count').text(counts.blocked_merchant);

            $('#total-resellar_count').text(counts.total_resellar);
            $('#active-resellar_count').text(counts.active_resellar);
            $('#verified-resellar_count').text(counts.verified_resellar);
            $('#blocked-resellar_count').text(counts.blocked_resellar);

            $('#merchant_approval_count').text(counts.merchant_approval_count);
            $('#merchant_approval_counts').text(counts.merchant_approval_counts);
            $('#merchant_rejected_counts').text(counts.merchant_rejected_counts);
            $('#white_label_status_zero').text(counts.white_label_status_zero);
            $('#wire_transfer_status_zero').text(counts.wire_transfer_status_zero);
            $('#payout_status').text(counts.payout_status);
            $('#payin_status').text(counts.payin_status);
            $('#bulk_payout').text(counts.bulk_payout);
            $('#spam').text(counts.spam);
            $('#inactive').text(counts.inactive);
            $('#suspicious').text(counts.suspicious);
            $('#t0').text(counts.merchant_t0_count);
            $('#t1').text(counts.merchant_t1_count);
            $('#t2').text(counts.merchant_t2_count);
            $('#t3').text(counts.merchant_t3_count);

        }
        function updateLastUpdateTime() {
        var lastUpdateTime = new Date(parseInt(localStorage.getItem('lastUpdateTime')));
        var formattedTime = lastUpdateTime.toLocaleString();
        $('#last-update-time').text('Last Updated: ' + formattedTime);
        }

        $('#refresh-button').click(function (event) {
            event.preventDefault();
            event.stopPropagation();
            updateCounts();
        });

        var lastCounts = JSON.parse(localStorage.getItem('lastCounts'));
        if (lastCounts) {
            updateHTML(lastCounts);
            updateLastUpdateTime();
        }
    });

    $(document).ready(function() {
    function toggleStats(showSelector) {
        var isVisible = $(showSelector).is(":visible");
        // Hide all sections
        $("#toggleRow, #today_stats, #transaction_stats, #overall_stats, #monthly_stats, #changeback_stats").hide();
        // Toggle the clicked section
        if (!isVisible) {
            $(showSelector).show();
        }
    }

    $("#toggleButton").click(function() {
        toggleStats("#toggleRow");
    });

    $("#today_stat").click(function() {
        toggleStats("#today_stats");
    });

    $("#transaction_stat").click(function() {
        toggleStats("#transaction_stats");
    });

    $("#overall_stat").click(function() {
        toggleStats("#overall_stats");
    });

    $("#monthly_stat").click(function() {
        toggleStats("#monthly_stats");
    });

    $("#changeback_stat").click(function() {
        toggleStats("#changeback_stats");
    });
});



//today stats

$(document).ready(function () {
    function updateTodayCounts() {
            $.ajax({
                url: '{{ route('admin.gettodaystats') }}',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    updateTodayHTML(data);
                    localStorage.setItem('lastTodayUpdateTime', new Date().getTime());
                    localStorage.setItem('lastTodayCounts', JSON.stringify(data));
                    updateLasttodayUpdateTime();
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching counts:', error);
                }
            });
        }

        function updateTodayHTML(counts) {
            $('#manual-deposit').text(counts.manual_deposit_count);
            $('#upi').text(counts.purchase_sale_count);
            $('#bulk_payouts').text(counts.bulk_payouts);
            $('#payout_amount').text(counts.payout_amount);
            $('#payout_deposit').text(counts.payout_deposit);
            $('#payout_transfer').text(counts.payout_transfer);
            $('#withdrawals').text(counts.withdrawals);
            $('#transferred_to_payout').text(counts.transferred_to_payout);
            $('#wire_count').text(counts.wire_count);
            $('#gross_incomings').text(counts.gross_incomings);
            $('#gross_outgoings').text(counts.gross_outgoing);

        }

        function updateLasttodayUpdateTime() {
            var lastUpdateTime = new Date(parseInt(localStorage.getItem('lastTodayUpdateTime')));
            var formattedTime = lastUpdateTime.toLocaleString();
            $('#last-update-today-time').text('Last Updated: ' + formattedTime);
        }

        $('#refresh-today-button').click(function (event) {
            event.preventDefault();
            event.stopPropagation();
            updateTodayCounts();
        });
        var lastCounts = JSON.parse(localStorage.getItem('lastTodayCounts'));
        if (lastCounts) {
            updateTodayHTML(lastCounts);
            updateLasttodayUpdateTime();
        }
    });

    //transaction
$(document).ready(function () {
    function updatetransactionCounts() {
            $.ajax({
                url: '{{ route('admin.gettransactionstats') }}',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    updateTransactionHTML(data);
                    localStorage.setItem('lastTranctionUpdateTime', new Date().getTime());
                    localStorage.setItem('lastTransactionCounts', JSON.stringify(data));
                    updateLasttransactionUpdateTime();
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching counts:', error);
                }
            });
        }

        function updateTransactionHTML(counts) {
            var activityTypes = ['upi', 'wire', 'manual', 'settlement', 'transferred'];
            var payoutTypes = ['Payout-Deposit', 'Transferred', 'Bulk Transfer'];

            activityTypes.forEach(function(type) {
                ['completed', 'pending', 'cancelled', 'partially_completed'].forEach(function(status) {
                    $('#' + type + '_' + status).text(counts[type + '_' + status]);
                });
            });

            payoutTypes.forEach(function(type) {
                ['completed', 'pending', 'cancelled', 'partially_completed'].forEach(function(status) {
                    $('#payout_' + type.toLowerCase() + '_' + status).text(counts['payout_' + type + '_' + status]);
                });
            });

            ['completed', 'pending', 'cancelled', 'partially_completed'].forEach(function(status) {
                $('#payout_' + status).text(counts['payout_' + status]);
            });

            ['completed', 'pending', 'cancelled', 'partially_completed'].forEach(function(status) {
                $('#bulk_payout_' + status).text(counts['payout_Bulk Transfer_' + status]);
            });
            ['completed', 'pending', 'cancelled', 'partially_completed'].forEach(function(status) {
                $('#payout_deposit_' + status).text(counts['payout_Payout-Deposit_' + status]);
            });
        }

    function updateLasttransactionUpdateTime() {
        var lastUpdateTime = new Date(parseInt(localStorage.getItem('lastTranctionUpdateTime')));
        var formattedTime = lastUpdateTime.toLocaleString();
        $('#last-update-transaction-time').text('Last Updated: ' + formattedTime);
    }
    $('#refresh-transaction-button').click(function (event) {
            event.preventDefault();
            event.stopPropagation();
            updatetransactionCounts();
    });
    var lastCounts = JSON.parse(localStorage.getItem('lastTransactionCounts'));
        if (lastCounts) {
            updateTransactionHTML(lastCounts);
            updateLasttransactionUpdateTime();
        }
});

//overall stats hard refresh
$(document).ready(function () {
    function updateOverallCounts() {
        $.ajax({
            url: '{{ route('admin.getoverallstats') }}',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                updateOverallHTML(data);
                localStorage.setItem('lastoverallUpdateTime', new Date().getTime());
                localStorage.setItem('lastOverallCounts', JSON.stringify(data));
                updateOverallUpdateTime();
            },
            error: function (xhr, status, error) {
                console.error('Error fetching counts:', error);
            }
        });
    }

    function updateOverallHTML(counts) {
        $('#payin_alltime').text(counts.payin_alltime);
        $('#alltimepayout').text(counts.alltimepayout);
        $('#last_update_hard').text(counts.datetime);
    }

    function updateOverallUpdateTime() {
        var lastUpdateTime = new Date(parseInt(localStorage.getItem('lastoverallUpdateTime')));
        var formattedTime = lastUpdateTime.toLocaleString();
        $('#last-overall-update-time').text('Last Updated: ' + formattedTime);
    }

    $('#refreshard-overall-button').click(function (event) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'Hard refresh take something to load the data',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, refresh!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#refresh-overall-button').show();
                $('#last-overall-update-time').show();
                event.preventDefault();
                event.stopPropagation();
                updateOverallCounts();
            }
        });
    });


    var lastCounts = JSON.parse(localStorage.getItem('lastOverallCounts'));
    if (lastCounts) {
        updateOverallHTML(lastCounts);
        updateOverallUpdateTime();
    }
});


             
//refreah overall
$(document).ready(function () {
    var lastUpdateTime = new Date(parseInt(localStorage.getItem('lastoverallUpdateTime')));
    var formattedTime = lastUpdateTime.toLocaleString();


    function updateOverallRefreshCounts() {
        
        var lastUpdateTime = new Date(parseInt(localStorage.getItem('lastoverallUpdateTime')));
        var formattedTime = lastUpdateTime.toLocaleString();
        $.ajax({
            url: '{{ route('admin.getoverallstatsrefresh') }}',
            type: 'POST',
            data: { 
                formattedTime: formattedTime, 
                _token: "{{ csrf_token() }}" 
            }, 
            success: function (data) {
                updateOverallRefreshHTML(data);
                localStorage.setItem('lastoverallUpdateTime', new Date().getTime());
                updateOverallUpdateRefreshTime();
            },
            error: function (xhr, status, error) {
                console.error('Error fetching counts:', error);
            }
        });
    }

    function updateOverallRefreshHTML(counts) {
        var payinAlltimeValue = parseFloat($('#payin_alltime').text()) || 0;
        var countsPayinAlltime = counts.payin_alltime || 0;
        var result = payinAlltimeValue + parseFloat(countsPayinAlltime);

        var payoutAlltimeValue = parseFloat($('#alltimepayout').text()) || 0;
        var countsPayoutAlltime = counts.alltimepayout || 0;
        var result2 = payoutAlltimeValue + parseFloat(countsPayoutAlltime);
        console.log(result2); 

        $('#payin_alltime').text(result);
        $('#alltimepayout').text(result2);

        var lastOverallCounts = {
            payin_alltime: result,
            alltimepayout: result2
        };
        localStorage.setItem('lastOverallCounts', JSON.stringify(lastOverallCounts));
    }
  
    function updateOverallUpdateRefreshTime() {
        var lastUpdateTime = new Date(parseInt(localStorage.getItem('lastoverallUpdateTime')));
        var formattedTime = lastUpdateTime.toLocaleString();
        $('#last-overall-update-time').text('Last Updated: ' + formattedTime);
    }

    $('#refresh-overall-button').click(function (event) {
        event.preventDefault();
        event.stopPropagation();
        updateOverallRefreshCounts();
    });

    var lastCounts = JSON.parse(localStorage.getItem('lastOverallCounts'));
    if (lastCounts) {
        updateOveralRefreshlHTML(lastCounts);
        updateOverallUpdateRefreshTime();
    }
    if (localStorage.getItem('lastoverallUpdateTime') !== null) {
        $('#refresh-overall-button').show();
   }else{
        $('#refresh-overall-button').hide();
        $('#last-overall-update-time').hide();
   }


});

         //monthly stats
     $(document).ready(function () {
        function updateMonthlyCounts() {
            $.ajax({
                url: '{{ route('admin.getmonthlystats') }}',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    updateMonthlyHTML(data);
                    localStorage.setItem('lastmonthlyUpdateTime', new Date().getTime());
                    localStorage.setItem('lastmonthlyCounts', JSON.stringify(data));
                    updateMonthlyUpdateTime();
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching counts:', error);
                }
            });
        }

        function updateMonthlyHTML(counts) {
            $('#montly_payin_alltime').text(counts.montly_payin_alltime);
            $('#monthly_alltimepayout').text(counts.monthly_alltimepayout);
            $('#top_merchant').text(counts.top_merchant);
            $('#new_merchants').text(counts.new_merchants);
            $('#active_merchant').text(counts.active_merchant);     
        }

        function updateMonthlyUpdateTime() {
            var lastUpdateTime = new Date(parseInt(localStorage.getItem('lastmonthlyUpdateTime')));
            var formattedTime = lastUpdateTime.toLocaleString();
            $('#last-monthly-update-time').text('Last Updated: ' + formattedTime);
        }
        $('#refreshard-monthly-button').click(function (event) {
            event.preventDefault();
            event.stopPropagation();
            updateMonthlyCounts();
        });


        var lastCounts = JSON.parse(localStorage.getItem('lastmonthlyCounts'));
        if (lastCounts) {
            updateMonthlyHTML(lastCounts);
            updateMonthlyUpdateTime();
        }
    });

    //chargeback hard refresh
    $(document).ready(function () {
        var lastOverallCounts = {};

        function displayLastUpdate() {
            var lastUpdateTime = new Date(parseInt(localStorage.getItem('lastchargebackUpdateTime')));
            var formattedTime = lastUpdateTime.toLocaleString();
            var lastOverallCounts = JSON.parse(localStorage.getItem('lastChargebackCounts'));

            $('#last-chargeback-update-time').text('Last Updated: ' + formattedTime);

            if (lastOverallCounts) {
                $('#overall_changeback').text(lastOverallCounts.overall_changeback || 0);
                $('#today_changeback').text(lastOverallCounts.today_changeback || 0);

                var tableBody = $('#user_chargeback_body');
                tableBody.empty();

                Object.keys(lastOverallCounts).forEach(function (userId) {
                    if (userId !== 'overall_changeback' && userId !== 'today_changeback') {
                        var userData = lastOverallCounts[userId];
                        tableBody.append('<tr><td>' + userId + '</td><td class="user-name"><a href="transaction/list?userId=' + userId + '">' + userData.name + '</a></td><td>' + userData.email + '</td><td>' + userData.chargebackCount + '</td></tr>');
                    }
                });
            }
        }

        function updateChargebackCounts() {
            $.ajax({
                url: '{{ route('admin.getchargebackstats') }}',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    updateChargebackHTML(data);
                    localStorage.setItem('lastchargebackUpdateTime', new Date().getTime());
                    localStorage.setItem('lastChargebackCounts', JSON.stringify(data));
                    updateChargebackUpdateTime();
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching counts:', error);
                }
            });
        }

        function updateChargebackHTML(counts) {
            if (!counts) return;
            $('#overall_changeback').text(counts.overall_changeback);
            $('#today_changeback').text(counts.today_changeback);
            $('#user_chargeback_body').empty();

            if (counts.user_changeback_counts) {
                var sortedUsers = counts.user_changeback_counts.sort((a, b) => b.chargeback_count - a.chargeback_count);

                sortedUsers.forEach(user => {
                    var userId = user.user_id;
                    var userName = user.name;
                    var userEmail = user.email;
                    var chargebackCount = user.chargeback_count;

                    $('#user_chargeback_body').append('<tr><td>' + userId + '</td><td class="user-name"><a href="transaction/list?userId=' + userId + '">' + userName + '</a></td><td>' + userEmail + '</td><td>' + chargebackCount + '</td></tr>');

                    lastOverallCounts[userId] = {
                        name: userName,
                        email: userEmail,
                        chargebackCount: chargebackCount
                    };
                });
            }

            lastOverallCounts.overall_changeback = counts.overall_changeback ;
            lastOverallCounts.today_changeback = counts.today_changeback ;

            localStorage.setItem('lastChargebackCounts', JSON.stringify(lastOverallCounts));
        }
      
        function updateChargebackUpdateTime() {
                var lastUpdateTime = new Date(parseInt(localStorage.getItem('lastchargebackUpdateTime')));
                var formattedTime = lastUpdateTime.toLocaleString();
                $('#last-chargeback-update-time').text('Last Updated: ' + formattedTime);
            }

            $('#refreshard-chargeback-button').click(function (event) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Hard refresh take something to load the data',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, refresh!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#refresh-chargeback-button').show();
                        $('#last-chargeback-update-time').show();
                        event.preventDefault();
                        event.stopPropagation();
                        updateChargebackCounts();
                    }
                });
            });

        var lastCounts = JSON.parse(localStorage.getItem('lastChargebackCounts'));
        if (lastCounts) {
            updateChargebackHTML(lastCounts);
            updateChargebackUpdateTime();
        }
    });


    //refresh chargeback
    
    $(document).ready(function () {

        var lastOverallCounts = {};

        function displayLastUpdate() {
            var lastUpdateTime = new Date(parseInt(localStorage.getItem('lastchargebackUpdateTime')));
            var formattedTime = lastUpdateTime.toLocaleString();
            var lastOverallCounts = JSON.parse(localStorage.getItem('lastChargebackCounts'));

            $('#last-chargeback-update-time').text('Last Updated: ' + formattedTime);

            if (lastOverallCounts) {
                $('#overall_changeback').text(lastOverallCounts.overall_changeback || 0);
                $('#today_changeback').text(lastOverallCounts.today_changeback || 0);

                var tableBody = $('#user_chargeback_body');
                tableBody.empty();

                Object.keys(lastOverallCounts).forEach(function (userId) {
                    if (userId !== 'overall_changeback' && userId !== 'today_changeback') {
                        var userData = lastOverallCounts[userId];
                        tableBody.append('<tr><td>' + userId + '</td><td class="user-name"><a href="transaction/list?userId=' + userId + '">' + userData.name + '</a></td><td>' + userData.email + '</td><td>' + userData.chargebackCount + '</td></tr>');
                    }
                });
            }
        }

        function updateChargebackRefreshCounts() {
            var lastUpdateTime = new Date(parseInt(localStorage.getItem('lastchargebackUpdateTime')));
            var formattedTime = lastUpdateTime.toLocaleString();

            $.ajax({
                url: '{{ route('admin.getchargebackstatsrefresh') }}',
                type: 'POST',
                data: {
                    formattedTime: formattedTime,
                    _token: "{{ csrf_token() }}"
                },
                success: function (data) {
                    updateChargebackRefreshHTML(data);
                    localStorage.setItem('lastchargebackUpdateTime', new Date().getTime());
                    updateChargebackUpdateRefreshTime();
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching counts:', error);
                }
            });
        }           

            function updateChargebackRefreshHTML(counts) {
            if (!counts) return;

            var lastOverallCounts = JSON.parse(localStorage.getItem('lastChargebackCounts')) || {};

            var overall_changeback = parseFloat($('#overall_changeback').text()) || 0;
            var countsoverall_changeback = counts.overall_changeback || 0;
            var result = overall_changeback + parseFloat(countsoverall_changeback);

            var today_changeback = parseFloat($('#today_changeback').text()) || 0;
            var countstoday_changeback = counts.today_changeback || 0;
            var result2 = today_changeback + parseFloat(countstoday_changeback);

            $('#overall_changeback').text(result);
            $('#today_changeback').text(result2);
            $('#user_chargeback_body').empty();

            if (counts.user_changeback_counts) {
                var sortedUsers = counts.user_changeback_counts.sort((a, b) => b.chargeback_count - a.chargeback_count);

                sortedUsers.forEach(user => {
                    var userId = user.user_id;
                    var userName = user.name;
                    var userEmail = user.email;
                    var chargebackCount = user.chargeback_count;

                    $('#user_chargeback_body').append('<tr><td>' + userId + '</td><td class="user-name"><a href="transaction/list?userId=' + userId + '">' + userName + '</a></td><td>' + userEmail + '</td><td>' + chargebackCount + '</td></tr>');

                    // Store user data in lastOverallCounts
                    lastOverallCounts[userId] = {
                        name: userName,
                        email: userEmail,
                        chargebackCount: chargebackCount
                    };
                });
            }

            // Update overall changeback counts in lastOverallCounts
            lastOverallCounts.overall_changeback = result;
            lastOverallCounts.today_changeback = result2;

            // Store lastOverallCounts in local storage
            localStorage.setItem('lastChargebackCounts', JSON.stringify(lastOverallCounts));
        }


        function updateChargebackUpdateRefreshTime() {
            var lastUpdateTime = new Date(parseInt(localStorage.getItem('lastchargebackUpdateTime')));
            var formattedTime = lastUpdateTime.toLocaleString();
            $('#last-chargeback-update-time').text('Last Updated: ' + formattedTime);
        }

        $('#refresh-chargeback-button').click(function (event) {
            event.preventDefault();
            event.stopPropagation();
            updateChargebackRefreshCounts();
        });

       displayLastUpdate();

       if (localStorage.getItem('lastchargebackUpdateTime') !== null) {
            $('#refresh-chargeback-button').show();
       }else{
            $('#refresh-chargeback-button').hide();
            $('#last-chargeback-update-time').hide();
       }
    });

</script>
@endpush
