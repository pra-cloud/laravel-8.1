<?php

namespace App\Modules\Billing\Traits;

use App\Models\TenantSubscription;
use App\Modules\Billing\Billing;
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
    public function subscribe( $planId = null )
    {
        $billing = Billing::init();
        $provider = Billing::getDefaultProvider();

        $customer_id = $this->subscription->billing_provider_customer_id ?? false;

        if (!$customer_id) {
            $customer_id = $billing->createCustomer( $this->toArray() );
     
            $this->subscription()->create([
                'tenant_id' => $this->id,
                'billing_provider' => $provider,
                'billing_provider_customer_id' => $customer_id,
            ]);
        }

        $billing->subscribe( $customer_id );
    }

    public function subscription()
    {
        return $this->hasOne(TenantSubscription::class);
    }
}