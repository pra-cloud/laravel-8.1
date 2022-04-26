<?php

use Illuminate\Support\Facades\Route;

# Tenant Routes
Route::group(['prefix' => '/tenant'], function () {
    Route::post('/onboarding', 'TenantController@onboarding');
    // Update
    Route::post('/update', 'TenantController@update');
    Route::post('/update/domain', 'TenantController@updateDomain');
    // List
    Route::get('/list', 'TenantController@list');
    Route::get('/listByIds', 'TenantController@listByIds');
    Route::get('/list/business-types', 'TenantController@listBusinessTypes');
    // View
    Route::get('/view', 'TenantController@view');
    Route::get('/view/public', 'TenantController@viewPublic');

    Route::get('/status', 'TenantController@fetchTenantStatus');

    Route::get('/validate', 'TenantController@validate');

    Route::get('/getTenantIdByAdminDomain', 'TenantController@getTenantIdByAdminDomain');
    Route::get('/getTenantIdByDomain', 'TenantController@getTenantIdByDomain');
    Route::get('/getDomainsByTenantId', 'TenantController@getDomainsByTenantId');

    // Delete
    Route::post('/delete', 'TenantController@delete');
    Route::post('/delete-force', 'TenantController@forceDestroy');

    Route::post('/configure-setup', 'TenantController@configureSetup');
});

# SAAS Modules Routes
Route::group(['prefix' => '/saas-module'], function () {
    Route::get('list', 'SaasModuleController@list');
});
