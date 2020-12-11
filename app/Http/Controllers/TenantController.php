<?php

namespace App\Http\Controllers;

use App\Services\SaasPlanService;
use App\Tenant;
use Illuminate\Http\Request;
use App\Services\TenantService;
use App\Services\TenantBillingDetailService;

class TenantController extends Controller
{
    private $TENANT_SERVICE;

    public function __construct(TenantService $tenant_service)
    {
        $this->TENANT_SERVICE = $tenant_service;
    }

    /**
     * Create Tenant
     * with Tenant Billing Detail
     */
    public function create(Request $request)
    {
        $attributes = $request->all();
        $response = $this->TENANT_SERVICE->save($attributes);

        return $this->processServiceResponse($response);
    }

    /**
     * Edit Tenant
     * with Tenant Billing Detail
     */
    public function update(Request $request)
    {
        $attributes = $request->all();
        $response = $this->TENANT_SERVICE->update($attributes);
        return $this->processServiceResponse($response);
    }

    /**
     * List of Tenants
     * with Tenant Billing Details
     */
    public function list()
    {
        $response = $this->TENANT_SERVICE->fetchAll();
        return $this->processServiceResponse($response);
    }

    /**
     * View Tenant Details
     * with Tenant Billing Detail
     */
    public function view(Request $request)
    {
        $response = $this->TENANT_SERVICE->fetch($request->all());
        return $this->processServiceResponse($response);
    }

    /**
     * Delete Tenant
     * with Tenant Billing Detail
     */
    public function delete(Request $request)
    {
        $response = $this->TENANT_SERVICE->destroy($request->id);
        return $this->processServiceResponse($response);
    }

    public function updateImages(Request $request)
    {
        $response = $this->TENANT_SERVICE->updateImages($request->all());
        return $response;
    }

}
