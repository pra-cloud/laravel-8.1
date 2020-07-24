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
    return $router->app->version();
});

// TENANT MODULE ROUTES
$router->group([ 'prefix' => '/tenant-module' ], function () use ($router) {
    
});

// TENANT ROUTES
$router->group([ 'prefix' => '/tenant' ], function () use ($router) {

    $router->post('/create', 'TenantController@create');
    $router->post('/update', 'TenantController@update');
    $router->get('/list', 'TenantController@list');
    $router->get('/show/{id}', 'TenantController@show');
    $router->post('/delete', 'TenantController@delete');
    
});