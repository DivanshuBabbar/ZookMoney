<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('front/css/style.css') }}" />
    <style>
        .payment-container * {
            font-family: 'Inter', sans-serif;
        }

        .payment-container {
            margin: 0;
            background: #FF8536;
            padding: 30px 225px;
            display: flex;
            padding-right: 0;
            font-size: large;
        }

        .payment-container .body-wrapper {
            height: calc(100vh - 78px);
            width: calc(80vw - 72px);
            background: #fff;
            border-radius: 15px;
            overflow: auto;
        }

        .payment-container .img-wrapper {
            margin-left: 40px;
        }

        .payment-container .content-wrapper {
            text-align: center;
        }

        .payment-container .bottom-txt {
            padding-left: 60px;
            padding-right: 60px;
            text-align: center;
            font-size: 16px;
            margin-top: 30px;
            font-weight: 400;
        }

        .input-wrapper {
            margin: auto;
            block-size: 40px;
        }

        input[type="email"] {
            padding: 3px 3px;
            line-height: 18px;
        }

        @media screen and (max-width: 768px) {
            .payment-container {
                padding: 30px 15px;
                justify-content: center;
                width: 100%;
                box-sizing: border-box; 
            }

            .payment-container .body-wrapper {
                height: auto;
                width: 100%;
                max-width: 500px; 
                margin: 0 auto; 
                padding: 20px;
                overflow: hidden;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                background: #fff; 
                border-radius: 15px; 
            }

            .payment-container .img-wrapper {
                margin-left: 0;
                margin-bottom: 20px;
                text-align: center;
            }

            .payment-container .img-wrapper img {
                max-width: 100%;
                height: auto;
            }

            .payment-container .bottom-txt {
                padding-left: 20px;
                padding-right: 20px;
                text-align: center;
                font-size: 16px;
                margin-top: 30px;
                font-weight: 400;
            }

            .input-wrapper {
                block-size: auto;
                width: 100%; 
                max-width: 300px; 
                margin: 0 auto;
            }

            input[type="email"] {
                width: 100%;
                padding: 10px;
                border-radius: 5px;
                border: 1px solid #ccc;
                box-sizing: border-box;
            }
        }
    </style>
</head>
<body>

<div class="payment-container">
    <div class="body-wrapper">
        <div class="content-wrapper">
            <div class="img-wrapper">
                <img src="{{ general_setting('site_logo') }}" alt="logo" />
            </div>
            <b><p class="login-txt">Express Checkout</p></b>
            <form id="expressLoginForm" class="form-horizontal form-wrapper col-sm-6"
                  method="POST"
                  action="{{ route('ipn.express_login', app()->getLocale()) }}">
                @csrf
                <input type="hidden" name="ref" id="requestRef" value="{{$ref}}">

                <div class="input-wrapper">
                    <input id="email" type="email" class="form-control" name="email"
                           value="{{ old('email') }}" required autofocus placeholder="E-Mail Address">
                    <span id="invalid_email" class="invalid-response text-danger"></span>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block btn-lg" style="font-weight: bold;background: coral; width: 11em; line-height: 30px; border: none; height: 3em; margin-top: 7px; color: aliceblue; border-radius: 15px;">
                        {{__('SEND OTP')}}
                    </button>
                    {{--<button type="button" onClick="window.location.reload();" class="btn btn-light btn-block btn-lg"
                            style="font-weight: bold; margin-bottom: 20px">{{__('USE EMAIL AND PASSWORD INSTEAD')}}</button>--}}
                </div>
            </form>
        </div><br>
        <p class="bottom-txt">If an account doesn't exist for the provided email, a new account will be created, and a password will be generated and shared on the provided email.</p>
    </div>
</div>

</body>
</html>
