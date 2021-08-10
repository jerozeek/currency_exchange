<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('App\Controllers\Admin\Auth\LoginController');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

$routes->get('/','Admin/Auth/LoginController::index');

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->group('api/auth',['namespace'  => 'App\Controllers\Auth'], function ($routes){
    $routes->post('login','LoginController::doLogin');
    $routes->post('signup','RegisterController::create');
    $routes->get('firewall','FirewallController::runValidations');
    $routes->get('fpassword','ForgetPasswordController::doSearch');
    $routes->post('verify','OtpController::verifyOtp');
    $routes->post('password_reset','OtpController::doReset');
    $routes->get('resend_otp','OtpController::doResend');
    $routes->post('update_password','OtpController::updatePassword');
    $routes->get('logout','JwtToken::blacklist');
});

$routes->group('api/deposit',['namespace' => 'App\Controllers\Deposit'], function ($routes){
    $routes->get('supported_currency','StripeController::supportedCurrency');
    $routes->post('create_intent','StripeController::createPaymentIntent');
    $routes->get('complete_deposit','StripeController::stripePay');
});

$routes->group('api/transfer',['namespace' => 'App\Controllers\Withdraw'], function ($routes){
    //Bank transfer
    $routes->get('supported_banks','PaystackController::bankList');
    $routes->get('verify_account','PaystackController::validateAccount');
    $routes->post('invoke','PaystackController::makeTransfer');

    //$routes->get('push','TransferController::testPushNotification');

    //in app transfer
    $routes->get('users','TransferController::getAllUsers');
    $routes->post('send','TransferController::moveFunds');
});
$routes->group('api/exchange', ['namespace' => 'App\Controllers\Exchange'], function ($routes){
    $routes->post('convertCurrency','FixerController::currencyExchange');
    $routes->get('getExchange','FixerController::getExchange');
});

$routes->group('api/transactions', ['namespace' => 'App\Controllers\Users'], function ($routes){
    $routes->get('exchange','TransactionController::exchanges');
    $routes->get('transfer','TransactionController::transactions');
});

$routes->group('api/users', ['namespace' => 'App\Controllers\Users'], function ($routes){
    $routes->post('update_password','UsersController::updatePassword');
    $routes->post('upload_image','UsersController::uploadProfileImage');
    $routes->post('upload_kyc','UsersController::uploadKYC');
    $routes->get('get_kyc','UsersController::getKYC');
});

$routes->group('api/notification', ['namespace' => 'App\Controllers\Notification'], function ($routes){
    $routes->get('message','NotificationController::getMessages');
    $routes->get('get_player_id','NotificationController::setPlayerId');
});

$routes->group('api/p2p', ['namespace' => 'App\Controllers\P2p'], function ($routes){

    $routes->post('seller/create/sales','P2pController::saleRequest');
    $routes->get('seller/listing/sales','P2pController::saleList');

    $routes->get('sales/pending/listing','P2pController::salesListing');
});


//Admin grouping
$routes->group('admin/auth', ['namespace' => 'App\Controllers\Admin\Auth'],function ($routes){
    $routes->get('login','LoginController::index');
    $routes->get('logout','LoginController::logout');
    $routes->post('login','LoginController::adminLogin');
});

//Admin grouping
$routes->group('admin/account', ['namespace' => 'App\Controllers\Admin\Account'],function ($routes){
    $routes->get('dashboard','AdminController::dashboard',['as' => 'dashboard']);
});

$routes->group('admin/kyc', ['namespace' => 'App\Controllers\Admin\Account'],function ($routes){
    $routes->get('kyc','KYController::index',['as' => 'kyc']);
    $routes->get('view/(:segment)','KYController::details/$1');
    $routes->get('action','KYController::handleRequest');
});

$routes->group('admin/users', ['namespace' => 'App\Controllers\Admin\Account'],function ($routes){
    $routes->get('manage','UserController::index');
    $routes->get('details/(:segment)','UserController::userDetails/$1');
    $routes->get('suspend','UserController::manageStatus');
});

$routes->group('admin/transactions', ['namespace' => 'App\Controllers\Admin\Account'],function ($routes){
    $routes->get('transactions','TransactionsController::transactions');
    $routes->get('details/(:segment)','TransactionsController::transactionInvoice/$1');
});


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
