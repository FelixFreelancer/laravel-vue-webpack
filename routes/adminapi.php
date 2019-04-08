<?php

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

Route::post('login', 'LoginController@index');
Route::post('google-authentication', 'LoginController@googleAuthenticationStore');

Route::group(['middleware'=>['adminapi']], function () {

  Route::post('read-notifications', 'Admin\UserController@readNotification');


  Route::post('user/status', 'Admin\UserController@updateStatus');

  Route::get('country-listing', 'Admin\CountryController@dropDown');

  Route::post('user/verify', 'Admin\UserController@verifyUserRequest');
  Route::post('user/unverify', 'Admin\UserController@unVerifyUserRequest');

  Route::get('pending', 'Admin\ShipmentController@getShipment');
  Route::post('shippingOptions', 'ShipmentController@shippingOptions');

  Route::get('inquiry', 'Admin\ContactController@index');
  Route::delete('inquiry/{id}', 'Admin\ContactController@delete');
  Route::post('inquiry-response', 'Admin\ContactController@response');

  Route::get('get-counter', 'Admin\HomeController@getCounter');

  Route::get('search', 'Admin\HomeController@globalSearch');

  Route::get('mail', 'Admin\MailController@index');
  Route::post('mail', 'Admin\MailController@store');


  Route::get('invoices', 'Admin\InvoiceController@index');

  Route::get('transaction', 'Admin\PaymentController@index');

  Route::get('settings', 'Admin\SettingController@index');
  Route::post('settings/{id}', 'Admin\SettingController@update');

  Route::get('logs', 'Admin\LogController@index');
  Route::delete('logs/{id}', 'Admin\LogController@destroy');
  Route::delete('logs', 'Admin\LogController@deleteAll');
  
  Route::get('permission', 'Admin\PermissionController@index');

  Route::get('notification', 'Admin\HomeController@getNotification');

  Route::get('role', 'Admin\RoleController@index');
  Route::get('role/{id}', 'Admin\RoleController@show');
  Route::post('role', 'Admin\RoleController@store');
  Route::post('role/{id}', 'Admin\RoleController@update');

  Route::get('user', 'Admin\UserController@index');
  Route::get('user/{id}', 'Admin\UserController@show');
  Route::get('user/{id}/metadata', 'Admin\UserController@getMetaData');
  Route::post('user', 'Admin\UserController@store');
  Route::post('user/{id}', 'Admin\UserController@update');
  Route::delete('user/{id}', 'Admin\UserController@delete');
  Route::post('change-password', 'Admin\UserController@changePassword');

  Route::get('shipping-progress/{id}', 'Admin\ShipmentController@getProgress');
  Route::get('shipment', 'Admin\ShipmentController@index');
  Route::get('shipment/{id}', 'Admin\ShipmentController@show');
  Route::post('shipment', 'Admin\ShipmentController@store');
  Route::post('shipment/{id}', 'Admin\ShipmentController@update');
  Route::delete('shipment/{id}', 'Admin\ShipmentController@delete');
  Route::post('shipment/{id}/update-status', 'Admin\ShipmentController@updateStatus');
  Route::post('shipment/{id}/revert-status', 'Admin\ShipmentController@revertStatus');

  Route::get('insurance', 'Admin\ShipmentController@getInsurance');

  Route::get('country', 'Admin\CountryController@index');
  Route::post('country', 'Admin\CountryController@store');
  Route::get('country/{id}', 'Admin\CountryController@show');
  Route::post('country/{id}', 'Admin\CountryController@update');
  Route::delete('country/{id}', 'Admin\CountryController@delete');

  Route::get('quotations', 'Admin\QuotationController@index');
  Route::get('quotations/{quotation}', 'Admin\QuotationController@show');
  Route::post('quotations/{quotation}', 'Admin\QuotationController@update');
  Route::delete('quotations/{id}', 'Admin\QuotationController@delete');
  Route::get('uk-warehouse-address', 'Admin\WarehouseController@index');
  Route::post('uk-warehouse-address', 'Admin\WarehouseController@store');

  Route::post('medias/{type}', 'Admin\MediaController@store');
  Route::delete('medias/{media}', 'Admin\MediaController@delete');

  Route::get('mail-contents', 'Admin\MailContentController@index');

  Route::get('twofactor/status', 'Admin\UserController@getAuthyStatus');
  Route::post('twofactor/enable', 'Admin\UserController@enableTwoFactor');
  Route::post('twofactor/disable', 'Admin\UserController@disableTwoFactor');
  Route::post('twofactor/verify', 'Admin\UserController@enableTwoFactorWithVerification');


  Route::group(['prefix' => 'shipment/{shipment}'], function () {
   
      Route::get('shipment-items/{item}', 'Admin\ShipmentItemController@show');
      Route::post('shipment-items/{item}', 'Admin\ShipmentItemController@update');
      Route::get('shipment-items', 'Admin\ShipmentItemController@index');
      Route::post('shipment-items', 'Admin\ShipmentItemController@store');
     
  });
  Route::delete('shipment-items/{item}', 'Admin\ShipmentItemController@delete');
  Route::post('upload-profile-pic', 'Admin\UserController@uploadProfilePic');
  Route::group(['prefix' => 'ajax'], function () {
      Route::get('user', 'Admin\UserController@getList');
  });
});
