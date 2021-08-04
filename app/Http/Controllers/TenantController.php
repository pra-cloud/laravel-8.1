<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TenantRepository;
use App\Repositories\SettingsRepository;
use GrahamCampbell\ResultType\Success;

class TenantController extends Controller
{
    private $TENANT_REPOSITORY;
    private $SETTINGS_REPOSITORY;

    public function __construct(SettingsRepository $SettingsRepository, TenantRepository $TenantRepository)
    {
        $this->TENANT_REPOSITORY = $TenantRepository;
        $this->SETTINGS_REPOSITORY = $SettingsRepository;
    }

    /**
     * Create Tenant
     * with Tenant Billing Detail
     */
    public function create(Request $request)
    {
        try {
            $response = $this->TENANT_REPOSITORY->save($request->all());
            return $this->successResponse(null, $response);
        } catch (\Exception $e) {
            $errors = $this->TENANT_REPOSITORY->getErrors();
            return $this->errorResponse($e->getMessage(), $errors);
        }
    }

    /**
     * Edit Tenant
     * with Tenant Billing Detail
     */
    public function update(Request $request)
    {
        try {
            $response = $this->TENANT_REPOSITORY->update($request->all());
            return $this->successResponse(null, $response);
        } catch (\Exception $e) {
            $errors = $this->TENANT_REPOSITORY->getErrors();
            return $this->errorResponse($e->getMessage(), $errors);
        }
    }

    /**
     * List of Tenants
     * with Tenant Billing Details
     */
    public function list()
    {
        try {
            $response = $this->TENANT_REPOSITORY->fetchAll();
            return $this->successResponse(null, $response);
        } catch (\Exception $e) {
            $errors = $this->TENANT_REPOSITORY->getErrors();
            return $this->errorResponse($e->getMessage(), $errors);
        }
    }

    public function listBusinessTypes()
    {
        $store_types = [
            'food_delivery' => 'Food Delivery Solution',
            'grocery_delivery' => 'Grocery Delivery Solution',
            'bakery_delivery' => 'Bakery Delivery Solution',
            'pet_food_delivery' => 'Pet Food Delivery Solution',
            'bouquet_delivery' => 'Bouquets Delivery Solution',
            'stationary_delivery' => 'Stationary Delivery Solution',
            'accessories_delivery' => 'Accessories Delivery Solution',
            'clothing_delivery' => 'Clothing Delivery Solution',
            'beverages_delivery' => 'Beverages Delivery Solution',
        ];

        return $this->successResponse("List of store types", $store_types);
    }

    /**
     * View Tenant Details
     * with Tenant Billing Detail
     */
    public function view(Request $request)
    {
        try {
            $response = $this->TENANT_REPOSITORY->fetch($request->all());
            return $this->successResponse(null, $response);
        } catch (\Exception $e) {
            $errors = $this->TENANT_REPOSITORY->getErrors();
            return $this->errorResponse($e->getMessage(), $errors);
        }
    }

    /**
     * Delete Tenant
     * with Tenant Billing Detail
     */
    public function delete(Request $request)
    {
        try {
            $response = $this->TENANT_REPOSITORY->destroy($request->id);
            return $this->successResponse(null, $response);
        } catch (\Exception $e) {
            $errors = $this->TENANT_REPOSITORY->getErrors();
            return $this->errorResponse($e->getMessage(), $errors);
        }
    }

    public function updateImages(Request $request)
    {
        try {
            $response = $this->TENANT_REPOSITORY->updateImages($request->all());
            return $this->successResponse(null, $response);
        } catch (\Exception $e) {
            $errors = $this->TENANT_REPOSITORY->getErrors();
            return $this->errorResponse($e->getMessage(), $errors);
        }
    }

    public function updateDomain(Request $request)
    {
        try {
            $response = $this->TENANT_REPOSITORY->updateDomain($request->all());
            return $this->successResponse(null, $response);
        } catch (\Exception $e) {
            $errors = $this->TENANT_REPOSITORY->getErrors();
            return $this->errorResponse($e->getMessage(), $errors);
        }
    }

    public function getTenantIdByAdminDomain(Request $request)
    {
        try {
            $response = $this->TENANT_REPOSITORY->getTenantIdByAdminDomain($request->all());
            return $this->successResponse(null, $response);
        } catch (\Exception $e) {
            $errors = $this->TENANT_REPOSITORY->getErrors();
            return $this->errorResponse($e->getMessage(), $errors);
        }
    }

    public function getTenantIdByDomain(Request $request)
    {
        try {
            $response = $this->TENANT_REPOSITORY->getTenantIdByDomain($request->all());
            return $this->successResponse(null, $response);
        } catch (\Exception $e) {
            $errors = $this->TENANT_REPOSITORY->getErrors();
            return $this->errorResponse($e->getMessage(), $errors);
        }
    }

    public function fetchTenantStatus(Request $request)
    {
        try {
            $response = $this->TENANT_REPOSITORY->fetchTenantStatus($request->all());
            return $this->successResponse(null, $response);
        } catch (\Exception $e) {
            $errors = $this->TENANT_REPOSITORY->getErrors();
            return $this->errorResponse($e->getMessage(), $errors);
        }
    }

    public function configureSetup(Request $request)
    {
        try {
            $response = $this->TENANT_REPOSITORY->configureSetup($request->all());
            return $this->successResponse(null, $response);
        } catch (\Exception $e) {
            $errors = $this->TENANT_REPOSITORY->getErrors();
            return $this->errorResponse($e->getMessage(), $errors);
        }
    }

    public function tenantExists(Request $request)
    {
        try {
            $response = $this->TENANT_REPOSITORY->tenantExists($request->all());
            return $this->successResponse(null, $response);
        } catch (\Exception $e) {
            $errors = $this->TENANT_REPOSITORY->getErrors();
            return $this->errorResponse($e->getMessage(), $errors);
        }
    }

    public function onboarding(Request $request)
    {
        try {
            $response = $this->TENANT_REPOSITORY->onboarding($request->all());
            return $this->successResponse(null, $response);
        } catch (\Exception $e) {
            $errors = $this->TENANT_REPOSITORY->getErrors();
            return $this->errorResponse($e->getMessage(), $errors);
        }
    }

    public function forceDestroy(Request $request)
    {
        try {
            $response = $this->TENANT_REPOSITORY->forceDestroy($request->id);
            return $this->successResponse(null, $response);
        } catch (\Exception $e) {
            $errors = $this->TENANT_REPOSITORY->getErrors();
            return $this->errorResponse($e->getMessage(), $errors);
        }
    }
}
