<?php

namespace App\Http\Controllers;

use App\Modules\Billing\Billing;
use App\Rules\ValidateBillingProvider;
use App\Tenant;
use Illuminate\Http\Request;

class AuthBillingController extends Controller
{
    public function handle(Request $request)
    {
        # Validate the request
        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
        ]);
        # Fetch the tenant
        $tenant = Tenant::find($request->tenant_id);
        # Set billing provider for the tenant
        $provider = $tenant->billing->billing_provider;
        # Set billing provider customer id for the tenant
        $customer_id = $tenant->billing->billing_provider_customer_id;

        $billing = Billing::init($provider);
        $output = $billing->setPortalSessionToken($customer_id);

        return $this->successResponse(null, $output);
    }
}
