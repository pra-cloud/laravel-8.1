<?php

namespace App\Modules\Billing;

use App\Modules\Billing\Interfaces\BillingProviderInterface;

class ChargeBee implements BillingProviderInterface
{
    public $CONFIG;

    function __construct( array $config )
    {
        $this->CONFIG = $config;
        \ChargeBee_Environment::configure($config['site_key'], $config['key']);
    }

    public function subscribe($customer_id, string $planId = null): array
    {

        $result = \ChargeBee_Subscription::createWithItems($customer_id, array(
            "planId" => $planId ?? $this->CONFIG['default_plan_id'], 
          ));

          $subscription = $result->subscription();
          $customer = $result->customer();
    }

    public function createCustomer(array $attributes)
    {
        $result = \ChargeBee_Customer::create(array(
            // "id" => $attributes['id'], 
            "email" => $attributes['email'], 
            "firstName" => $attributes['tenant_billing_detail']['billing_name'], 
            "lastName" => "",
            "billingAddress" => array(
              "firstName" => $attributes['tenant_billing_detail']['billing_name'],
              "lastName" => "",
              "city" => $attributes['city'],
              "country" => $attributes['country']
              )
            ));

          $customer = $result->customer();

          return $customer->id;
    }

    public function fetch(array $attributes): array
    {
        return [];
    }
}