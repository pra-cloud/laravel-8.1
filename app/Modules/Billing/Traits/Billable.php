<?php

namespace App\Modules\Billing\Traits;

use App\Modules\Billing\Billing;
use App\Modules\Billing\DataTransferObjects\CustomerDto;

trait Billable
{
    public function subscribe(string $provider = null, array $planIds = null)
    {
        $billing_provider = Billing::init($provider);
        $customer_id = $this->billing->billing_provider_customer_id ?? false;
        # Create New Customer on Billing Provider if not exists
        if (!$customer_id) {
            $customer_dto = new CustomerDto([
                'customerId' => $this->id,
                'email' => $this->email,
                'firstName' => $this->billing->billing_name,
                'lastName' => '',
                'city' => $this->city,
                'country' => $this->country,
            ]);

            $customer_id = $billing_provider->createCustomer($customer_dto);

            $this->billing->update([
                'billing_provider' => $billing_provider->getProviderName(),
                'billing_provider_customer_id' => $customer_id,
            ]);
        }
        # Get default Plan
        if (is_null($planIds)) {
            $planIds = $billing_provider->getDefaultPlanPriceIds();
        }
        # Subscribe Customer to Plans on Billing Provider
        $billing_provider->subscribe($customer_id, $planIds);
        # Everything went fine
        return true;
    }
}
