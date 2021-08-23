<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () use ($router) {
    return "Tenant Service Dev: " . $router->app->version();
});

/**
 * Tenant Module Routes
 */

Route::group([ 'prefix' => '/tenant/is-serviceable-area' ], function () {
    Route::post('/', 'ServiceableAreaController@checkIfPresentOrnot');
});

/**
 * Tenant Routes
 */
Route::group([ 'prefix' => '/tenant' ], function () use ($router) {
    Route::post('/onboarding', 'TenantController@onboarding');
    Route::post('/create', 'TenantController@create');

    // Update
    Route::post('/update', 'TenantController@update');
    Route::post('/update/domain', 'TenantController@updateDomain');

    // List
    Route::get('/list', 'TenantController@list');
    Route::get('/list/business-types', 'TenantController@listBusinessTypes');

    // View
    Route::get('/view', 'TenantController@view');
    Route::get('/view/public', 'TenantController@viewPublic');

    Route::get('/status', 'TenantController@fetchTenantStatus');
    Route::get('/getTenantIdByAdminDomain', 'TenantController@getTenantIdByAdminDomain');
    Route::get('/getTenantIdByDomain', 'TenantController@getTenantIdByDomain');

    // Delete
    Route::post('/delete', 'TenantController@delete');
    Route::post('/delete-force', 'TenantController@forceDestroy');

    Route::post('/images', 'TenantController@updateImages');
    Route::post('/configure-setup', 'TenantController@configureSetup');
    Route::get('/exists', 'TenantController@tenantExists');
});

/**
 * SAAS Plan Routes
 */
Route::group([ 'prefix' => '/saas-plan' ], function () use ($router) {
    Route::post('/create', 'SaasPlanController@create');
    Route::post('/update', 'SaasPlanController@update');
    Route::get('/list', 'SaasPlanController@list');
    Route::get('/view', 'SaasPlanController@view');
    Route::post('/delete', 'SaasPlanController@delete');
    Route::get('/list-billing-cycles', 'SaasPlanController@listPlanBillingCycle');
});

/**
 * SAAS Modules Routes
 */
Route::group(['prefix' => '/saas-module'], function () use ($router) {
    Route::get('list', 'SaasModuleController@list');
});

/**
 * Settings Routes
 */
Route::group(['prefix' => '/settings', 'namespace' => 'Settings'], function () use ($router) {
    Route::post('/currency', 'SettingController@updateCurrency');

    Route::post('/delivery-settings/delivery-calculation', 'DeliverySettingsController@updateDeliveryCalculations');
    Route::get('/delivery-settings/delivery-calculation', 'DeliverySettingsController@fetchDeliveryCalculations');

    Route::post('/delivery-settings/delivery-fee-source', 'DeliverySettingsController@updateDeliveryFeeSource');
    Route::get('/delivery-settings/delivery-fee-source', 'DeliverySettingsController@fetchDeliveryFeeSource');

    Route::post('/delivery-settings/flat-delivery-fee', 'DeliverySettingsController@updateFlatDeliveryFee');
    Route::get('/delivery-settings/flat-delivery-fee', 'DeliverySettingsController@fetchFlatDeliveryFee');

    Route::post('/apikey/gmap', 'ApiKeyController@updateGmapApiKey');
    Route::get('/apikey/gmap', 'ApiKeyController@fetchGmapApiKey');
});
