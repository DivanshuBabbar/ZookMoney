<?php

namespace App\Service;

/**
 * BitPay payment process service
 *
 *---------------------------------------------------------------- */
class StrowalletService
{
    /**
     * @var configData - configData
     */
    protected $configData;

    /**
     * @var configItem - configItem
     */
    protected $configItem;
    protected $publicKey;
    /**
     * @var testMode - testMode
     */
    protected $testMode;
    protected $checkoutUrl;
    protected $callbackUrl;

    /**
     * Constructor.
     *
     *-----------------------------------------------------------------------*/
    public function __construct()
    {
        $this->configData       = configItem();
        $this->configItem       = getArrayItem($this->configData, 'payments.gateway_configuration.strowallet', []);
        // check of config item exists
        if (!empty($this->configItem)) 
        {
            $this->publicKey         = $this->configItem['livePublicApiKey'];
            $this->checkoutUrl         = $this->configItem['checkoutUrl'];
            $this->callbackUrl         = $this->configItem['callbackUrl'];
            $this->testMode         = $this->configItem['testMode'];
        }
    }
    public function proccesPayment($insertData,$cancel_url)
    {
        $amount = $insertData['amounts']['NGN'];
        $details = isset($insertData['item_name']) ? $insertData['item_name']:'';
        $order_id = isset($insertData['order_id']) ? $insertData['order_id']:'';
        $name = isset($insertData['payer_name']) ? $insertData['payer_name']:'';
        $email = isset($insertData['payer_email']) ? $insertData['payer_email']:'';
        $paymentOption = isset($insertData['paymentOption']) ? $insertData['paymentOption']:'';
        $success_url_parameters = array(
            'amount' => $amount,
            'currency' => 'NGN',
            'details' => $details,
            'custom' => $order_id,
            'paymentOption' => $paymentOption,
        );
        $endpoint_success_url = url($this->callbackUrl);
        $success_url = $endpoint_success_url . "?" . http_build_query($success_url_parameters);
        $parameters = array(
            'amount' => $amount,
            'currency' => 'NGN',
            'details' => $details,
            'custom' => $order_id,
            'ipn_url' => 'http://www.abc.com/ipn.php',
            'success_url' => $success_url,
            'cancel_url' => $cancel_url,
            'public_key' => $this->publicKey,
            'name' => $name,
            'email' => $email
        );
        $endpoint = $this->checkoutUrl;
        $call = $endpoint . "?" . http_build_query($parameters);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $call);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response);
        return (array)$response;
    }
}
