<?php

namespace App\Http\Controllers;

use App\Repositories\SaasPlanRepository;
use App\Tenant;
use Illuminate\Http\Request;
use App\Repositories\TenantRepository;
use App\Repositories\TenantBillingDetailService;

class TenantController extends Controller
{
    private $TENANT_REPOSITORY;

    public function __construct(TenantRepository $tenantRepository)
    {
        $this->TENANT_REPOSITORY = $tenantRepository;
    }

    /**
     * Create Tenant
     * with Tenant Billing Detail
     */
    public function create(Request $request)
    {
        $attributes = $request->all();
        $response = $this->TENANT_REPOSITORY->save($attributes);

        return $this->processServiceResponse($response);
    }

    /**
     * Edit Tenant
     * with Tenant Billing Detail
     */
    public function update(Request $request)
    {
        $attributes = $request->all();
        $response = $this->TENANT_REPOSITORY->update($attributes);
        return $this->processServiceResponse($response);
    }

    /**
     * List of Tenants
     * with Tenant Billing Details
     */
    public function list()
    {
        $response = $this->TENANT_REPOSITORY->fetchAll();
        return $this->processServiceResponse($response);
    }

    /**
     * View Tenant Details
     * with Tenant Billing Detail
     */
    public function view(Request $request)
    {
        $response = $this->TENANT_REPOSITORY->fetch($request->all());
        return $this->processServiceResponse($response);
    }

    /**
     * Delete Tenant
     * with Tenant Billing Detail
     */
    public function delete(Request $request)
    {
        $response = $this->TENANT_REPOSITORY->destroy($request->id);
        return $this->processServiceResponse($response);
    }

    public function updateImages(Request $request)
    {
        $response = $this->TENANT_REPOSITORY->updateImages($request->all());
        return $response;
    }

    public function getTenantIdByAdminDomain(Request $request)
    {
        $response = $this->TENANT_REPOSITORY->getTenantIdByAdminDomain($request->all());
        return $response;
    }

    public function getTenantIdByDomain(Request $request)
    {
        $response = $this->TENANT_REPOSITORY->getTenantIdByDomain($request->all());
        return $response;
    }

    public function fetchTenantStatus(Request $request)
    {
        $response = $this->TENANT_REPOSITORY->fetchTenantStatus($request->all());
        return $response;
    }
}
