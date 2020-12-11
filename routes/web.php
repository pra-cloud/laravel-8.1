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
    $router->post('/delete', 'TenantController@delete');
    $router->post('/images', 'TenantController@updateImages');
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
$router->group(['prefix' => '/saas-module'], function () use ($router){
    $router->get('list', 'SaasModuleController@list');
});
