<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Repositories\TenantRepository;
use Hyperzod\HyperzodServiceFunctions\Enums\HttpHeaderKeyEnum;
use Hyperzod\HyperzodServiceFunctions\HyperzodServiceFunctions;
use Illuminate\Support\Str;

class TenantController extends Controller
{
    private $TENANT_REPOSITORY;

    public function __construct(TenantRepository $TenantRepository)
    {
        $this->TENANT_REPOSITORY = $TenantRepository;
    }

    /**
     * Edit Tenant
     
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
     s
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

    public function viewPublic(Request $request)
    {
        try {
            $response = $this->TENANT_REPOSITORY->viewPublic($request->all());
            return $this->successResponse(null, $response);
        } catch (\Exception $e) {
            $errors = $this->TENANT_REPOSITORY->getErrors();
            return $this->errorResponse($e->getMessage(), $errors);
        }
    }

    /**
     * Delete Tenant
     
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

    public function updateDomain(Request $request)
    {
        $response = $this->TENANT_REPOSITORY->updateDomain($request->all());
        return $this->successResponse(null, $response);
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

    public function getDomainsByTenantId(Request $request)
    {
        try {
            $response = $this->TENANT_REPOSITORY->getDomainsByTenantId($request->all());
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

    public function listByIds(Request $request)
    {
        try {
            $response = $this->TENANT_REPOSITORY->listByIds($request->all());
            return $this->successResponse(null, $response);
        } catch (\Exception $e) {
            $errors = $this->TENANT_REPOSITORY->getErrors();
            return $this->errorResponse($e->getMessage(), $errors);
        }
    }

    public function validate()
    {
        $validated = request()->validate([
            HttpHeaderKeyEnum::TENANT => 'required'
        ]);

        // Check if X-Tenant is a domain without www. and redirect 301 to the same domain with www.
        $assumed_domain = explode(".", $validated[HttpHeaderKeyEnum::TENANT]);
        if (count($assumed_domain) == 2) {
            $redirect_target = "https://www." . $validated[HttpHeaderKeyEnum::TENANT];
            return $this->successResponse("Redirect to {$redirect_target}", [
                'redirect_to' => $redirect_target
            ], 301, true);
        }

        // Parse slug if its a native ordering domain - {slug}.{hyperzodOrderingAppNativeDomainTLD()}
        $has_native_ordering_domain = Str::contains(
            $validated[HttpHeaderKeyEnum::TENANT],
            HyperzodServiceFunctions::hyperzodOrderingAppNativeDomainTLD()
        );
        if ($has_native_ordering_domain) {
            $native_ordering_domain = explode(".", $validated[HttpHeaderKeyEnum::TENANT]);
            $validated[HttpHeaderKeyEnum::TENANT] = $native_ordering_domain[0];
        }

        $tenant = Tenant::select('id', 'domain', 'admin_domain', 'name', 'slug', 'status', 'is_open')
            ->where('id', $validated[HttpHeaderKeyEnum::TENANT])
            ->OrWhere('domain', $validated[HttpHeaderKeyEnum::TENANT])
            ->OrWhere('slug', $validated[HttpHeaderKeyEnum::TENANT])
            ->OrWhere('admin_domain', $validated[HttpHeaderKeyEnum::TENANT])
            ->first();

        if (!$tenant) {
            return $this->errorResponse("Invalid domain", null, 404, true);
        }

        $tenant['saas_modules'] = $tenant->saasModules()->pluck('module_name');

        return $this->successResponse(null, $tenant);
    }
}
