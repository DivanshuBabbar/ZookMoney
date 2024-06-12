<div id="depositModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="depositModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            
            <div class="row">
                <div class="col" >
                    <div class="card bg-light" >
                        <div class="header" style="background-color:#ffffff;">
                            <h2><strong>{{ __('Deposit Fund') }}</strong></h2>
                        </div>
                        <div class="body"style="background-color:#ffffff;">
                            <div class="row clearfix mb-2">
                                <div class="col-md-12" >
                                    @include('flash')
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    {{-- <form id="depositForm" action="{{ route('save.mydeposits', app()->getLocale()) }}" method="POST" enctype="multipart/form-data"> --}}
                                    <form id="depositForm" action="{{ route('ipn.deposit', app()->getLocale()) }}" method="POST" enctype="multipart/form-data">
                                        <meta name="csrf-token" content="{{ csrf_token() }}" />
                                        <input type="hidden" name="ref" value="{{$ref}}">
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Amount <sup>*</sup></label>
                                                    <input type="text" name="amount" class="form-control" value="{{ old('amount') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Payment Method <sup>*</sup></label>
                                                    <select class="form-control deposit" name="deposit_method_id"  required>
                                                        <option value="">--select--</option>
                                                        @if(!empty($depositMethod))
                                                            @foreach($depositMethod as $row)
                                                                <option value="{{$row->id}}"  {{ empty($row->is_eligible) ? "style=color:#bc0000;" : "" }}>
                                                                    {{$row->name}} {{ empty($row->is_eligible) ? ' - ineligible' : '' }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                                <br>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <hr>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label>Instructions for payment: </label>
                                                    <div class="detail">{{ __('Please select a payment method to get instruction related to that.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <hr>
                                            </div>
                                        </div>
                                        <div class="row eligible-row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Payment Proof</label>
                                                    <input type="file" name="transaction_receipt" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Transaction Reference No <sup>*</sup></label>
                                                    <input type="text" name="transaction_receipt_ref_no" class="form-control" value="{{ old('transaction_receipt_ref_no') }}">
                                                    <small id="refNoFormat" class="mt-2"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row eligible-row">
                                            <div class="col-lg-6">
                                                <button type="submit" class="btn btn-primary btn-round bg-blue m-l-10">{{__('Submit')}}</button>
                                            </div>
                                        </div>
                                    </form>            
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>