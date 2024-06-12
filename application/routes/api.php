<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('v1/validate-transaction', 'Api\MerchantPaymentApiController@validateTransaction');
Route::post('v1/validate-qr-transaction', 'Api\MerchantPaymentApiController@validateQrTransaction');


Route::prefix('api')->group(function () {
    Route::get('api/get_currency', 'Api\ApiController@index')->name('api.get_currency');
});

Route::middleware('api')->group(function () {

    // alternate merchant key based send money
    Route::prefix('money-transfer')->group(function () {
        Route::post('/send-money', 'Api\ApiController@send_money');
    });

    //sendMoney
    Route::get('/get_currency/{id}', 'Api\ApiController@index')->name('get_currency');
    Route::post('/send_money/{id}', 'Api\ApiController@send_money')->name('send_money');

    //Registerition
    Route::get('/get_country/{id}', 'Api\ApiController@get_country')->name('get_country');
    Route::post('/register', 'Api\ApiController@register')->name('register');

    //exchange_currency//
    Route::post('/exchange_currency/{id}', 'Api\ApiController@exchange_currency')->name('exchange_currency');

    //virtual card
    Route::post('/virtual_card/{id}', 'Api\ApiController@virtual_card')->name('virtual_card');
    Route::get('/card_list/{id}', 'Api\ApiController@card_list')->name('card_list');
    Route::get('/card_details/{id}/{user_id}', 'Api\ApiController@card_details')->name('card_details');
    Route::post('/card_fund/{id}/{user_id}', 'Api\ApiController@card_fund')->name('card_fund');

    //request money
    Route::post('/request_money/{id}', 'Api\ApiController@request_money')->name('request_money');

    //mobile up 

    Route::get('/get_country_currency/{id}', 'Api\ApiController@get_country_currency')->name('get_country_currency');
    Route::get('/getOperatores/{id}/{country_code}/{currency_code}', 'Api\ApiController@getOperatores')->name('getOperatores');
    Route::post('/top_up_mobile/{id}', 'Api\ApiController@top_up_mobile')->name('top_up_mobile');

    //login
    Route::post('/login', 'Api\ApiController@login')->name('login');

    //gift card
    Route::get('/get_gift_cards/{id}/{code}/{country_id}', 'Api\ApiController@get_gift_cards')->name('get_gift_cards');
    Route::get('/gift_cards_price/{id}/{code}/{country_id}/{product_id}', 'Api\ApiController@gift_cards_price')->name('gift_cards_price');
    Route::post('/order_gift_card/{id}', 'Api\ApiController@order_gift_card')->name('order_gift_card');

    //deposit method

    Route::get('/get_deposit_payment_method/{id}', 'Api\DepositApiController@get_deposit_payment_method')->name('get_deposit_payment_method');
    Route::post('/deposit_api/{id}', 'Api\DepositApiController@deposit_api')->name('deposit_api');

    //withdraw
    Route::get('/get_withdraw_payment_method/{id}', 'Api\WithdrawApiController@get_withdraw_payment_method')->name('get_withdraw_payment_method');
    Route::post('/withdraw_api/{id}', 'Api\WithdrawApiController@withdraw_api')->name('withdraw_api');
    
   //get transaction
    Route::get('get_transaction/{id}', 'Api\ApiController@get_transaction')->name('get_transaction');
    Route::get('get_wallet/{id}', 'Api\ApiController@get_wallet')->name('get_wallet');

});

