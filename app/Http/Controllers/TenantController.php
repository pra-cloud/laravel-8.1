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

    public function __construct(TenantService $TENANT_SERVICE, TenantBillingDetailService $TENANT_BILLING_DETAIL_SERVICE)
    {
        $this->TENANT_SERVICE = $TENANT_SERVICE;
        $this->TENANT_BILLING_DETAIL_SERVICE = $TENANT_BILLING_DETAIL_SERVICE;
    }
    
    /**
     * Create Tenant
     * with Tenant Billing Detail
     */
    public function create(Request $request)
    {
        $attributes = $request->all();
        return $this->TENANT_SERVICE->save($attributes);
    }

    /**
     * Edit Tenant
     * with Tenant Billing Detail
     */
    public function update(Request $request)
    {
        $attributes = $request->all();
        return $this->TENANT_SERVICE->update($attributes);
    }

    /**
     * List of Tenants
     * with Tenant Billing Details
     */
    public function list()
    {
        return $this->TENANT_SERVICE->fetchAll();
    }

    /**
     * View Tenant Details
     * with Tenant Billing Detail
     */
    public function show($id)
    {
        return $this->TENANT_SERVICE->fetch($id);
    }

    /**
     * Delete Tenant
     * with Tenant Billing Detail
     */
    public function delete(Request $request)
    {   
        $this->TENANT_BILLING_DETAIL_SERVICE->destroyTenantBillingDetail($request->id);
        return $this->TENANT_SERVICE->destroyTenant($request->id);
    }

}
