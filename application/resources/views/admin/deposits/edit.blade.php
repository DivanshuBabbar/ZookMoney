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
                                        <form action="{{ route('admin.deposits.update', $deposits->id) }}" method="POST">
                                            @csrf
                                            <div class="form-group  col-md-12">

                                                <div class="card">

                                                    <div class="col-md-12">
                                                        <div class="panel panel-bordered" style="padding-bottom:5px;">
                                                            <div class="panel-heading" style="border-bottom:0;">
                                                                <h3 class="panel-title">Message to reviewer</h3>
                                                            </div>
                                                            <div class="panel-body" style="padding-top:0;">
                                                                {{ $deposits->message }}
                                                            </div>

                                                            <div class="clearfix"></div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="form-group  hidden  col-md-12">
                                                    <div class="panel-heading" style="border-bottom:0;">
                                                        <h3 class="panel-title">Transaction Receipt</h3>
                                                    </div>
                                                    <div class="panel-body" style="padding-top:0;">
                                                        <img src="{{url('assets/images/'.$deposits->transaction_receipt)}}" width="500px" class="img-responsive">
                                                        <div class="clearfix"></div>
                                                    </div>

                                                </div>
                                                <div class="form-group  col-md-12">

                                                    <label for="name">Transaction Receipt Ref Number</label>
                                                    <input required type="text" class="form-control" name="transaction_receipt_ref_no"
                                                        placeholder="Transaction Receipt Ref Number" value="{{ $deposits->transaction_receipt_ref_no }}">

                                                </div>
                                                <div class="form-group  col-md-12">

                                                    <label for="name">Gross</label>
                                                    <input required type="text" class="form-control" name="gross"
                                                        placeholder="Gross" value="{{ $deposits->gross }}">


                                                </div>
                                                <div class="form-group  col-md-12">

                                                    <label for="name">Message</label>
                                                    <input type="text" class="form-control" name="message"
                                                        placeholder="Message" value="{{ $deposits->message }}">


                                                </div>
                                                <div class="form-group  col-md-12">

                                                    <label for="name">Created At</label>
                                                    <input type="datetime" class="form-control datepicker" name="created_at"
                                                        value="{{ $deposits->created_at }}">


                                                </div>
                                                <div class="form-group  col-md-12">

                                                    <label for="name">Transfer Method Id</label>
                                                    <input type="text" class="form-control" name="transfer_method_id"
                                                        placeholder="Transfer Method Id"
                                                        value="@isset($deposits->deposit_method_id){{$deposits->method->name}}@endisset">


                                                </div>
                                                <div class="form-group  col-md-12">

                                                    <label for="name">Unique Transaction Id</label>
                                                    <input type="text" class="form-control" name="unique_transaction_id"
                                                        placeholder="Unique Transaction Id"
                                                        value="{{ $deposits->unique_transaction_id }}">

                                                </div>
                                                <div class="form-group col-md-12">
                                                  <label for="name">Kyc File Upload</label>
                                                   <input type="text" class="form-control" value="{{$deposits->kyc_file_upload}}" readonly>
                                                  @if($deposits->kyc_file_upload)
                                                    <a href="{{$kyc_url}}" target="_blank">View File</a>
                                                   @else
                                                    <p>No file uploaded</p>
                                                @endif
                                            </div>
                                                <div class="form-group  col-md-12">
                                                    <label for="name">transaction_states</label>
                                                    <select class="form-control" name="transaction_state_id">
                                                        @foreach ($status as $transaction_status)
                                                            <option value="{{ $transaction_status->id }}"
                                                                {{ $deposits->transaction_state_id == $transaction_status->id ? 'selected' : '' }}>
                                                                {{ $transaction_status->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                                <div class="">
                                                    <button type="submit"
                                                        class="btn btn-primary">{{ __('Submit') }}</button>
                                                </div>
                                        </form>
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
