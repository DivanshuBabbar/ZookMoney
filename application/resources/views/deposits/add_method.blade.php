@extends('layouts.app')
@section('content')
<div class="row clearfix">
    <div class="col-md-12 " >
        @include('flash')
    </div>
</div>
    <div class="row">
        @include('partials.sidebar')
        <div class="col-lg-9 col-md-12">
            <div class="row">
                <div class="col" >
                    <div class="card bg-light" >
                        <div class="header" style="background-color:#ffffff;">
                            <h2><strong>{{ __('Select deposit method') }}</strong></h2>
                        </div>
                        <div class="body"style="background-color:#ffffff;">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <form action="{{ route('save.mydeposits', app()->getLocale()) }}" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="balance_type" value="{{ $type }}" />
                                    <input type="hidden" name="deposit_to" value="{{$bank->name ?? ''}} - {{$bank->account_number ?? ''}} - {{$bank->ifsc_number ?? ''}}" />
                                        <meta name="csrf-token" content="{{ csrf_token() }}" />
                                        @csrf
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Amount</label>
                                                    <input type="text" name="amount" class="form-control" value="{{ old('amount') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Payment Method</label>
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
                                      
                                        <div class="row eligible-row">
                                             <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label>Depositing From</label>
                                                    <select class="form-control deposit" name="bank_account"  required>
                                                        <option value="">--select--</option>
                                                        @if(!empty($bankaccounts))
                                                            @foreach($bankaccounts as $row)
                                                                <option value="{{$row->name}} - {{ $row->account_number}} - {{ $row->ifsc_number}}">
                                                                    {{$row->name}} - {{ $row->account_number}} - {{ $row->ifsc_number}}
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
                                            <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>KYC <sup>*</sup></label>
                                                <input type="file" name="kyc" accept=".pdf" class="form-control" required>
                                                <small class="form-text text-muted">Please upload your KYC in PDF format only.</small>
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

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded',function(){
            $(document).off('change', 'select[name="deposit_method_id"]').on('change', 'select[name="deposit_method_id"]', function(e) {
                let id  = $(this).find(':selected').val();
                if(id == '') {
                    $('body').find('.detail').html('{{ __('Please select a payment method to get instruction related to that.') }}');
                    $('body').find('#refNoFormat').html('{{ __('Sample Transaction Ref No: please select a payment method to get sample ref no.') }}');
                    return;
                }
                let text = '';
                $.ajax({
                    url:"{{ route('save.mydeposits', app()->getLocale()) }}",
                    method:'POST',
                    datatype:'json',
                    data:{'id':id},
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success:function(response) {

                        if(response.is_eligible != '') {
                            $('body').find('.eligible-row').show();
                        } else {
                            $('body').find('.eligible-row').hide();
                        }

                        if(response.detail != '') {
                            $('body').find('.detail').html(response.detail);
                        } else {
                            $('body').find('.detail').html('{{ __('Please select a payment method to get instruction related to that.') }}');    
                        }

                        if(response.transaction_receipt_ref_no_format != '') {
                            $('body').find('#refNoFormat').html(`Sample Transaction Ref No: ${response.transaction_receipt_ref_no_format}`);
                        } else {
                            $('body').find('#refNoFormat').html('');    
                        }
                    }
                })
            })

            var bankData = {!! json_encode($bank) !!};
            console.log(bankData);
            $(document).off('change', 'select[name="bank_account"]').on('change', 'select[name="bank_account"]', function(e) {
                let data = bankData;  
                console.log(data);             
                if (data) {
                    let name = data.name; 
                    let acc_no = data.account_number; 
                    let ifsc = data.ifsc_number; 
                    let detail = '<p><font color="#ce0000">For credit cards &amp; other ineligible options, kyc is mandatory.</font></p><p>Please deposit the above entered amount in the following account and upload screenshot</p><p> &amp; copy paste transaction reference number and submit to generate deposit request.</p><p>Beneficiary Name- ' + name + '</p><p>Account Number- ' + acc_no + '</p><p>IFSC Code- ' + ifsc + '</p>';
                    $('body').find('.detail').html(detail);
                } else {
                    $('body').find('.detail').html('');
                }
            });
        })



    </script>
@endsection
