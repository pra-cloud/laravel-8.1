<?php

namespace App\Modules\Billing\Traits;

use App\Modules\Billing\Billing;
use App\Modules\Billing\DataTransferObjects\CustomerDTO;
use Exception;

// $this->subscription->create([
//     'tenant_id' => $this->id,
//     'billing_provider' => $provider,
//     'billing_provider_customer_id',
//     'billing_provider_subscription_id',
//     'billing_provider',
//     'plan_id',
//     'plan_expiry'
// ]);
trait Billable
{
    public function subscribe($provider = false, array $planIds = null)
    {
        $billing_provider = Billing::init($provider);

        $customer_id = $this->subscription->billing_provider_customer_id ?? false;

        # Create New Customer on Billing Provider if not exists
        if (!$customer_id) {
            $customer_dto = new CustomerDTO([
                'customerId' => $this->id,
                'email' => $this->email,
                'firstName' => $this->tenantBillingDetail->billing_name,
                'lastName' => '',
                'city' => $this->city,
                'country' => $this->country,
            ]);

            $customer_id = $billing_provider->createCustomer($customer_dto);

            $this->update([
                'tenant_id' => $this->id,
                'billing_provider' => $billing_provider->getProviderName(),
                'billing_provider_customer_id' => $customer_id,
            ]);
        }
        # Subscribe Customer to Plans on Billing Provider
        $billing_provider->subscribe($customer_id, $planIds);
    }
}
