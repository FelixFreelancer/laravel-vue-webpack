<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'HomeController@index')->name('home');

Route::resource('users', 'UserController');

Route::resource('admins', 'AdminController');

Route::resource('countries', 'CountryController');

Route::get('shipment/payment-confirmation', 'ShipmentController@paymentConfirmaion')->name('shipments.payment-confirmation');
Route::post('shipment/payment-confirmation/{id}', 'ShipmentController@confirmPayment')->name('shipments.confirm-payment');

Route::get('shipment/ready-for-shipment', 'ShipmentController@readyForShipment')->name('shipments.ready-for-shipment');
Route::post('shipment/ready-for-shipment', 'ShipmentController@shipped')->name('shipments.shipment');

Route::get('shipment/delivered', 'ShipmentController@readyForDelivered')->name('shipments.ready-for-delivered');
Route::post('shipment/delivered', 'ShipmentController@delivered')->name('shipments.delivered');

Route::resource('shipments', 'ShipmentController');

Route::get('quotations', 'QuotationController@index')->name('quotations.index');
Route::get('quotations/{quotation}', 'QuotationController@show')->name('quotations.show');
Route::put('quotations/{quotation}', 'QuotationController@update')->name('quotations.update');

Route::group(['prefix' => 'shipments/{shipment}'], function () {
    Route::resource('shipment-items', 'ShipmentItemController');
});

Route::get('uk-warehouse-address', 'WarehouseController@index')->name('uk.warehouse.address.index');
Route::post('uk-warehouse-address', 'WarehouseController@store')->name('uk.warehouse.address.store');

Route::group(['prefix' => 'ajax'], function () {
    Route::get('datatable-admins', 'AdminController@indexDatatable');

    Route::get('users/search', 'UserController@search');
    Route::get('datatable-users', 'UserController@indexDatatable');
    Route::get('users/{user}', 'UserController@ajaxShow');

    Route::get('datatable-countries', 'CountryController@indexDatatable');

    Route::get('datatable-shipments', 'ShipmentController@indexDatatable');

    Route::get('datatable-quotations', 'QuotationController@indexDatatable');
    Route::get('datatable-quotations/{quotation}', 'QuotationController@showDatatable');

    Route::post('medias/{type}', 'MediaController@store');
    Route::delete('medias/{media}', 'MediaController@destroy');

    Route::group(['prefix' => 'shipments/{shipment}'], function () {
        Route::get('datatable-shipment-items', 'ShipmentItemController@indexDatatable');
    });
});
