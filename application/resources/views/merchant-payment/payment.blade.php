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
    #swal2-html-container{
        font-family:'Roboto';
    }
    /*input#user_symbol {
        width: 24px;
    }*/
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
        justify-content: center; /* Align items horizontally in the center */
        align-items: center; /* Align items vertically in the center */
    }

    .form-control {
/*        flex: 1;*/
        padding: 8px;
        font-size: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        margin-right: 10px;
    }

    .dropdown-toggle {
/*        flex: 1;*/
        padding: 8px;
        font-size: 15px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        appearance: none;
        -webkit-appearance: none;
        background: url('https://cdn3.iconfinder.com/data/icons/fatcow/32x32_0760/arrow_down.png') no-repeat right #fff;
        background-position: 95%;
        margin-right: 10px;
    }

      #upiSubmit { 
        margin-top:10px;
        background-color: #007bff;
        border: none;
        color: white;
        margin-bottom: 11px; 
        cursor: pointer;
        border-radius: 4px;
        font-size: 16px;
        height: 41px !important;
        background: #FF8536;
        border:aliceblue; 
        padding-left: 8px;

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

    <div class="payment-container row" @if(empty($loginRequired)) id="style-element" @endif>
        <div class="body-wrapper col-md-8">
            <div class="img-wrapper">
                <img src="{{ general_setting('site_logo') }}" height="141px" width="141px" alt="logo" />
            </div>
            <div class="content-wrapper">
                <p class="m-logo">
                    {{-- mercant logo --}}
                     <img src="{{$merchant->logo ?? ''}}" class="rounded-circle" alt="" style="width: 100px;">
                    {{-- -- --}}
                </p>
                <img src="{{ $qr }}" alt="qr" height="180" width="180" />
                <p class="depositing-txt">You are depositing &#8377; {{ $sourceAmount }}</p>
                <div class="discount-wrapper" @if($discountEnabled)style="margin-left: 152px;"@endif>
                    <div>
                        <p class="pay-txt">You have to pay </span></p>
                        <p class="pay-amt">&#8377; {{ $showAmount }}</p>
                        <img class="rotate" src="{{ asset('assets/merchant-payment/img/refresh.png') }}" style="margin-top: 17px;" height="51px" width="51px"
                            alt="refresh-img" />
                        <p class="timer" id="counter">5:40</p>
                    </div>

                    {{-- discount stamp --}}
                    @if($discountEnabled)
                        <div>
                            <img src="{{ asset('assets/merchant-payment/img/discount.png') }}" height="152px" width="152px" alt="discount-img" />
                        </div>
                    @endif
                    {{-- -- --}}
                </div>
                <p class="upi-txt">Use your favourite UPI app to <br /> make the payment and wait.</p>
                <h3 class="upi-txt">UPI INTENT</h3>
                <div class="upi-wrapper">
                    <img src="{{ asset('assets/merchant-payment/img/upi1.png') }}" class="upiImage" style="margin-top: 34px; margin-right: 45px;" />
                    <img src="{{ asset('assets/merchant-payment/img/upi2.png') }}" style="margin-top: 34px;" />
                </div>
                <div>
                    <p class="upi-txt">OR</p>
                </div>
                <br>
                <div class="form-container">
                    <form>
                        <div class="form-group">
                            <input type="text" name="user_input" id="user_input_val" class="form-control">
                            <input type="text" name="fixed" id="user_symbol" value="@" class="form-control" disabled>
                            <select name="upi" id="upi-select" class="dropdown-toggle">
                              <option value="rbl">rbl</option>
                              <option value="idbi">idbi</option>
                              <option value="upi">upi</option>
                              <option value="aubank">aubank</option>
                              <option value="axisbank">axisbank</option>
                              <option value="indus">indus</option>
                              <option value="federal">federal</option>
                              <option value="sbi">sbi</option>
                              <option value="uco">uco</option>
                              <option value="yesbank">yesbank</option>
                              <option value="citi">citi</option>
                              <option value="citigold">citigold</option>
                              <option value="okhdfcbank">okhdfcbank</option>
                              <option value="okaxis">okaxis</option>
                              <option value="okicici">okicici</option>
                              <option value="idbi">idbi</option>
                              <option value="icici">icici</option>
                              <option value="kotak">kotak</option>
                              <option value="paytm">paytm</option>
                              <option value="ybl">ybl</option>
                              <option value="axl">axl</option>
                            </select>
                            <button type="submit" id="upiSubmit"> Send Request </button>
                        </div>
                       
                    </form>
                </div>
            </div>
          
    
            <p class="bottom-txt">Do not close or refresh the page if you have made payment, you will be automatically
                redirected to the merchant website. If you have any issues with any transaction please email at
                <b>grievances@zookpe.com</b>
            </p>
        </div>
        @if(!empty($loginRequired))
            <div class="login-wrapper alignment col-md-4">
                <p class="already-txt">Already have funds in your {{ general_setting('site_name') }} Account? <br />
                    Want to use?</p>
        
                <div class="btn-wrapper">
                   <a href="{{ route('ipn.show_login', [app()->getLocale(),$ref]) }}"><button class="btn">Login</button></a>
                    <a href="{{ route('ipn.show_express_login', [app()->getLocale(),$ref]) }}"><button class="btn">Express Checkout</button></a>
                </div>
            </div>
        @endif
    </body>

@endsection

@push('scripts')
<script src="{{ asset('assets/payment/js/HackTimer.silent.min.js')}}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        const REF = $("#requestRef").val();
        let totalTime = 60 * 5; //in minute
        let counterElem = document.querySelector('#counter');
        let validateInterval = 10; // in second
        var it_works = false;
        
        function Timer(funct, delayMs, times) {
            if (times == undefined) {
                times = -1;
            }
            if (delayMs == undefined) {
                delayMs = 10;
            }
            this.funct = funct;
            var times = times;
            var timesCount = 0;
            var ticks = (delayMs / 10) | 0;
            var count = 0;
            Timer.instances.push(this);

            this.tick = function () {
                if (count >= ticks) {
                    this.funct(timesCount);
                    count = 0;
                    if (times > -1) {
                        timesCount++;
                        if (timesCount >= times) {
                            this.stop();
                        }
                    }
                }
                count++;
            };

            this.stop = function () {
                var index = Timer.instances.indexOf(this);
                Timer.instances.splice(index, 1);
            };
        }

        Timer.instances = [];

        Timer.ontick = function () {
            for (var i in Timer.instances) {
                Timer.instances[i].tick();
            }
        };

        window.setInterval(Timer.ontick, 10);

        function setCountdown(tick) {

            minutes = parseInt((totalTime - (tick + 1)) / 60, 10);
            seconds = parseInt((totalTime - (tick + 1)) % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            counterElem.textContent = minutes + ":" + seconds;
        }
        function validateTransaction(tick) {
           
            let elapsedTime = (tick + 1) * validateInterval;

            let formData = new FormData();
            //removed $merchantKey
            formData.append('token', "{{ $ref }}");
            formData.append('sourceAmount', "{{ $sourceAmount}}");
            formData.append('user_id', "{{ $userId}}");
            formData.append('email', "{{ $email}}");
            formData.append('type', "1");

            if(elapsedTime > totalTime) {
                return;
            }
            if(it_works == false){
                $.ajax({
                    url: "{{ url('api/v1/validate-qr-transaction') }}",
                    type: 'post',
                    dataType: 'json',
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {},
                    complete: function() {},
                    success: function(response) {
                        let msg;
                        let icon;
                        let timerInterval;
                    
                        if(response.transaction_status == 'Completed') {
                            it_works = true;
                            Swal.fire({
                                icon: 'success',
                                title: response.success_message,
                                html: "Request Reference No: {{ $ref }}. <br/> Redireting to merchant website in <b></b> seconds.<br/>If you have any issues with any transaction please email at <strong>grievances@zookpe.com</strong>",
                                timer: 5000,
                                timerProgressBar: true,
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                    const timer = Swal.getPopup().querySelector("b");
                                    timerInterval = setInterval(() => {
                                        timer.textContent = `${parseInt(Swal.getTimerLeft() / 1000)}`;
                                    }, 100);
                                },
                                willClose: () => {
                                    clearInterval(timerInterval);
                                }
                            }).then((result) => {
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    window.location.replace("{{ $merchantRedirect }}");
                                }
                            });

                        } else {
                            if(elapsedTime >= totalTime) {
                                Swal.fire({
                                    icon: 'error',
                                    title: response.error_message,
                                    html: "Request Reference No: {{ $ref }}. <br/> Redireting to merchant website in <b></b> seconds.<br/>If you have any issues with any transaction please email at <strong>grievances@zookpe.com</strong>",
                                    timer: 5000,
                                    timerProgressBar: true,
                                    didOpen: () => {
                                        Swal.showLoading();
                                        const timer = Swal.getPopup().querySelector("b");
                                        timerInterval = setInterval(() => {
                                            timer.textContent = `${parseInt(Swal.getTimerLeft() / 1000)}`;
                                        }, 100);
                                    },
                                    willClose: () => {
                                        clearInterval(timerInterval);
                                    }
                                }).then((result) => {
                                    if (result.dismiss === Swal.DismissReason.timer) {
                                        window.location.replace("{{ $merchantRedirect }}");
                                    }
                                });
                            }
                        }
                    },
                    error: function(response) {}
                });
            }
        }
       
        let countDownTimer = new Timer(setCountdown, 1000, totalTime);
        let validationTimer = new Timer(validateTransaction, 1000 * validateInterval, totalTime);

    </script>
    <script>
        $('#upiSubmit').click( function(e) {
            e.preventDefault();
            var upi_select = $('#upi-select').val();
            var user_input = $('#user_input_val').val();
            var user_symbol = $('#user_symbol').val();

            var upi_id = user_input+user_symbol+upi_select;
            var amount = {{$showAmount}};
            var token = {{ $ref }};
            
            if (user_input == '') {
                alert('Field cannot be empty');
                return false;
            }
            var xhr = new XMLHttpRequest();
            xhr.withCredentials = true;

            xhr.addEventListener("readystatechange", function() {
              if(this.readyState === 4) {
                console.log(this.responseText);
              }
            });

            xhr.open("POST", "https://ap-south-1.aws.data.mongodb-api.com/app/application-0-arvdg/endpoint/upi_link_request?vpa="+upi_id+"&token="+token+"&amount="+amount);

            xhr.send();
            alert('Request Sent Successfully');
             $('#upi-select').val('');
             $('#user_input_val').val('');
          
        });
    </script>
    <script>
        // window.addEventListener('beforeunload', function (e) {
        //   // Cancel the event
        //   e.preventDefault(); // If you prevent default behavior in Mozilla Firefox prompt will always be shown
        //   // Chrome requires returnValue to be set
        //   e.returnValue = 'are you sure ?';
        // });
        function disableF5(e) { if ((e.which || e.keyCode) == 116 || (e.which || e.keyCode) == 82) e.preventDefault(); };

        $(document).ready(function(){
             $(document).on("keydown", disableF5);
        });
    </script>
@endpush