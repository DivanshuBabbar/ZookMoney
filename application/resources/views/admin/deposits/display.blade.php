@extends('admin.layouts.master')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark"> {{ $page_title }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12 col-xs-12">
                                        <div class="row">
                                            <div class="col-md-12">

                                                <div class="panel panel-bordered" style="padding-bottom:5px;">
                                                    <!-- form start -->

                                                    <div class="panel-heading" style="border-bottom:5px;">
                                                        <h5 class="panel-title">Id</h5>
                                                    </div>

                                                    <div class="panel-body" style="padding-top:5px;">
                                                        <p>{{ $deposits->id }}</p>
                                                    </div><!-- panel-body -->
                                                    <hr style="margin:5px;">

                                                    <div class="panel-heading" style="border-bottom:5px;">
                                                        <h5 class="panel-title">User</h5>
                                                    </div>

                                                    <div class="panel-body" style="padding-top:5px;">
                                                        <p>{{$deposits->user->name}}</p>
                                                    </div><!-- panel-body -->
                                                    <hr style="margin:5px;">

                                                    <div class="panel-heading" style="border-bottom:5px;">
                                                        <h5 class="panel-title">Currency Id</h5>
                                                    </div>

                                                    <div class="panel-body" style="padding-top:5px;">
                                                        <p>{{ $deposits->currency_id }}</p>
                                                    </div><!-- panel-body -->
                                                    <hr style="margin:5px;">

                                                    <div class="panel-heading" style="border-bottom:5px;">
                                                        <h5 class="panel-title">Gross</h5>
                                                    </div>

                                                    <div class="panel-body" style="padding-top:5px;">
                                                        <p>{{ $deposits->gross }}</p>
                                                    </div><!-- panel-body -->
                                                    <hr style="margin:5px;">

                                                    <div class="panel-heading" style="border-bottom:5px;">
                                                        <h5 class="panel-title">Fee</h5>
                                                    </div>

                                                    <div class="panel-body" style="padding-top:5px;">
                                                        <p>{{ $deposits->fee }}</p>
                                                    </div><!-- panel-body -->
                                                    <hr style="margin:5px;">

                                                    <div class="panel-heading" style="border-bottom:5px;">
                                                        <h5 class="panel-title">Net</h5>
                                                    </div>

                                                    <div class="panel-body" style="padding-top:5px;">
                                                        <p>{{ $deposits->net }}</p>
                                                    </div><!-- panel-body -->
                                                    <hr style="margin:5px;">

                                                    <div class="panel-heading" style="border-bottom:5px;">
                                                        <h5 class="panel-title">TRX Receipt Ref No</h5>
                                                    </div>

                                                    <div class="panel-body" style="padding-top:5px;">
                                                        <p>{{ $deposits->transaction_receipt_ref_no }}</p>
                                                    </div><!-- panel-body -->
                                                    <hr style="margin:5px;">

                                                    <div class="panel-heading" style="border-bottom:5px;">
                                                        <h5 class="panel-title">Transaction Receipt</h5>
                                                    </div>

                                                    <div class="panel-body" style="padding-top:5px;">
                                                      "
                                                    <a href="{{url('assets/images/'.$deposits->transaction_receipt)}}" download="">
                                                         <img src="{{url('assets/images/'.$deposits->transaction_receipt)}}" width="500px">
                                                    </a>
                                                   
                                                    </div><!-- panel-body -->
                                                    <hr style="margin:0;margin-top:10px;">

                                                    <div class="panel-heading mt-2" style="border-bottom:5px;">
                                                        <h5 class="panel-title">Currency Symbol</h5>
                                                    </div>

                                                    <div class="panel-body" style="padding-top:5px;">
                                                        <p>{{ $deposits->currency_symbol }}</p>
                                                    </div><!-- panel-body -->
                                                    <hr style="margin:5px;">

                                                    <div class="panel-heading" style="border-bottom:5px;">
                                                        <h5 class="panel-title">Message</h5>
                                                    </div>

                                                    <div class="panel-body" style="padding-top:5px;">
                                                        <p>{{ $deposits->message }}</p>
                                                    </div><!-- panel-body -->
                                                    <hr style="margin:5px;">

                                                    <div class="panel-heading" style="border-bottom:5px;">
                                                        <h5 class="panel-title">Created At</h5>
                                                    </div>

                                                    <div class="panel-body" style="padding-top:5px;">
                                                        {{ localDate($deposits->created_at, 'M j, Y, g:i A') }}
                                                    </div><!-- panel-body -->
                                                    <hr style="margin:5px;">

                                                    <div class="panel-heading" style="border-bottom:5px;">
                                                        <h5 class="panel-title">Transfer Method Id</h5>
                                                    </div>

                                                    <div class="panel-body" style="padding-top:5px;">
                                                        <p>{{ $deposits->transfer_method_id }}</p>
                                                    </div><!-- panel-body -->
                                                    <hr style="margin:5px;">

                                                    <div class="panel-heading" style="border-bottom:5px;">
                                                        <h5 class="panel-title">wallets</h5>
                                                    </div>

                                                    <div class="panel-body" style="padding-top:5px;">
                                                        <p>{{ $deposits->wallet_id }}</p>




                                                    </div><!-- panel-body -->
                                                    <hr style="margin:0;">

                                                    <div class="panel-heading" style="border-bottom:0;">
                                                        <h5 class="panel-title">Unique Transaction Id</h5>
                                                    </div>

                                                    <div class="panel-body" style="padding-top:0;">
                                                        <p>{{ $deposits->unique_transaction_id }}</p>
                                                    </div><!-- panel-body -->
                                                    <hr style="margin:0;">

                                                    <div class="panel-heading" style="border-bottom:0;">
                                                        <h5 class="panel-title">transaction_states</h5>
                                                    </div>

                                                    <div class="panel-body" style="padding-top:0;">
                                                        <p>{{ $deposits->Status->name }}</p>

                                                    </div>
                                                    <hr style="margin:0;">
                                                    <div class="panel-heading" style="border-bottom:0;">
                                                        <h5 class="panel-title">Deposited From</h5>
                                                    </div>

                                                    <div class="panel-body" style="padding-top:0;">
                                                        <p>{{ $deposits->bank_account ?? ''}}</p>

                                                    </div>
                                                    <div class="panel-heading" style="border-bottom:0;">
                                                        <h5 class="panel-title">Deposited To</h5>
                                                    </div>
                                                    <div class="panel-body" style="padding-top:0;">
                                                        <p>{{ $deposits->deposited_to ?? ''}}</p>

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
        </section>
    </div>
@endsection
