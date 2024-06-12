<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = ['en/purchase/link','en/request/status','stro_webhook','send_money','register','exchange_currency','virtual_card','request_money','top_up_mobile','card_fund','login','order_gift_card','gatepay_process_payment','gatepay_callback','payment_gatepay_process_payment','payment_gatepay_callback','merchant_gatepay_process_payment','merchant_gatepay_callback','en/purchase/get-deposit-details','en/purchase/create-transaction'];

}
