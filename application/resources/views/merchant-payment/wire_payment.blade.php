@extends('layouts.payment')

@push('styles')
<style>
    .rotate {
        animation: rotation 2s infinite linear;
    }

    @keyframes rotation {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(359deg);
        }
    }

    #swal2-html-container {
        font-family: 'Roboto';
    }

    .form-container {
        max-width: 600px;
        margin: 0 auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        font-family: sans-serif !important;
    }

    .form-group {
        margin-bottom: 20px;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
    }

    .form-control,
    .dropdown-toggle {
        padding: 8px;
        font-size: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        margin-right: 10px;
    }

    .dropdown-toggle {
        background: url('https://cdn3.iconfinder.com/data/icons/fatcow/32x32_0760/arrow_down.png') no-repeat right #fff;
        background-position: 95%;
    }

    #upiSubmit {
        margin-top: 10px;
        background-color: #007bff;
        border: none;
        color: white;
        margin-bottom: 11px;
        cursor: pointer;
        border-radius: 4px;
        font-size: 16px;
        height: 41px !important;
        background: #ff8536;
        border: aliceblue;
        padding-left: 8px;
    }

    .important-note {
        background-color: lightyellow;
        width: 50%;
        margin: 0 auto;
        padding: 1em;
        box-sizing: border-box;
        text-align: center;
        font-size: 1em;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    @media (max-width: 768px) {
        .important-note {
            width: 70%;
            font-size: 0.9em;
        }
    }

    @media (max-width: 480px) {
        .form-container {
            width: 90%;
        }
        .important-note {
            width: 90%;
            font-size: 0.8em;
        }
    }
    .body-wrapper {
    margin-left: auto;
    margin-right: auto;
    width: calc(100% - 42px);
    box-sizing: border-box;
    }

    @media (max-width: 768px) {
        .body-wrapper {
            margin-left: 0;
            width: 90%;
        }
    }   
</style>
<link rel="stylesheet" href="{{ asset('assets/merchant-payment/css/payment-with-qr.css') }}" />
@endpush

@section('content')
    @include('flash')

    @php
        $discountEnabled = floatval($showAmount) < floatval($sourceAmount);
    @endphp

    <input type="hidden" id="requestRef" value="{{ $ref }}" >

    <div class="payment-container row">
        <div class="body-wrapper col-md-10" style="margin-top:3vh;" >
            <div class="img-wrapper">
                <img src="{{ general_setting('site_logo') }}" height="141px" width="141px" alt="logo" />
            </div>
            <div class="content-wrapper">
                <p class="m-logo">
                    {{-- merchant logo --}}
                    <img src="{{$merchant->logo ?? ''}}" class="rounded-circle" alt="" style="width: 100px;">
                    {{-- --}}
                </p>
                <p class="depositing-txt" style="margin-top:20px;">You are depositing &#8377; {{ $sourceAmount }}</p>
                <div class="discount-wrapper">
                    <div>
                        <p class="pay-txt">You have to pay </span></p>
                        <p class="pay-amt">&#8377; {{ $showAmount }}</p>
                    </div>
                </div>
                <p style="font-size:20px;">Please complete the transaction by adding beneficiary and transferring <br> to below account via IMPS/NEFT/RTGS within 24 hours</p>
                <h2>Payment Information</h2>
                <p><strong style="font-size:18px;">Beneficiary Name :   </strong style="font-size:18px;">XYZ</p>
                <p><strong style="font-size:18px;">Account Number :   </strong style="font-size:18px;">123456789</p>
                <p><strong style="font-size:18px;">IFSC Code :   </strong style="font-size:18px;">XYZ123</p>
                <p><strong style="font-size:18px;">Remark :   </strong style="font-size:18px;">XYZ123</p>
                <div class="important-note">
                    Important Note: Make sure the remark is added as the transaction note as it is when transferring.
                </div>
                <br>
            </div>
            <p class="bottom-txt">Do not close or refresh the page if you have made payment, you will be automatically redirected to the merchant website. If you have any issues with any transaction please email at <b>grievances@zookpe.com</b></p>
        </div>
    </div>
@endsection
