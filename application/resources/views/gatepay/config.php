<?php
$callback_url = '';
if(session()->has('gateway_callback_url'))
{
    $callback_url = session()->get('gateway_callback_url');
}
$techAppConfig = [
    /* Base Path of app
    ------------------------------------------------------------------------- */
    'base_url' =>  url('/'),

    /* Amount - if null amount input open in form
    ------------------------------------------------------------------------- */
    'amount' => null,

    'payments' => [
        /* Gateway Configuration key
        ------------------------------------------------------------------------- */
        'gateway_configuration' => [
            'paypal' => [
                'enable'                        => true,
                'testMode'                      => true, //test mode or product mode (boolean, true or false)
                'gateway'                       => 'Paypal', //payment gateway name
                'paypalSandboxBusinessEmail'        => general_setting('paypal_email'), //paypal sandbox business email
                'paypalProductionBusinessEmail'     => general_setting('paypal_email'), //paypal production business email
                'currency'                  => 'USD', //currency
                'currencySymbol'              => '$',
                'paypalSandboxUrl'          => 'https://www.sandbox.paypal.com/cgi-bin/webscr', //paypal sandbox test mode Url
                'paypalProdUrl'             => 'https://www.paypal.com/cgi-bin/webscr', //paypal production mode Url
                'notifyIpnURl'              => $callback_url, //paypal ipn request notify Url
                'cancelReturn'              => $callback_url, //cancel payment Url
                'callbackUrl'               => $callback_url, //callback Url after payment successful
                'privateItems'              => []
            ],
            'paytm' => [
                'enable'                    => false,
                'testMode'                  => true, //test mode or product mode (boolean, true or false)
                'gateway'                   => 'Paytm', //payment gateway name
                'currency'                  => 'INR', //currency
                'currencySymbol'              => '₹',
                'paytmMerchantTestingMidKey'       => 'Enter your Test Mid Key', //paytm testing Merchant Mid key
                'paytmMerchantTestingSecretKey'    => 'Enter your Test Secret Key', //paytm testing Merchant Secret key
                'paytmMerchantLiveMidKey'       => 'Enter your Live Mid Key', //paytm live Merchant Mid key
                'paytmMerchantLiveSecretKey'    => 'Enter your Live Secret Key', //paytm live Merchant Secret key
                'industryTypeID'            => 'Retail', //industry type
                'channelID'                 => 'WEB', //channel Id
                'website'                   => 'WEBSTAGING',
                'paytmTxnUrl'               => 'https://securegw-stage.paytm.in/theia/processTransaction', //paytm transaction Url
                'callbackUrl'               => 'payment-response.php', //callback Url after payment successful or cancel payment
                'privateItems'              => [
                    'paytmMerchantTestingSecretKey',
                    'paytmMerchantLiveSecretKey'
                ]
            ],
            'instamojo' => [
                'enable'                    => true,
                'testMode'                  => true, //test mode or product mode (boolean, true or false)
                'gateway'                   => 'Instamojo', //payment gateway name
                'currency'                  => 'INR', //currency
                'currencySymbol'              => '₹',
                'sendEmail'                 => false, //send mail (true or false)
                'instamojoTestingApiKey'           => general_setting('instamojo_ApiKey'), // instamojo testing API Key
                'instamojoTestingAuthTokenKey'     => general_setting('instamojo_AuthTokenKey'), // instamojo testing Auth token Key
                'instamojoLiveApiKey'           => general_setting('instamojo_ApiKey'), // instamojo live API Key
                'instamojoLiveAuthTokenKey'     => general_setting('instamojo_AuthTokenKey'), // instamojo live Auth token Key
                'instamojoSandboxRedirectUrl'   => 'https://test.instamojo.com/api/1.1/', // instamojo Sandbox redirect Url
                'instamojoProdRedirectUrl'      => 'https://www.instamojo.com/api/1.1/', // instamojo Production mode redirect Url
                'webhook'                   => 'http://instamojo.com/webhook/', // instamojo Webhook Url
                'callbackUrl'               => $callback_url, //callback Url after payment successful
                'privateItems'              => [
                    'instamojoTestingApiKey',
                    'instamojoTestingAuthTokenKey',
                    'instamojoLiveApiKey',
                    'instamojoLiveAuthTokenKey',
                    'instamojoSandboxRedirectUrl',
                    'instamojoProdRedirectUrl'
                ]
            ],
            'paystack' => [
                'enable'                    => true,
                'testMode'                  => true, //test mode or product mode (boolean, true or false)
                'gateway'                   => 'Paystack', //payment gateway name
                'currency'                  => 'NGN', //currency
                'currencySymbol'              => '₦',
                'paystackTestingSecretKey'         => general_setting('paystack_secret'), //paystack testing secret key
                'paystackTestingPublicKey'         => general_setting('paystack_public'), //paystack testing public key
                'paystackLiveSecretKey'         => general_setting('paystack_secret'), //paystack live secret key
                'paystackLivePublicKey'         => general_setting('paystack_public'), //paystack live public key
                'callbackUrl'               => $callback_url, //callback Url after payment successful
                'privateItems'              => [
                    'paystackTestingSecretKey',
                    'paystackLiveSecretKey'
                ]
            ],
            'stripe'    => [
                'enable'                    => true,
                'testMode'                  => true, //test mode or product mode (boolean, true or false)
                'gateway'                   => 'Stripe', //payment gateway name
                'locale'                    => 'auto', //set local as auto
                'allowRememberMe'           => false, //set remember me ( true or false)
                'currency'                  => 'USD', //currency
                'currencySymbol'              => '$',
                'paymentMethodTypes'         => [
                    // before activating additional payment methods
                    // make sure that these methods are enabled in your stripe account
                    // https://dashboard.stripe.com/settings/payments
                    'card',
                    // 'ideal',
                    // 'bancontact',
                    // 'giropay',
                    // 'p24',
                    // 'eps'
                ],
                'stripeTestingSecretKey'    => general_setting('stripe_secret'), //Stripe testing Secret Key
                'stripeTestingPublishKey'   => general_setting('stripe_public'), //Stripe testing Publish Key
                'stripeLiveSecretKey'       => general_setting('stripe_secret'), //Stripe Secret live Key
                'stripeLivePublishKey'      => general_setting('stripe_public'), //Stripe live Publish Key
                'callbackUrl'               => $callback_url, //callback Url after payment successful
                'privateItems'              => [
                    'stripeTestingSecretKey',
                    'stripeLiveSecretKey'
                ]
            ],
            'razorpay'    => [
                'enable'                    => true,
                'testMode'                  => true, //test mode or product mode (boolean, true or false)
                'gateway'                   => 'Razorpay', //payment gateway name
                'merchantname'              => 'John', //merchant name
                'themeColor'                => '#4CAF50', //set razorpay widget theme color
                'currency'                  => 'INR', //currency
                'currencySymbol'              => '₹',
                'razorpayTestingkeyId'      => general_setting('razorpay_keyId'), //razorpay testing Api Key
                'razorpayTestingSecretkey'  => general_setting('razorpay_Secretkey'), //razorpay testing Api Secret Key
                'razorpayLivekeyId'         => general_setting('razorpay_keyId'), //razorpay live Api Key
                'razorpayLiveSecretkey'     => general_setting('razorpay_Secretkey'), //razorpay live Api Secret Key
                'callbackUrl'               => $callback_url, //callback Url after payment successful'
                'privateItems'              => [
                    'razorpayTestingSecretkey',
                    'razorpayLiveSecretkey'
                ]
            ],
            'iyzico'    => [
                'enable'                    => false,
                'testMode'                  => true, //test mode or product mode (boolean, true or false)
                'gateway'                   => 'Iyzico', //payment gateway name
                'conversation_id'           => 'CONVERS' . uniqid(), //generate random conversation id
                'currency'                  => 'TRY', //currency
                'currencySymbol'              => '₺',
                'subjectType'               => 1, // credit
                'txnType'                   => 2, // renewal
                'subscriptionPlanType'      => 1, //txn status
                'iyzicoTestingApiKey'       => 'Enter your Test Api Key', //iyzico testing Api Key
                'iyzicoTestingSecretkey'    => 'Enter your Test Secret Key', //iyzico testing Secret Key
                'iyzicoLiveApiKey'          => 'Enter your Live Api Key', //iyzico live Api Key
                'iyzicoLiveSecretkey'       => 'Enter your Live Secret Key', //iyzico live Secret Key
                'iyzicoSandboxModeUrl'      => 'https://sandbox-api.iyzipay.com', //iyzico Sandbox test mode Url
                'iyzicoProductionModeUrl'   => 'https://api.iyzipay.com', //iyzico production mode Url
                'callbackUrl'               => 'payment-response.php', //callback Url after payment successful
                'privateItems'              => [
                    'iyzicoTestingApiKey',
                    'iyzicoTestingSecretkey',
                    'iyzicoLiveApiKey',
                    'iyzicoLiveSecretkey'
                ]
            ],
            'authorize-net'    => [
                'enable'                         => false,
                'testMode'                       => true, //test mode or product mode (boolean, true or false)
                'gateway'                        => 'Authorize.net', //payment gateway name
                'reference_id'                   => 'REF' . uniqid(), //generate random conversation id
                'currency'                       => 'USD', //currency
                'currencySymbol'                 => '$',
                'type'                           => 'individual',
                'txnType'                        => 'authCaptureTransaction',
                'authorizeNetTestApiLoginId'     => 'Enter your Test API Login Id', //authorize-net testing Api login id
                'authorizeNetTestTransactionKey' => 'Enter your Test Secret Transaction Key', //Authorize.net testing transaction key
                'authorizeNetLiveApiLoginId'     => 'Enter your Live API Login Id', //Authorize.net live Api login id
                'authorizeNetLiveTransactionKey' => 'Enter your Live Secret Transaction Key', //Authorize.net live transaction key
                'callbackUrl'                    => 'payment-response.php', //callback Url after payment successful
                'privateItems'                  => [
                    'authorizeNetTestApiLoginId',
                    'authorizeNetTestTransactionKey',
                    'authorizeNetLiveApiLoginId',
                    'authorizeNetLiveTransactionKey'
                ]
            ],
            'bitpay'    => [
                'enable'                        => false,
                'testMode'                      => true, //test mode or product mode (boolean, true or false)
                'notificationEmail'             => 'osl.com', // Merchant Email
                'gateway'                       => 'BitPay', //payment gateway name
                'currency'                      => 'USD', //currency
                'currencySymbol'                => '$', //currency Symbol
                'password'                      => 'Ja311', // Password for "EncryptedFilesystemStorage"
                'pairingCode'                   => 'D09CEdd', // Your pairing Code
                'pairinglabel'                  => 'zookpe', // Your Pairing Label
                'callbackUrl'                   => $callback_url, //callback Url after payment successful
                'privateItems'                  => ['pairingCode', 'pairinglabel', 'password']
            ],
            'mercadopago' => [
                'enable'                        => false,
                'testMode'                      => true, //test mode or product mode (boolean, true or false)
                'gateway'                       => 'Mercado Pago', //payment gateway name
                'currency'                      => 'USD', //currency
                'currencySymbol'                => '$', //currency Symbol
                'testAccessToken'               => 'Your Test Access Token',
                'liveAccessToken'               => 'Your Live Access Token',
                'callbackUrl'                   => 'payment-response.php', //callback Url after payment successful
                'privateItems'                  => ['testAccessToken', 'liveAccessToken']
            ],
            'payumoney' => [
                'enable'                        => false,
                'testMode'                      => true, //test mode or product mode (boolean, true or false)
                'gateway'                       => 'PayUmoney', //payment gateway name
                'currency'                      => 'INR', //currency
                'currencySymbol'                => '₹', //currency Symbol
                'txnId'                         => "Txn" . rand(10000, 99999999),
                'merchantTestKey'               => 'Your Test Merchant Key',
                'merchantTestSalt'              => 'Your Test Salt Key',
                'merchantLiveKey'               => 'Your Live Merchant Key',
                'merchantLiveSalt'              => 'Your Live Salt Key',
                'callbackUrl'                   => 'payment-response.php', //callback Url after payment successful
                'checkoutColor'                 => 'e34524',
                'checkoutLogo'                  => 'http://boltiswatching.com/wp-content/uploads/2015/09/Bolt-Logo-e14421724859591.png',
                'privateItems'                  => ['merchantTestKey', 'merchantTestSalt', 'merchantLiveKey', 'merchantLiveSalt']
            ],
            'mollie' => [
                'enable'                        => true,
                'testMode'                      => true, //test mode or product mode (boolean, true or false)
                'gateway'                       => 'Mollie', //payment gateway name
                'currency'                      => 'EUR', //currency
                'currencySymbol'                => '€', //currency Symbol
                'testApiKey'                    => general_setting('Mollie_ApiKey'),
                'liveApiKey'                    => general_setting('Mollie_ApiKey'),
                'callbackUrl'                   => $callback_url, //callback Url after payment successful
                'privateItems'                  => ['testApiKey', 'liveApiKey']
            ],
            'ravepay' => [
                'enable'                        => true,
                'testMode'                      => true, //test mode or product mode (boolean, true or false)
                'gateway'                       => 'Ravepay', //payment gateway name
                'currency'                      => 'NGN', //currency
                'currencySymbol'                => '₦', //currency Symbol
                'txn_reference_id'              => 'REF' . uniqid(), //generate random conversation id
                'testPublicApiKey'              => general_setting('flutter_public'),
                'testSecretApiKey'              => general_setting('flutter_secret'),
               'livePublicApiKey'              =>  general_setting('flutter_public'),
               'liveSecretApiKey'              =>  general_setting('flutter_secret'),
                'callbackUrl'                   => $callback_url, //callback Url after payment successful
                'sandboxVerifyPaymentUrl'       => 'https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/v2/verify', //sandbox staging server verify payment url.
                 'productionVerifyPaymentUrl'    => 'https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify',
                'privateItems'                  => ['testSecretApiKey']
                // 'privateItems'                  => ['testSecretApiKey', 'liveSecretApiKey']
            ],
            'strowallet' => [
                'enable'                        => true,
                'testMode'                      => true, //test mode or product mode (boolean, true or false)
                'gateway'                       => 'strowallet', //payment gateway name
                'currency'                      => 'NGN', //currency 
                'currencySymbol'                => '₦', //currency Symbol
                'txn_reference_id'              => 'REF' . uniqid(), //generate random conversation id
                'livePublicApiKey'              => general_setting('stro_publickey'),
                'checkoutUrl'                   => 'https://strowallet.com/express/initiate',
                'callbackUrl'                   => $callback_url,
                'privateItems'                  => ['testSecretApiKey', 'liveSecretApiKey']
            ],
            'pagseguro' => [
                'enable'                        => false,
                'testMode'                      => true, //test mode or product mode (boolean, true or false)
                'gateway'                       => 'Pagseguro', //payment gateway name
                'environment'                   => 'sandbox', //production, sandbox
                'currency'                      => 'BRL', //currency
                'currencySymbol'                => 'R$', //currency Symbol
                'reference_id'                  => 'REF' . uniqid(), //generate random reference id
                'email'                         => 'Your PagSeguro Email id', //your pagseguro email id for create account credentials
                'testToken'                     => 'Your Test Production Token', //your sandbox pagseguro token for create account credentials
                'liveToken'                     => 'Your Live Production Token', //your production pagseguro token for create account credentials
                'callbackUrl'                   => 'payment-response.php', //callback Url after payment successful
                'notificationUrl'               => 'payment-response.php', //notification url when payment successfully user collect notfication data
                'privateItems'                  => ['liveToken', 'testToken']
            ],
        ],
    ],
];
return compact("techAppConfig");
