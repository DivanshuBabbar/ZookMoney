<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('front/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-jvectormap-2.0.3.min.css')}}"/>

    <style>
        body {
            margin: 0;
            overflow-x: hidden;
        }

        .payment-container * {
            font-family: 'Inter', sans-serif;
        }

        .payment-container {
            margin: 0;
            background: #FF8536;
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            font-size: large;
            overflow-x: hidden;
        }

        .payment-container .body-wrapper {
            width: 100%;
            background: #fff;
            border-radius: 15px;
            overflow: hidden; 
            overflow-y: auto; 
            height: auto; 
        }

        .payment-container .img-wrapper {
            margin: auto;
            text-align: center;
        }

        .payment-container .img-wrapper img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .payment-container .content-wrapper {
            text-align: center;
            padding: 20px;
        }

        .payment-container .bottom-txt {
            text-align: center;
            font-size: 16px;
            margin-top: 30px;
            font-weight: 400;
        }

        .input-wrapper {
            margin: auto;
            block-size: 40px;
            text-align: center;
            padding-bottom: 20px;
        }

        .validateLoginForm .form-group .btn-primary {
            line-height: 26px;
            font-size: 17px;
            max-width: 100%;
            margin-bottom: 11px;
            width: 11em;
            border: 1px solid coral;
            text-decoration: none;
            display: inline-block;
            color: aliceblue;
            background: coral;
            margin-top: 19px;
            font-weight: 400;
        }

        input[type="number"] { padding: 3px 3px; line-height: 18px; }

        .back-wrap {
            text-align: end;
            padding: 20px;
        }

        /* Media Queries for responsiveness */
        @media screen and (min-width: 768px) {
            .payment-container {
                padding: 30px 50px;
                flex-direction: row;
            }

            .payment-container .body-wrapper {
                width: 80vw;
                height: auto;
            }

            .payment-container .img-wrapper {
                margin-left: 40px;
                margin-right: 20px;
            }
        }

        @media screen and (min-width: 1024px) {
            .payment-container {
                padding: 30px 225px;
            }
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="body-wrapper">
            <div class="back-wrap">
                <a href="{{route('ipn.show_express_login',['language' => app()->getLocale(),'ref' => $ref ])}}" style="color: #000000;">{{__('Back to guest checkout')}}</a>
                <span><strong>X</strong></span>
            </div>
            <div class="content-wrapper">
                <div class="img-wrapper">
                    <img src="{{ general_setting('site_logo') }}" alt="logo" />
                </div>
                <b><p class="login-txt">Express Checkout</p></b>
                <form id="validateExpressLoginForm" class="form-horizontal form-wrapper col-sm-6 validateLoginForm" method="POST" action="{{ route('ipn.validate_express_login', app()->getLocale()) }}">
                    @csrf
                    <input type="hidden" name="ref" id="requestRef" value="{{$ref}}">
                    <input name="email" value="{{ $email }}" type="hidden" >
                    <div class="input-wrapper">
                        <input style="font-size: 1.0rem;text-align: center;" id="otp" type="text" class="form-control" name="otp" onkeypress="return onlyNumberKey(event)" required autofocus><br>
                        <span id="invalid_otp" style="color:red;" class="invalid-response text-danger"></span>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block btn-lg" style="border-radius: 15px;padding: 7px;border: 1px solid aliceblue;"> {{__('Verify')}}</button><br>
                        <button type="button" id="resendExpressOtp" data-action="{{ route('ipn.express_login', app()->getLocale()) }}" class="btn btn-primary btn-block btn-lg" style="border-radius: 15px;padding: 7px;border: 1px solid aliceblue;"> {{__('Resend')}}</button>
                        {{--<button type="button" onClick="window.location.reload();" class="btn btn-light btn-block btn-lg" 
                        style="font-weight: bold; margin-bottom: 20px">{{__('USE EMAIL AND PASSWORD INSTEAD')}}</button>--}}
                    </div>
                </form>
            </div>
            <p class="bottom-txt" style="margin-top:50px;">If account doesn't exist for provided email, new account will be created and password will be generated and shared on provided email.</p>
        </div>
    </div>
</body>
</html>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.1/dist/sweetalert2.all.min.js" integrity="sha256-Y16qmk55km4bhE/z6etpTsUnfIHqh95qR4al28kAPEU=" crossorigin="anonymous"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });
     const REF = $("#requestRef").val();

    $(document).off('click', '#resendExpressOtp').on('click', '#resendExpressOtp', function(e) {
            e.preventDefault();
            let form = $(this);
            let action = $(this).data('action');
            let formData = new FormData();
            formData.append('ref', REF);
            formData.append('email', $('input[name="email"]').val());
            $.ajax({
                url: action,
                type: 'post',
                dataType: 'json',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                  beforeSend: function() {
                    $(".lw-show-till-loading").show();
                    $(".flash").hide();
                    $('.invalid-response').text('');
                },
                complete: function() {
                    $(".lw-show-till-loading").hide();
                },
                success: function(response) {
                     Swal.fire({
                      title: "Success!",
                      text: "otp sent successfully!",
                      icon: "success"
                    });
                },
                error: function(response){
                    if (response.status == 200) {
                        Swal.fire({
                          title: "Success!",
                          text: "otp sent successfully!",
                          icon: "success"
                        });
                    }
                    $('.invalid-response').text('');
                    $('.invalid-response').text(response.responseJSON.message);
                     if (response.responseJSON.errors.otp) {
                        $('.invalid-response').text('');
                        $('.invalid-response').text(response.responseJSON.errors.otp[0]);
                    }
                }
               
            });
    })

    $(document).off('submit', '#validateExpressLoginForm').on('submit', '#validateExpressLoginForm', function(e) {
            e.preventDefault();
            let form = $(this);
            let action = $(this).attr('action');
            let formData = new FormData(form[0]);
            formData.append('ref', REF);
            $.ajax({
                url: action,
                type: 'post',
                dataType: 'json',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('.invalid-response').text('');
                },
                complete: function() {
                
                },
                success: function(response) {

                },
                error: function(response) {
                    if (response.status == 200) {
                        $('#otp').text('');
                       swal.fire({
                          title: 'Successfull',
                          html: 'Redirecting',
                          timer: 2000
                        }).then(function (result) {
                            window.location.href = "{{ route('ipn.show_login' , [app()->getLocale(),$ref] ) }}";
                        });
                    }
                    $('.invalid-response').text('');
                    $('.invalid-response').text(response.responseJSON.message);
                     if (response.responseJSON.errors.otp) {
                        $('.invalid-response').text('');
                        $('.invalid-response').text(response.responseJSON.errors.otp[0]);
                    }
                }
            });
        })

         function onlyNumberKey(evt) {
 
            // Only ASCII character in that range allowed
            let ASCIICode = (evt.which) ? evt.which : evt.keyCode
            if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57))
                return false;
            return true;
        }
       
</script>