<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return "Tenant Service";
});

/**
 * Tenant Module Routes
 */

/**
 * Tenant Routes
 */
Route::group(['prefix' => '/tenant'], function () {
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
Route::group(['prefix' => '/saas-plan'], function () {
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
Route::group(['prefix' => '/saas-module'], function () {
    Route::get('list', 'SaasModuleController@list');
});
