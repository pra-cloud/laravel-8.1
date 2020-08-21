<?php

namespace App\Http\Controllers;

use App\Tenant;
use Illuminate\Http\Request;
use App\Services\TenantService;
use App\Services\TenantBillingDetailService;

class TenantController extends Controller
{
    private $TENANT_SERVICE;
    private $TENANT_BILLING_DETAIL_SERVICE;

    public function __construct(TenantService $tenant_service, TenantBillingDetailService $tenant_billing_detail_service)
    {
        $this->TENANT_SERVICE = $tenant_service;
        $this->TENANT_BILLING_DETAIL_SERVICE = $tenant_billing_detail_service;
    }

    /**
     * Create Tenant
     * with Tenant Billing Detail
     */
    public function create(Request $request)
    {
        $attributes = $request->all();
        $response = $this->TENANT_SERVICE->save($attributes);
        return $response;
    }

    /**
     * Edit Tenant
     * with Tenant Billing Detail
     */
    public function update(Request $request)
    {
        $attributes = $request->all();
        $response = $this->TENANT_SERVICE->update($attributes);
        return $response;
    }

    /**
     * List of Tenants
     * with Tenant Billing Details
     */
    public function list()
    {
        $response = $this->TENANT_SERVICE->fetchAll();
        return $response;
    }

    /**
     * View Tenant Details
     * with Tenant Billing Detail
     */
    public function view(Request $request)
    {
        $response = $this->TENANT_SERVICE->fetch($request->all());
        return $response;
    }

    /**
     * Delete Tenant
     * with Tenant Billing Detail
     */
    public function delete(Request $request)
    {
        $this->TENANT_BILLING_DETAIL_SERVICE->destroy($request->id);
        $response = $this->TENANT_SERVICE->destroy($request->id);
        return $response;
    }

}
