<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Admin\CurrencyExchangeController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


Artisan::call('storage:link');
Route::any('/stro_webhook', 'StrovirtualaccountController@stro_webhook')->name('stro_webhook');
Route::get('loan/return_loan', 'LoanController@return_loan')->name('loan.return_loan');
Route::any('/gatepay_callback', 'GatepayController@gatepay_callback')->name('gatepay_callback');
Route::post('/gatepay_process_payment', 'GatepayController@gatepay_process_payment')->name('gatepay_process_payment');
Route::post('payment_gatepay_process_payment', 'PaymentlLinkGatePayController@payment_gatepay_process_payment')->name('payment_gatepay_process_payment');
Route::any('payment_gatepay_callback', 'PaymentlLinkGatePayController@payment_gatepay_callback')->name('payment_gatepay_callback');
Route::any('payment_gatepay_cancel', 'PaymentlLinkGatePayController@payment_gatepay_cancel')->name('payment_gatepay_cancel');

Route::post('merchant_gatepay_process_payment', 'MerchantPaymentGatePayController@merchant_gatepay_process_payment')->name('merchant_gatepay_process_payment');
Route::any('merchant_gatepay_callback', 'MerchantPaymentGatePayController@merchant_gatepay_callback')->name('merchant_gatepay_callback');

Route::redirect('/', '/en');
Route::get('/migrate/artisan', function(){
	$result = Artisan::call('migrate');
	dd($result);
});

// Route::get('/lang/{lang}', function ($locale){
// 	Session::put('locale', $locale);
//        return redirect('/');
// });



define('GIFT_CARD_API_URL', 'https://giftcards.reloadly.com');


 define('STRO_WALLET_API_URL','https://strowallet.com');
 define('STRO_PUBLIC_KEY','KHND78WDP');
Route::get('en/login', 'Auth\LoginController@showLoginForm')->name('enlogin');
Route::get('/checkpayout','PayoutController@index');

Route::namespace('Admin')->prefix('administrator')->name('admin.')->group(function () {
    Route::namespace('Auth')->group(function () {
        Route::get('/login', 'LoginController@showLoginForm')->name('login');
        Route::post('/checklogin', 'LoginController@checklogin')->name('checklogin');
        Route::post('/logout', 'LoginController@logout')->name('logout');
        // Admin Password Reset
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'ForgotPasswordController@sendResetLinkEmail');
        Route::post('password/verify-code', 'ForgotPasswordController@verifyCode')->name('password.verify-code');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.change-link');
        Route::post('password/reset/change', 'ResetPasswordController@reset')->name('password.change');
    });
    Route::group(['middleware'=>['admin']],function () {
	    Route::get('new_dashboard', 'DashboardController@new_dashboard')->name('new_dashboard');
		Route::get('/getCounts', 'DashboardController@getCounts')->name('getCounts');
		Route::get('/get_today_stats', 'DashboardController@getTodayStats')->name('gettodaystats');
		Route::get('/get_overall_stats', 'DashboardController@getOverallStats')->name('getoverallstats');
		Route::post('/get_overall_stats_refresh', 'DashboardController@getOverallStatsRefresh')->name('getoverallstatsrefresh');
		Route::get('/get_monthly_stats', 'DashboardController@getMonthlyStats')->name('getmonthlystats');
		Route::get('/get_chargeback_stats', 'DashboardController@getChargebackStats')->name('getchargebackstats');
		Route::post('/get_chargeback_stats_refresh', 'DashboardController@getChargebackStatsRefresh')->name('getchargebackstatsrefresh');

		Route::get('/get_transaction_stats', 'DashboardController@getTransactionStats')->name('gettransactionstats');
	    Route::get('user/list', 'UserController@user_list')->name('user.list');
		Route::post('user/changestatus', 'UserController@changestatus')->name('changestatus');
	    Route::get('user/edit/{id}', 'UserController@user_edit')->name('user.edit');
	    Route::post('user/update/{id}', 'UserController@user_update')->name('user.update');
	    Route::get('/blockuser', 'UserController@accountStatus')->name('blockuser');
	    //User Kyc
	    Route::get('user/kyc/list', 'UserController@userKfcList')->name('user.kyc.list');
	    Route::get('change/status/{id?}/{val?}', 'UserController@changeKycStatus')->name('change.kyc.status');
	    //Transaction
	    Route::get('transaction/list', 'TransactionController@index')->name('transaction.list');
		Route::get('transaction/chargeback', 'TransactionController@chargeBack')->name('chargeback');
	    Route::get('transaction/delete/{id?}', 'TransactionController@delete')->name('transaction.delete');
	    //Currency
	    Route::get('currency/list', 'CurrencyController@currency_list')->name('currency.list');
	    Route::get('add/currency', 'CurrencyController@add_currency')->name('add.currency');
	    Route::post('store/currency', 'CurrencyController@store')->name('store.currency');
	    Route::get('edit/currency/{id?}', 'CurrencyController@edit')->name('edit.currency');
	    Route::post('update/currency', 'CurrencyController@update')->name('update.currency');
	    Route::get('delete/currency/{id?}', 'CurrencyController@delete')->name('delete.currency');

		// Currency exhchange
		Route::get('currency/exchange/list', 'CurrencyExchangeController@index')->name('exchange-rate.list');
		Route::get('currency/exchange/add','CurrencyExchangeController@create')->name('exchange-rate.create');
		Route::post('currency/exchange/store', 'CurrencyExchangeController@store')->name('exchange-rate.store');
		Route::get('currency/exchange_edit/{id}','CurrencyExchangeController@edit')->name('exchange-rate.edit');
		Route::post('currency/exchange_update/{id}','CurrencyExchangeController@update')->name('exchange-rate.update');
		Route::get('currency/exchange_delete/{id?}','CurrencyExchangeController@delete')->name('exchange-rate.delete');


		// Merchant
		Route::get('merchant/list', 'MerchantController@index')->name('merchant.list');
		Route::get('edit/merchant/{id?}', 'MerchantController@edit')->name('edit.merchant');
		Route::post('update/merchant', 'MerchantController@update')->name('update.merchant');
		Route::get('delete/merchant/{id?}', 'MerchantController@delete')->name('delete.merchant');
		Route::get('merchant/detail/{id?}', 'MerchantController@detail')->name('merchant.detail');
		Route::get('bulkfileupload', 'MerchantController@bulkfileupload')->name('bulkfileupload');
		
		// Escrow
		Route::get('escrow/list', 'EscrowController@index')->name('escrow.list');
		//setting
		Route::get('setting', 'SettingController@index')->name('setting');
		Route::post('post_setting', 'SettingController@post_setting')->name('post_setting');


		// country
		Route::get('countries/list', 'CountryController@index')->name('countries.list');
		Route::get('country/add','CountryController@create')->name('countries.create');
		Route::post('country/store', 'CountryController@store')->name('countries.store');
		Route::get('country/edit/{id}','CountryController@edit')->name('countries.edit');
		Route::post('country/update/{id}','CountryController@update')->name('countries.update');
		Route::get('country/delete/{id?}','CountryController@delete')->name('countries.delete');

		// Deposits 
		Route::get('deposits/list', 'DepositController@index')->name('deposits.list');
		Route::get('deposit/edit/{id}','DepositController@edit')->name('deposits.edit');
		Route::post('deposit/update/{id}','DepositController@update')->name('deposits.update');
		// Route::get('deposit/delete/{id?}','DepositController@delete')->name('deposits.delete');
		Route::get('deposit/detail/{id?}', 'DepositController@display')->name('deposits.view');

		//Deposit Method
		Route::get('deposit/method.list', 'DepositMethodController@index')->name('deposit.method.list');
		Route::get('add/deposit/method', 'DepositMethodController@add')->name('add.deposit.method');
		Route::post('save/deposit/method', 'DepositMethodController@save')->name('save.deposit.method');
		Route::get('edit/deposit/method/{id?}', 'DepositMethodController@edit')->name('edit.deposit.method');
		Route::get('delete/deposit/method/{id?}', 'DepositMethodController@delete')->name('delete.deposit.method');

		//Withdrawal
		Route::get('withdrawal/list', 'WithdrawalController@index')->name('withdrawal.list');
		Route::get('withdrawal/edit/{id?}', 'WithdrawalController@edit')->name('withdrawal.edit');
		Route::get('withdrawal/detail/{id?}', 'WithdrawalController@detail_view')->name('withdrawal.detail');
		Route::post('withdrawal/withdrawal_update/{id}', 'WithdrawalController@withdrawal_update')->name('withdrawal.withdrawal_update');
		
		// WithDraw Method
		Route::get('withdraw/method/list', 'WithdrawMethodController@index')->name('withdraw.method.list');
		Route::get('withdraw/add/method',   'WithdrawMethodController@create')->name('withdraw.method.create');
		Route::post('withdraw/save/method', 'WithdrawMethodController@store')->name('withdraw.method.store');
		Route::get('withdraw/edit/{id}',     'WithdrawMethodController@edit')->name('withdraw.method.edit');
		Route::post('withdraw/update/{id}',  'WithdrawMethodController@update')->name('withdraw.method.update');
		Route::get('withdraw/delete/{id?}',  'WithdrawMethodController@delete')->name('withdraw.method.delete');
		Route::post('withdrawal/save-terms', 'WithdrawMethodController@storeTerms')->name('withdraw.method.store.terms');

		//Payout
		 Route::get('payoutList', 'AdminPayoutController@index')->name('payoutList');
		 Route::get('getpayoutlist', 'AdminPayoutController@getPayoutList')->name('getpayoutlist');
		 Route::get('getlistbypayoutgroup', 'AdminPayoutController@getlistbypayoutgroup')->name('getlistbypayoutgroup');


		 // Support Tickets
		 Route::get('ticketlist', 'SupportTicketController@index')->name('ticketlist');
		 Route::post('store/comment', 'SupportTicketController@storeComment')->name('store.comments');
		 Route::get('fetch/comments', 'SupportTicketController@fetchComments')->name('fetch.comments');
		 Route::post('ticketlist/data', 'SupportTicketController@getdata')->name('ticketlist.data');
		 Route::get('getticketlist', 'SupportTicketController@getticketlist')->name('getticketlist');
		 Route::post('ticketComment', 'SupportTicketController@postComment')->name('ticketComment');
		 Route::post('close_ticket', 'SupportTicketController@closeTicket')->name('close_ticket');
		 Route::post('open_ticket', 'SupportTicketController@open_ticket')->name('open_ticket');
		 Route::get('show_comment', 'SupportTicketController@showComment')->name('show_comment');


		 //Developer Tools 
		 Route::get('developertoollist', 'DeveloperToolsController@developertoollist')->name('developertoollist');
		 Route::post('developertoolstore', 'DeveloperToolsController@developertoolstore')->name('developertoolstore');
         Route::post('deleteItem', 'DeveloperToolsController@deleteItem')->name('deleteItem');
		 Route::post('editItem', 'DeveloperToolsController@editItem')->name('editItem');
		 Route::post('updateItem', 'DeveloperToolsController@updateItem')->name('updateItem');

		Route::get('/check-main-balance','MainBalanceController@index')->name('check-main-balance');
		Route::get('/check-hold-balance','MainBalanceController@hold_balance_index')->name('check-hold-balance');
		Route::get('/check-payouthold-balance','MainBalanceController@payouthold_balance_index')->name('check-payouthold-balance');
		Route::get('/check-holdpayout-balance','MainBalanceController@holdpayout_balance_index')->name('check-holdpayout-balance');

		//bulk payout
		Route::get('bulk_payout', 'BulkPayoutsController@index')->name('bulk_payout.index');
		Route::post('/getdata','BulkPayoutsController@getdata')->name('getdata');

		Route::get('whitelist_account', 'WhiteListAccountController@index')->name('whitelistaccount.index');
		Route::get('whitelist_account_list', 'WhiteListAccountController@list')->name('whitelistaccount.list');
		Route::post('updatestatus', 'WhiteListAccountController@updatestatus')->name('updatestatus');

		Route::get('our_bank_account', 'BankAccountController@index')->name('ourbankaccount.index');
		Route::get('bank_account_list', 'BankAccountController@list')->name('ourbankaccount.list');
		Route::post('updatebankstatus', 'BankAccountController@updatestatus')->name('updatebankstatus');
		Route::post('bankaccountstore', 'BankAccountController@store')->name('bankaccountstore');

    });
});


// Route::group(['prefix'=>'administrator'],function () 
// {
// 	Route::get('login', 'Admin\Auth\LoginController@showLoginForm')->name('login');
// 	Route::group(['middleware'=>'auth'],function () {
// 		Route::post('admin/logout', 'Admin\Auth\LoginController@logout')->name('admin.logout');
// 	    Route::get('new_dashboard', 'Admin\DashboardController@new_dashboard')->name('new_dashboard');
// 	    Route::get('user/list', 'Admin\UserController@user_list')->name('administrator.user.list');
// 	    Route::get('transaction/list', 'Admin\TransactionController@index')->name('administrator.transaction.list');
// 	});
// });


Route::group(['prefix' => '{language}', 'middleware' => ['setLanguage','blockUser']],function(){

		// Route::group(['prefix' => 'ticketadmin', 'middleware' => 'ticketadmin'], function() {
		Route::group(['prefix' => 'ticketadmin'], function() {
		    Route::get('tickets', 'TicketsController@index')->name('support');
		    Route::post('close/{ticket_id}', 'TicketsController@close')->name('close');
		    
		    Route::get('kyc','KYCController@index')->name('admin.kyc.index')->middleware('admin.user');
		    Route::get('kyc/view/{id}','KYCController@viewProfile')->name('admin.kyc.view')->middleware('admin.user');
		    Route::post('kyc/view/{id}','KYCController@validateProfile')->name('admin.kyc.validate')->middleware('admin.user');
		    Route::get('/generalSetting','GeneralSettingController@generalSetting')->name('admin.generalSetting')->middleware('admin.user');
		    Route::post('/saveGiftCardFee','GeneralSettingController@saveGiftCardFee')->name('admin.saveGiftCardFee')->middleware('admin.user');
    
		});


		//Auth::routes();

		// Authentication Routes...
		Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
		Route::post('login', 'Auth\LoginController@login');
		Route::post('logout', 'Auth\LoginController@logout')->name('logout');

		// Registration Routes...
		Route::get('register', 'SignUpController@showRegistrationForm')->name('register');
		Route::post('register', 'SignUpController@register');

		// Reseller Routes..
		Route::get('reseller', 'Auth\ResellerController@showResellerForm')->name('reseller');
		Route::get('register/reseller', 'Auth\ResellerController@showResellerRegistrationForm');
		Route::post('reseller/register', 'Auth\ResellerController@register')->name('resellerFormRegister');

		// Password Reset Routes...
		Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
		// Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
		Route::get('password/reset/{token}/{email}', 'AccountsController@showResetForm')->name('password.reset');
		//Route::post('password/reset', 'Auth\ResetPasswordController@reset');


		Route::post('reset_password_without_token', 'AccountsController@validatePasswordRequest')->name('reset_password_without_token');
		Route::post('reset_password', 'AccountsController@resetPassword')->name('reset_password');


		//Account Activation Routes...
		Route::get('register/{email}/{token}', 'SignUpController@verifyEmail');
		Route::get('resend/activationlink', 'SignUpController@resendActivactionLink')->middleware('auth');
		Route::get('otp', 'SignUpController@OTP')->middleware('auth');
		Route::get('otp/resend', 'SignUpController@OTPresend')->middleware('auth')->name('resend_otp');
		Route::post('otp', 'SignUpController@postOtp')->middleware('auth');
		Route::get('otp/blade', 'SignUpController@TestMail');

		Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');

		// -------------------------------------------------------------------------------

	
		Route::get('/wallet/{id}','HomeController@wallet')->middleware('auth')->name('wallet.id');    
		 
		Route::get('/mail', 'SignUpController@TestMail');
		Route::get('/paysi', 'SignUpController@paysy');

		Route::get('/account_status/{User}', 'HomeController@accountStatus')->middleware('auth');



		//Impersonation routes
		Route::get('impersonate/user/{user_id}', 'ProfileController@impersonateUser')->middleware('auth')->name('impersonateUser');
		Route::impersonate();
	


		// SEND MONEY ROUTES
		Route::get('/sendmoney', 'MoneyTransferController@sendMoneyForm')->name('sendMoneyForm')->middleware('auth');
		Route::post('/sendmoney', 'MoneyTransferController@sendMoney')->name('sendMoney')->middleware('auth');

		Route::post('/sendMoneyConfirm', 'MoneyTransferController@sendMoneyConfirm')->name('sendMoneyConfirm')->middleware('auth');
		Route::post('/sendMoneyDelete', 'MoneyTransferController@sendMoneyCancel')->name('sendMoneyDelete')->middleware('auth');
		
			// Exchange Currency
		Route::get('/exchange_currency', 'ExchangeCurrencyController@exchangeCurrencyForm')->name('exchangeCurrencyForm')->middleware('auth');
		Route::get('/currencyExchange', 'ExchangeCurrencyController@currencyExchange')->name('currencyExchange')->middleware('auth');
		Route::post('/save/currencyExchange', 'ExchangeCurrencyController@saveDetail')->name('save.currencyExchange')->middleware('auth');
		Route::get('/exchange_currency/list', 'ExchangeCurrencyController@exchange_currency_list')->name('exchange_currency.list')->middleware('auth');

         /* VIRTUAL CARDS ROUTES */
        Route::get('/vcard','VCardController@index')->name('vcard')->middleware('auth');
        Route::get('/vcard/create','VCardController@create')->name('vcard.create')->middleware('auth');
        Route::post('/vcard/store','VCardController@store')->name('vcard.store')->middleware('auth');
        Route::get('/vcard/details/{id}','VCardController@details')->name('vcard.details')->middleware('auth');
        Route::get('/vcard/fund/{id}','VCardController@fund')->name('vcard.fund')->middleware('auth');
        Route::post('/vcard/postFund/{id}','VCardController@postFund')->name('vcard.postFund')->middleware('auth');
        Route::get('/vcard/error','VCardController@error')->name('vcard.error')->middleware('auth');
        Route::get('/vcard/confirm_delete/{id}','VCardController@confirmDelete')->name('vcard.confirmDelete')->middleware('auth');
        Route::get('/vcard/delete/{id}','VCardController@delete')->name('vcard.delete')->middleware('auth');
        
         // epin
		Route::get('generatePDF/{id}', 'EpinController@generatePDF')->name('generatePDF')->middleware('auth');
		Route::get('buyEpin', 'EpinController@buyEpin')->name('buyEpin')->middleware('auth');
		// Route::post('/user/buyepin', 'EpinController@buyEpin')->name('buyepin')->middleware('auth');
		Route::post('buyEpinAction', 'EpinController@buyEpinAction')->name('buyEpinAction')->middleware('auth');

		//REQUEST MONEY ROUTES
		Route::get('/requestmoney', 'MoneyTransferController@requestMoneyForm')->name('requestMoneyForm')->middleware('auth');
		Route::post('/requestmoney', 'MoneyTransferController@requestMoney')->name('requestMoney')->middleware('auth');
		// Route::post('/requestMoneyConfirm', 'MoneyTransferController@requestMoneyConfirm')->name('requestMoneyConfirm')->middleware('auth');
		// Route::post('/requestMoneyDelete', 'MoneyTransferController@requestMoneyCancel')->name('requestMoneyDelete')->middleware('auth');


		//WALLET ROUTES
		Route::get('transfer/{currency_id}/methods', 'WalletController@showTransferMethods')->middleware('auth')->name('show.transfermethods');
		Route::get('currencies/methods', 'WalletController@showCurrencies')->middleware('auth')->name('show.currencies');
		Route::get('wallet/create/{currency_id}', 'WalletController@createWallet')->middleware('auth')->name('show.createwalletform');
		Route::get('method/create/{currency_id}', 'WalletController@showCreateMethodForm')->middleware('auth');
		Route::post('method/create', 'WalletController@createMethod')->middleware('auth')->name('add.method.wallet');

         //Gift Cards Route
        Route::get('/giftCards', 'GiftCardController@gift_card')->name('cards')->middleware('auth');
        Route::get('/getGiftCards/{code}/{country_id}', 'GiftCardController@getGiftCards')->name('getGiftCards')->middleware('auth');
        Route::get('/getGiftCards/{code}/{country_id}/{product_id}', 'GiftCardController@getGiftCards')->name('getGiftCards')->middleware('auth');
        Route::post('/order_gift_card', 'GiftCardController@order_gift_card')->name('order_gift_card')->middleware('auth');

		/*	MERCHANT ROUTES	*/

		Route::get('/merchant/merchantlinkGateWay/{ref}', 'MerchantPaymentGatePayController@merchantlinkGateWay')->name('merchantlinkGateWay');

		// deprecated temporarily by /merchant/payment-storefront/{ref}  
		Route::get('/merchant/storefront/{ref}', 'MerchantController@getStoreFront')->name('storefront');
		//

		Route::get('/merchant/payment-storefront/{ref}', 'MerchantController@getPaymentStorefront')->name('payment.storefront');
		Route::get('/merchant/{merchant}/docs', 'MerchantController@integration')->middleware('auth')->name('merchantIntegration');
		Route::get('/mymerchants', 'MerchantController@index')->name('mymerchants')->middleware('auth');

		Route::get('/merchant/new', 'MerchantController@new')->name('merchant.new')->middleware('auth');
		Route::post('/merchant/add','MerchantController@add')->name('merchant.add')->middleware('auth');


		/*	IPN ROUTES	*/
		Route::post('/purchase/link', 'RequestController@storeRequest')->name('purchase_link');
		Route::post('/purchase/create-transaction', 'RequestController@storeRequest')->name('create_transaction');
		Route::post('/request/status', 'RequestController@requestStatus')->name('purchase_status');
		Route::post('/purchase/confirm', 'IPNController@purchaseConfirmation')->name('purchaseConfirm')->middleware('auth');
		Route::post('/purchase/delete', 'IPNController@purchaseCancelation')->name('purchaseDelete')->middleware('auth');
		Route::post('/ipn/payment', 'IPNController@pay')->name('pay')->middleware('auth');
		Route::post('/ipn/payment/guest/{ref}', 'IPNController@logandpay')->name('logandpay');
		Route::post('/ipn/payment/login', 'IPNController@loginForPayment')->name('ipn.login');
		Route::post('/ipn/payment/logout', 'IPNController@logoutFromPayment')->name('ipn.logout');
		Route::get('/ipn/payment/refresh-balance/{ref}', 'IPNController@refreshBalance')->name('ipn.refresh');
		Route::post('/ipn/payment/deposit', 'IPNController@deposit')->name('ipn.deposit');
		
		Route::get('/ipn/payment/show-login/{ref}', 'IPNController@showLogin')->name('ipn.show_login');
		Route::get('/ipn/payment/show-express-login/{ref}', 'IPNController@showExpressLogin')->name('ipn.show_express_login');
		Route::post('/ipn/payment/express-login', 'IPNController@expressLogin')->name('ipn.express_login');
		Route::post('/ipn/payment/validate-express-login', 'IPNController@validateExpressLogin')->name('ipn.validate_express_login');

		/*	ADD CREDIT ROUTES	*/
		Route::get('/addcredit/{method_id?}', 'AddCreditController@addCreditForm')->name('add.credit')->middleware(['auth','activeUser']);
		Route::get('/deposit', 'AddCreditController@depositMethods')->name('deposit.credit')->middleware('auth');
		// Route::get('/deposit/{wallet_id}', 'AddCreditController@depositTransferMethods')->name('deposit.transfer.form')->middleware('auth');
		// Route::get('/deposit/{wallet_id}', 'AddCreditController@depositByWallet')->name('deposit.transfer.form')->middleware('auth');

		Route::get('/deposit/m/{method_id}', 'AddCreditController@depositByTransferMethod')->name('deposit.transfer.form')->middleware('auth');

		Route::post('/addcredit', 'AddCreditController@depositRequest')->name('post.credit')->middleware('auth');
		
		Route::post('/flutteraddcredit', 'AddCreditController@flutteraddcredit')->name('post.flutteraddcredit')->middleware('auth');

		Route::get('/flutteraddredirect', 'AddCreditController@handleFlutterWavePayment')->name('get.flutteraddredirect')->middleware('auth');
 
		/*	DEPOSITS ROUTES	*/
		Route::get('/mydeposits','DepositController@myDeposits')->name('mydeposits')->middleware('auth');
		Route::get('/mypayouts','DepositController@myPayouts')->name('mypayouts')->middleware('auth');
		Route::get('add/mydeposits','DepositController@add')->name('add.mydeposits')->middleware('auth');
		Route::post('save/mydeposits','DepositController@save')->name('save.mydeposits')->middleware('auth');
		Route::put('/confirm/deposit','DepositController@confirmDeposit')->name('confirm.deposit')->middleware('auth');

		/* WITHDRAWAL ROUTES */

		// route::get('/withdrawal/request/{method_id?}', 'WithdrawalController@getWithdrawalRequestForm')->name('withdrawal.form')->middleware(['auth','activeUser']);
		route::get('/payout/{wallet_id}', 'WithdrawalController@payoutMethod')->name('payout.methods')->middleware(['auth','activeUser']);
		route::get('/payout/request/{pivot_id}', 'WithdrawalController@payoutForm')->name('payout.form')->middleware(['auth','activeUser']);
		route::post('/withdrawal/request', 'WithdrawalController@makeRequest')->name('post.withdrawal')->middleware('auth');
		route::get('/withdrawals', 'WithdrawalController@index')->name('withdrawal.index')->middleware('auth');

		Route::put('/confirm/withdrawal','WithdrawalController@confirmWithdrawal')->name('confirm.withdrawal')->middleware('auth');
		route::get('add/withdrawal/method', 'WithdrawalController@add')->name('add.withdrawal.method')->middleware('auth');
		route::post('create/withdrawal/method', 'WithdrawalController@create')->name('create.withdrawal.method')->middleware('auth');

		/* EXCHANGE ROUTES */
		route::get('/exchange/first/{first_id?}/second/{second_id?}', 'ExchangeController@getExchangeRequestForm')->name('exchange.form')->middleware('auth');
		route::post('/exchange/', 'ExchangeController@exchange')->name('post.exchange')->middleware('auth');

		route::post('/update_rates','ExchangeController@updateRate')->middleware('auth');
		route::get('/update_rates','ExchangeController@updateRateForm')->middleware('auth');

		route::get('new_ticket', 'TicketsController@create')->name('support');
		route::post('post_new_ticket', 'TicketsController@store')->name('post_new_ticket');
		route::get('my_tickets', 'TicketsController@userTickets')->name('my_tickets');
		Route::get('ticket_detail/{ticket_id}', 'TicketsController@show')->name('ticket_detail');
		Route::post('comment', 'TicketCommentsController@postTicketComment')->name('comment');


		route::get('profile/info', 'ProfileController@personalInfo')->name('profile.info')->middleware('auth');
		route::post('profile/info', 'ProfileController@storePersonalInfo')->name('profile.info.store')->middleware('auth');
		route::get('profile/identity', 'ProfileController@profileIdentity')->name('profile.identity')->middleware('auth');
		route::post('profile/identity', 'ProfileController@storeProfileIdentity')->name('profile.identity.store')->middleware('auth');
		route::get('profile/newpassword', 'ProfileController@newpasswordInfo')->name('profile.newpassword')->middleware('auth');
		route::post('profile/newpassword', 'ProfileController@storeNewpasswordInfo')->name('profile.newpassword.store')->middleware('auth');


		//VOUCHERS ROUTES
		route::get('my_vouchers', 'VoucherController@getVouchers')->name('my_vouchers')->middleware('auth');
		route::post('my_vouchers', 'VoucherController@createVoucher')->name('create_my_voucher')->middleware('auth');
		route::post('load_my_voucher', 'VoucherController@loadVoucher')->name('load_my_voucher')->middleware('auth');
		route::post('load_voucher_to_user', 'VoucherController@loadVoucherToUser')->name('load_voucher_to_user')->middleware('auth');
		route::get('makevouchers', 'VoucherController@generateVoucher')->name('makeVouchers')->middleware('auth');
		route::post('generateVoucher', 'VoucherController@postGenerateVoucher')->name('generateVoucher')->middleware('auth');
		route::get('buyvoucher', 'VoucherController@buyvouchermethod')->middleware('auth');

		//PAYPAL VOUCHER ROUTES
		route::get('buyvoucher/paypal', 'PayPalController@buyvoucher')->middleware('auth');
		route::post('buyvoucher/paypal', 'PayPalController@sendRequestToPaypal')->middleware('auth');
		route::get('pay/voucher/paypal/success', 'PayPalController@paySuccess')->middleware('auth');
		Route::post('/merchant/storefront/paypal/{ref}', 'PayPalController@postStoreFront')->name('paypalstorefront');
		Route::get('/merchant/storefront/paypal/success', 'PayPalController@postStoreFrontSuccess');
		Route::get('/merchant/storefront/paypal/cancel', 'PayPalController@postStoreFrontCancel');

		//PAYSTACK VOUCHER ROUTES
		route::get('buyvoucher/paystack', 'PaystackController@buyvoucher')->middleware('auth');
		route::post('buyvoucher/paystack', 'PaystackController@sendRequestToPayStack')->middleware('auth');
		route::get('pay/voucher/paystack/success', 'PaystackController@payVoucherPayStackSuccess')->middleware('auth');
		Route::post('/merchant/storefront/paystack/{ref}', 'PaystackController@postStoreFront')->name('paystackstorefront');
		Route::get('/merchant/storefront/paystack/success', 'PaystackController@postStoreFrontSuccess');

		//STRIPE VOUCHER ROUTES
		route::get('buyvoucher/stripe', 'StripeController@buyvoucher')->middleware('auth');
		route::post('buyvoucher/stripe', 'StripeController@sendRequestToStripe')->middleware('auth');
		//route::get('pay/voucher/paystack/success', 'PaystackController@payVoucherPayStackSuccess')->middleware('auth');

		//2CHECKOUT VOUCHER ROUTES
		route::get('buyvoucher/2checkout', 'TwoCheckoutController@buyvoucher')->middleware('auth');
		route::post('buyvoucher/2checkout', 'TwoCheckoutController@sendRequestToStripe')->middleware('auth');
		//route::get('pay/voucher/paystack/success', 'PaystackController@payVoucherPayStackSuccess')->middleware('auth');

		//TUTORIAL ROUTES


		// route::get('blog', 'BlogController@index' )->name('blog');
		// route::get('blog/{post_excerpt}/{post_id}', 'BlogController@singlePost' )->name('post');

		//TRANSACTIOINS ROUTES
		route::post('transaction/remove', 'TransactionController@deleteMapper')->middleware('auth');

		//ESCROW ROUTES

		route::get('escrow', 'EscrowController@sendForm')->name('escrow')->middleware('auth');
		route::post('escrow', 'EscrowController@store')->middleware('auth');
		route::post('/escrow/refund','EscrowController@refund')->middleware('auth');
		route::post('/escrow/release','EscrowController@release')->middleware('auth');
		route::get('/escrow/{eid}', 'EscrowController@agreement')->middleware('auth');

		//INVESTMENT

		route::get('investment/plans', 'InvestmentController@plans')->name('investmentplans');
		route::get('investment/plan/{plan_id}', 'InvestmentController@investForm')->name('investmentform')->middleware('auth');
		route::post('investment/store', 'InvestmentController@store')->middleware('auth');
		route::get('myinvestments', 'InvestmentController@myInvestments')->name('myinvestments')->middleware('auth');
		route::post('investment/take_profit', 'InvestmentController@takeProfit')->name('takeProfit')->middleware('auth');

		//VIRTUAL CARDS ROUTES
		route::get('cards/all', 'VirtualCardController@list')->name('cards')->middleware('auth');
		route::post('virtualcard', 'VirtualCardController@requestVc')->name('requestVirtualCard')->middleware('auth');

		//Sto Virtual Account
		Route::any('/stro_webhook', 'StrovirtualaccountController@stro_webhook')->name('stro_webhook');
        route::get('/strovirtual_account', 'StrovirtualaccountController@index')->name('strovirtual_account')->middleware('auth');
        route::get('/stro_account', 'StrovirtualaccountController@stro_account')->name('stro_account')->middleware('auth');

        //Stro Bank Transfer
        Route::get('/stro_bank_transfer', 'StroBankTransferController@index')->name('stro_bank_transfer');
        Route::post('/stroBankPostRequest', 'StroBankTransferController@stroBankPostRequest')->name('stroBankPostRequest');
        Route::get('/stroPreview', 'StroBankTransferController@stroPreview')->name('stroPreview');
        Route::get('/stroBankTransfer', 'StroBankTransferController@stroBankTransfer')->name('stroBankTransfer');

        //StroAirtime
        Route::get('/stroAirtime', 'StroAirtimeController@index')->name('stroAirtime');
        Route::post('/stroairtimeRequest', 'StroAirtimeController@stroairtimeRequest')->name('stroairtimeRequest');
        
         //Gatepay
        Route::get('/gatepay', 'GatepayController@index')->name('gatepay');
        Route::get('/auto_deposit', 'GatepayController@auto_deposit')->name('auto_deposit');
        Route::post('/auto_deposit_post', 'GatepayController@auto_deposit_post')->name('auto_deposit_post');
        Route::get('/auto_deposit_success', 'GatepayController@auto_deposit_success')->name('auto_deposit_success');
        Route::get('/auto_deposit_failed', 'GatepayController@auto_deposit_failed')->name('auto_deposit_failed');
        Route::get('/auto_deposit_failed', 'GatepayController@auto_deposit_failed')->name('auto_deposit_failed');
        
        //StroData
        Route::get('/stroData', 'StroDataController@index')->name('stroData');
        Route::post('/buyStroDataRequest', 'StroDataController@buyStroDataRequest')->name('buyStroDataRequest');
        Route::get('/stro_get_data_bundles/{service_name}', 'StroDataController@stro_get_data_bundles')->name('stro_get_data_bundles');

        //StroCable
        Route::get('/strotvSubscription', 'StroCableController@index')->name('strotvSubscription');
        Route::get('/strogetCablePlan/{service_id}', 'StroCableController@strogetCablePlan')->name('strogetCablePlan');
        Route::post('/cable_merchant_verify', 'StroCableController@cable_merchant_verify')->name('cable_merchant_verify');
        Route::get('/cablePreview', 'StroCableController@cablePreview')->name('cablePreview');
        Route::get('/postCableRequest', 'StroCableController@postCableRequest')->name('postCableRequest');

        //StroElectricity
        Route::get('/stroElectricity', 'StroElectricityController@index')->name('stroElectricity');
        Route::get('/stroElectricityPreview', 'StroElectricityController@stroElectricityPreview')->name('stroElectricityPreview');
        Route::get('/stroPostElectricity', 'StroElectricityController@stroPostElectricity')->name('stroPostElectricity');
        Route::post('/stro_electricity_merchant_verify', 'StroElectricityController@stro_electricity_merchant_verify')->name('stro_electricity_merchant_verify');

        //StroFundTransfer
        Route::get('/strofundTransfer', 'StroFundTransferController@index')->name('strofundTransfer');

        //StroEducationController
        Route::get('/stroEducation', 'StroEducationController@index')->name('stroEducation');
        Route::post('/stroPostEducational', 'StroEducationController@stroPostEducational')->name('stroPostEducational');

        //Loan Routes
        Route::get('/loan', 'LoanController@index')->name('loan');
        Route::post('/submitLoanRequest', 'LoanController@submitLoanRequest')->name('submitLoanRequest');
        Route::get('/loan/edit_detail', 'LoanController@edit_detail')->name('loan.edit_detail');
        Route::get('/loan/detail/{id}', 'LoanController@detail')->name('loan.detail');
        Route::get('/loan/user_loan_preview/{id}', 'LoanController@user_loan_preview')->name('loan.user_loan_preview');
        Route::get('/loan/confirm/{id}', 'LoanController@confirm')->name('loan.confirm');
        Route::get('/loan/user_payment_detail/{id}', 'LoanController@user_payment_detail')->name('loan.user_payment_detail');
        Route::get('/loan/list', 'LoanController@list')->name('loan.list');
        Route::get('/loan/pending', 'LoanController@pending')->name('loan.pending');
        Route::get('/loan/approved', 'LoanController@approved')->name('loan.approved');
        Route::get('/loan/rejected', 'LoanController@rejected')->name('loan.rejected');
        Route::get('/loan/accepted', 'LoanController@accepted')->name('loan.accepted');
        Route::get('/loan/declined', 'LoanController@declined')->name('loan.declined');
        Route::post('/loan/accept_loan', 'LoanController@accept_loan')->name('loan.accept_loan');
        Route::post('/loan/decline_loan', 'LoanController@decline_loan')->name('loan.decline_loan');
        Route::post('/loan/repay_now', 'LoanController@repay_now')->name('loan.repay_now');
        
        //Kyc Setting
        Route::get('kyc_setting', 'KycController@kyc_setting')->name('kyc_setting');
        Route::post('submitKyc', 'KycController@submitKyc')->name('submitKyc');

		//PAYMENTLINKS ROUTES
		route::get('paymentlinks/all', 'PaymentLinkController@list')->name('paymentlinks')->middleware('auth');
		route::post('paymentlink', 'PaymentLinkController@createPaymentLink')->name('createPaymentLink')->middleware('auth');
		route::get('web/payment/{payment_id}', 'PaymentLinkController@paymentStoreFront')->name('paymentLinkStoreFront');
		route::post('web/payment/link/process',  'PaymentLinkController@payWithWalletBalance');
		route::post('web/payment/link/process/card',  'PaymentLinkController@payWithCard');
		route::get('web/paymentlinkGateWay/{payment_id}', 'PaymentlLinkGatePayController@paymentlinkGateWay')->name('paymentlinkGateWay');

		//PAYPAGE ROUTES
		route::get('paypage', 'PayPageController@index')->name('paypage')->middleware('auth');
		
		route::get('/service', 'BillpageController@service')->name('service');

		
		//TRADES
		route::get('trades/mybook', 'TradeController@myBook')->name('mybook')->middleware('auth');
		route::get('trades/myclosed', 'TradeController@myClosed')->name('myclosed')->middleware('auth');
		route::get('trades/book', 'TradeController@offerbook')->name('offerbook')->middleware('auth');
		route::get('trades/liquid/{trade_id}', 'TradeController@liquidateForm')->name('liquidatef')->middleware('auth');
		route::post('trades/open', 'TradeController@openPosition')->name('openposition')->middleware('auth');
		route::post('/trade/liquid', 'TradeController@liquidate')->name('liquid')->middleware('auth');
		// route::get('investment/plan/{plan_id}', 'InvestmentController@investForm')->name('investmentform')->middleware('auth');
		// route::post('investment/store', 'InvestmentController@store')->middleware('auth');
		// route::get('myinvestments', 'InvestmentController@myInvestments')->name('myinvestments')->middleware('auth');
		// route::post('investment/take_profit', 'InvestmentController@takeProfit')->name('takeProfit')->middleware('auth');


		//ADMINISTRATION ROUTES
		Route::get('loan/approved_loan','Admin\AdminLoansController@approved_loan')->name('loan.approved_loan');
        Route::get('loan/pending_loan','Admin\AdminLoansController@pending_loan')->name('loan.pending_loan');
        Route::get('loan/rejected_loan','Admin\AdminLoansController@rejected_loan')->name('loan.rejected_loan');
        Route::get('loan/accepted_loan','Admin\AdminLoansController@accepted_loan')->name('loan.accepted_loan');
        Route::get('loan/declined_loan','Admin\AdminLoansController@declined_loan')->name('loan.declined_loan');

        Route::get('loan/preview/{id}','Admin\AdminLoansController@details')->name('loan.preview');
        Route::get('loan/payment_detail/{id}','Admin\AdminLoansController@payment_detail')->name('loan.payment_detail');
        Route::post('loan/approve','Admin\AdminLoansController@approve')->name('loan.approve');
        Route::post('loan/reject','Admin\AdminLoansController@reject')->name('loan.reject');

		route::get('users/all', 'ProfileController@getUsers')->middleware('auth');
		route::get('users/whatsapp', 'ProfileController@getUsersWhatsApps')->middleware('auth');

		//BuyVouchersRoutes
		route::get('order/vouchers', 'VoucherController@orderForm')->name('show.ordervouchers')->middleware('auth');
		route::post('order/vouchers/check', 'VoucherController@checkOrder')->name('check.ordervouchers')->middleware('auth');
		route::post('order/vouchers/process', 'VoucherController@processOrder')->name('processVoucherOrder')->middleware('auth');

		//DEMO ROUTES

		route::get('demo/index', 'DemoController@index');
		route::get('demo/user', 'DemoController@user')->name('demouser');
		route::get('demo/admin', 'DemoController@admin')->name('demoadmin');

		route::get('/me/{user_name}', 'ProfileController@me');


		// static pages route
		Route::get('/', 'PagesController@welcome')->middleware('check.website');
		Route::get('about-us', 'PagesController@aboutUs')->name('pages.about_us');
		Route::get('service-of-us', 'PagesController@service')->name('pages.service');
		Route::get('contact-us', 'PagesController@contactUs')->name('pages.contact_us');

		route::get('faq', "PagesController@faq")->name('pages.faq');
		route::get('terms-of-use', "PagesController@termsOfUse")->name('pages.terms_of_use');
		// route::get('tutorials', "PagesController@tutorials");
		route::get('privacy-policy', "PagesController@privacyPolicy")->name('pages.privacy_policy');
		
		
		
		// Airitime
		route::get('/airtime', 'AirtimeController@airtime')->name('airtime')->middleware('auth');
		route::post('/buyAirtimeRequest', 'AirtimeController@buyAirtimeRequest')->name('buyAirtimeRequest');
		route::get('/getOperatores/{country_code}/{currency_code}', 'AirtimeController@getOperatores')->name('getOperatores');

		// Ledger
		Route::get('/ledger','LedgerController@index')->name('ledger');
		Route::get('ledger/list', 'LedgerController@transaction_list')->name('ledger.list');

		//Logs
		Route::get('/logs','TransactionLogsController@index')->name('logs');
		Route::get('transaction/logs', 'TransactionLogsController@transaction_logs')->name('transaction.logs');
		Route::get('show/logs', 'TransactionLogsController@show_logs')->name('show.logs');

		// Payout
		Route::get('/payout','PayoutController@getPayoutData')->name('payout');

		Route::post('/bulkuploadpayout','PayoutController@bulkuploadpayout')->name('bulkuploadpayout');
		
		Route::get('getpayout', 'PayoutController@payout_list')->name('getpayout');
		Route::get('getpayoutgroupwise', 'PayoutController@getpayoutgroupwise')->name('getpayoutgroupwise');

		//developer tools 
		Route::get('/developer_tools','DeveloperController@index')->name('developer_tools.index');
        Route::get('/developer_tools/data', 'DeveloperController@getDeveloperTools')->name('developer_tools.data');

		//bulk payout
		Route::get('/bulk_payout','BulkPayoutController@index')->name('bulk_payout.index');
		Route::get('/bulk_payout/data','BulkPayoutController@bulk_payout')->name('bulk_payout.data');

		//pie chart
		Route::get('pie_stats','HomeController@getChartData')->name('pie_stats');

		// white label
		Route::post('/purchase/get-deposit-details','WhitelabelController@getDepositDetails');

		//whilte label accounts
		Route::get('/whitelistaccount','WhiteListAccountController@index')->name('whitelistaccount.index');
		 Route::post('whitelistaccountstore', 'WhiteListAccountController@store')->name('whitelistaccountstore');

		


});

require __DIR__.'/notus.php';