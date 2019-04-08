<?php

use Illuminate\Support\Facades\Route;

use App\Models\User;
use App\Models\Shipment;
use App\Models\Quotation;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;

Route::any('test/{user}',
  'RegistrationController@otpCall');
Route::any('test-mail',function(){
  $data['userObj'] = User::find(2);
  $data['mail'] = "Testing mail content";
  $data['subject'] = "Test Mail";
  dd(Mail::to('dev2@cloudsasystems.com')->send(new SendMail($data)));

});
Route::get('test',function(){
  return generateInvoice(31);
});
Route::get('/', 'HomeController@index')->name('home');
Route::get('pricing', 'HomeController@pricing')->name('home.pricing');

Route::get('/thank-you', function () {
    return view('thank-you');
});

Route::get('/parcel-forwarding-service-europe', function () {
    return view('service-europe');
});

Route::get('/package-forwarding-service', function () {
    return view('package-forwarding');
});

Route::get('/parcel-forwarding-service-to-australia', function () {
    return view('forwarding-australia');
});

Route::get('/parcel-forwarding-service-us', function () {
    return view('parcelforwardingus');
});

Route::get('/parcel-delivery-service-uk', function () {
    return view('parcel-delivery-service');
});

Route::get('/parcel-forwarding-service', function () {
    return view('parcel-forwarding-service');
});

Route::get('404',['as'=>'404','uses'=>'ErrorHandlerController@errorCode404']);
Route::get('405',['as'=>'405','uses'=>'ErrorHandlerController@errorCode405']);
Route::any('xml/{user}', 'XmlController@index')->name('xml.index');
Route::post('read-notifications', 'Admin\UserController@readNotification');
Route::get('registration/plan', 'RegistrationController@plan')->name('registration.plan');
Route::get('services', 'HomeController@services');
Route::get('contact-us', 'ContactUsController@index');
Route::post('contact-us', 'ContactUsController@store')->name('contact-us.store');
//Route::get('blog', 'HomeController@blog');
//Route::get('blog-detail', 'HomeController@blogDetail');
Route::get('faqs', 'HomeController@faqs');
Route::get('privacy-policy', 'HomeController@privacy');
Route::get('terms-trade', 'HomeController@termsTrade');
Route::get('return-policy', 'HomeController@returnPolicy');
Route::get('legal-terms', 'HomeController@legalTerms');
Route::get('sitemap', 'HomeController@sitemap');
Route::get('shipment/{id}', 'HomeController@getPdf');
Route::get('personal-shopper-pdf/{id}', 'HomeController@getPersonalShopperPdf');
Route::get('membership-pdf/{id}', 'HomeController@membershipPdf');

Route::match(['get', 'post'], 'users/{user}/payment', 'RegistrationController@payment')->name('users.payment');

Route::post('payment-gateway', 'PaymentController@index')->name('payment-gateway.index');
Route::post('payment-gateway/pay', 'PaymentController@store')->name('payment-gateway.store');

Route::post('shipping-options', 'ShipmentController@shippingOptions');

Route::post('paypal/express-checkout', 'PaypalController@expressCheckout')->name('paypal.checkout');
Route::get('paypal/express-checkout-success', 'PaypalController@expressCheckoutSuccess');

Route::get('users/verification/{token}', 'RegistrationController@verification')->name('users.verification');
Route::get('users/{user}/verification', 'RegistrationController@verificationStatus')->name('users.verification.status');
Route::get('users/{user}/otp-verification', 'RegistrationController@otpVerification')->name('users.verification.mobile');
Route::get('users/{user}/otp', 'RegistrationController@otpVerificationPage')->name('users.verification.otp');
Route::post('users/{user}/otp-verification', 'RegistrationController@otpVerificationCheck')->name('users.verification.mobile.check');
Route::get('users/resend/verification/{user}', 'RegistrationController@resendVerification')->name('users.resend.verification');
Route::group(['prefix' => 'ajax'], function () {
    Route::post('users/{user}/otp-call', 'RegistrationController@otpCall')->name('users.verification.call');
});
Route::group(['middleware' => 'guest'], function () {
    Route::get('registration/membership', 'RegistrationController@membership')->name('registration.membership');
    Route::post('registration', 'RegistrationController@store')->name('registration.store');

    Route::get('signin', 'LoginController@index')->name('login.index');

    Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');

    Route::get('validate-otp/{token}', 'ResetPasswordController@showValidateOtpForm')->name('password.otp-validation');
    Route::post('validate-otp', 'ResetPasswordController@validateOtp');
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'ResetPasswordController@reset')->name('password.reset.store');

    Route::group(['prefix' => 'ajax'], function () {
        Route::post('login', 'LoginController@store')->name('login.store');
        Route::post('google-authentication', 'LoginController@googleAuthenticationStore')->name('login.authy.store');
        Route::post('registration/unique', 'RegistrationController@unique');

        Route::post('subscribe','HomeController@subscribe');
    });
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('logout', 'LoginController@logout')->name('logout');


    Route::get('dashboard/', 'UserController@profile')->name('users.profile.index');
    Route::post('profile', 'UserController@profileUpdate')->name('users.profile.update');

    Route::get('security', 'UserController@security')->name('users.security.index');
    Route::post('security', 'UserController@securityUpdate')->name('users.security.update');
    //
    // Route::get('twofactor/enable', 'UserController@enableTwoFactor')->name('users.twofactor.enable');
    // Route::get('twofactor/disable', 'UserController@disableTwoFactor')->name('users.twofactor.disable');

    Route::get('personal-shopper', 'PersonalShopperController@index')->name('users.shopper.index');
    Route::post('personal-shopper', 'PersonalShopperController@store')->name('users.shopper.store');

    Route::post('shipments/{shipment}/shipping-option', 'UserController@shippingOption')->name('users.shipment.shipping-option');
    Route::post('shipments/{shipment}/warehouse-option', 'UserController@warehouseOption')->name('users.shipment.warehouse-option');
    Route::get('users/subscription', 'UserController@subscription')->name('users.subscription');

    Route::post('users/downgrade', 'UserController@downGradeAccount')->name('users.downgrade');

    Route::get('shipments/{shipment}/payment', 'ShipmentPaymentController@index')->name('shipments.payment');
    Route::get('personal-shopper/{quotation}/payment', 'PersonalShopperController@payment')->name('shopper.payment');

    Route::get('dashboard-action', 'HomeController@dashboardAction');
    Route::get('dashboard-history', 'UserController@dashboardHistory');
    Route::get('dashboard-shipments', 'UserController@dashboardShipments');
    Route::get('dashboard-invoice', 'UserController@dashboardInvoice');
    Route::get('dashboard-shipping', 'UserController@dashboardShipping')->name('users.shipments.ready-for-shipment');
    Route::get('dashboard-warehouse', 'HomeController@dashboardWarehouse');
    Route::get('dashboard-action-box', 'UserController@actionbox')->name('users.action_box');
    Route::get('dashboard-warehouse', 'UserController@warehouse')->name('users.warehouse');

    Route::get('get-service-charge', 'HomeController@getServiceCharge');


    Route::group(['prefix' => 'ajax'], function () {
        Route::post('users/shipments/{shipment}/shipment-items/{shipment_item}/request-photo', 'UserController@requestItemPhoto');
        Route::post('users/shipments/{shipment}/shipment-items/{shipment_item}/photos', 'UserController@itemPhotos');

        Route::post('check-mobile', 'UserController@checkMobile');
        Route::post('profile-verify-otp', 'UserController@otpVerificationCheck');
        Route::post('profile-send-otp', 'UserController@sendOtp');
		    Route::post('update-number', 'RegistrationController@updateNumber')->name('users.update.number');

    });
});

Route::group(['middleware' => 'authenticate','prefix' => 'ajax'], function () {
	Route::post('read-notifications', 'Ajax\UserController@readNotification');
	  Route::post('user/autorenew', 'Ajax\PaymentController@autorenew');
      Route::get('profile', 'Ajax\UserController@show');
      Route::post('get-payment-detail', 'Ajax\UserController@paymentDetail');
      Route::post('upload-profile-pic', 'Ajax\UserController@uploadProfilePic');
      Route::post('send-otp', 'Ajax\UserController@sendOtp');
      Route::post('verify-otp', 'Ajax\UserController@verifyOtp');
      Route::post('profile', 'Ajax\UserController@update');
      Route::get('action-box', 'Ajax\UserController@actionBox');
      Route::get('warehouse', 'Ajax\UserController@warehouse');
      Route::get('invoice', 'Ajax\InvoiceController@index');
      Route::get('ready-for-shipping', 'Ajax\UserController@readyForShipping');
      Route::get('shipments', 'Ajax\UserController@shipments');
      Route::get('history', 'Ajax\UserController@history');
      Route::get('security-question', 'Ajax\SecurityQuestionController@index');
      Route::post('security-update', 'Ajax\SecurityQuestionController@securityUpdate');
      Route::post('user/shipment/{shipment}/shipment-item/{shipment_item}/request-photo', 'Ajax\UserController@requestItemPhoto');
      Route::post('shipment/get-shipping-option', 'Ajax\ShipmentController@shippingOptions');
      Route::post('shipment/{shipment}/shipping-option', 'Ajax\UserController@shippingOptions');
      Route::get('personal-shopper', 'Ajax\PersonalShopperController@index');
      Route::post('personal-shopper', 'Ajax\PersonalShopperController@store');
      Route::post('payment', 'Ajax\PaymentController@create');
      Route::post('read-notifications', 'Ajax\UserController@readNotification');
      Route::get('twofactor/status', 'Ajax\UserController@getAuthyStatus');
      Route::post('twofactor/enable', 'Ajax\UserController@enableTwoFactor');
      Route::post('twofactor/disable', 'Ajax\UserController@disableTwoFactor');
      Route::post('twofactor/verify', 'Ajax\UserController@enableTwoFactorWithVerification');
});

Route::get('downgrade-plan', 'Ajax\PaymentController@downgradePlan');
Route::get('payment/epdq', 'Ajax\PaymentController@payViaEpdq');
Route::get('payment/epdq-response', 'Ajax\PaymentController@handleEpdqResponse');
Route::get('payment/paypal', 'Ajax\PaymentController@payViaPaypal');
Route::get('payment/paypal-response', 'Ajax\PaymentController@handlePaypalResponse');
Route::get('payment/paypal-subscription-response', 'Ajax\PaymentController@handlePaypalSubscriptionResponse');
Route::any('payment/paypal-notify', 'Ajax\PaymentController@paypalNotify');
Route::get('payment/paypal-cancel', 'Ajax\PaymentController@cancelPayment');

Route::get('payment/epdq-cancel', 'Ajax\PaymentController@cancelEpdqPayment');

Route::get('/ip', function () {
    dd(geoip(\Request::ip()));
});
/*
Route::get('/session', function () {
    dd(request()->session()->all());
});
*/
