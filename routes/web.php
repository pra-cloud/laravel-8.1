<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return "Tenant Service Dev: " . $router->app->version();
});

/**
 * Tenant Module Routes
 */
$router->group([ 'prefix' => '/tenant-module' ], function () use ($router) {
});

/**
 * Tenant Routes
 */
$router->group([ 'prefix' => '/tenant' ], function () use ($router) {
    $router->post('/create', 'TenantController@create');
    $router->post('/update', 'TenantController@update');
    $router->get('/list', 'TenantController@list');
    $router->get('/view', 'TenantController@view');
    $router->get('/status', 'TenantController@fetchTenantStatus');
    $router->get('/getTenantIdByAdminDomain', 'TenantController@getTenantIdByAdminDomain');
    $router->get('/getTenantIdByDomain', 'TenantController@getTenantIdByDomain');
    $router->post('/delete', 'TenantController@delete');
    $router->post('/images', 'TenantController@updateImages');
    $router->post('/configure-setup', 'TenantController@configureSetup');
    $router->get('/exists', 'TenantController@tenantExists');
});

/**
 * SAAS Plan Routes
 */
$router->group([ 'prefix' => '/saas-plan' ], function () use ($router) {
    $router->post('/create', 'SaasPlanController@create');
    $router->post('/update', 'SaasPlanController@update');
    $router->get('/list', 'SaasPlanController@list');
    $router->get('/view', 'SaasPlanController@view');
    $router->post('/delete', 'SaasPlanController@delete');
    $router->get('/list-billing-cycles', 'SaasPlanController@listPlanBillingCycle');
});

/**
 * SAAS Modules Routes
 */
$router->group(['prefix' => '/saas-module'], function () use ($router) {
    $router->get('list', 'SaasModuleController@list');
});

/**
 * Settings Routes
 */
$router->group(['prefix' => '/settings', 'namespace' => 'Settings'], function () use ($router) {
    $router->post('/currency', 'SettingController@updateCurrency');

    $router->post('/delivery-settings/delivery-calculation', 'DeliverySettingsController@updateDeliveryCalculations');
    $router->get('/delivery-settings/delivery-calculation', 'DeliverySettingsController@fetchDeliveryCalculations');

    $router->post('/delivery-settings/delivery-fee-source', 'DeliverySettingsController@updateDeliveryFeeSource');
    $router->get('/delivery-settings/delivery-fee-source', 'DeliverySettingsController@fetchDeliveryFeeSource');

    $router->post('/delivery-settings/flat-delivery-fee', 'DeliverySettingsController@updateFlatDeliveryFee');
    $router->get('/delivery-settings/flat-delivery-fee', 'DeliverySettingsController@fetchFlatDeliveryFee');

    $router->post('/apikey/gmap', 'ApiKeyController@updateGmapApiKey');
    $router->get('/apikey/gmap', 'ApiKeyController@fetchGmapApiKey');
});
