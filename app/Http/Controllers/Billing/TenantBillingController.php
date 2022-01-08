<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Modules\Billing\Billing;
use App\Repositories\TenantRepository;
use Illuminate\Http\Request;

class TenantBillingController extends Controller
{
    var $tenantRepository;

    function __construct(TenantRepository $tenantRepository)
    {
        $this->tenantRepository = $tenantRepository;
    }

    public function listPlansForTenant(Request $request)
    {
        $validated = $request->validate(['tenant_id' => 'required|integer']);
        $tenant = $this->fetchTenant($validated['tenant_id']);

        $billing = Billing::init($tenant->billing->billing_provider);
        return $this->successResponse(null, $billing->getPlans());
    }

    private function fetchTenant(int $tenant_id)
    {
        return $this->tenantRepository->fetch(['id' => $tenant_id]);
    }
}
