<?php

namespace App\Modules\Billing;

use App\Events\TenantSubscribed;
use App\Modules\Billing\Abstracts\AbstractBilling;
use App\Modules\Billing\DataTransferObjects\CustomerDTO;
use App\Modules\Billing\Interfaces\BillingProviderInterface;

use ChargeBee\ChargeBee\Environment;
use ChargeBee\ChargeBee\Models\Customer;
use ChargeBee\ChargeBee\Models\Subscription;
use ChargeBee\ChargeBee\Models\PortalSession;

class ChargeBee extends AbstractBilling implements BillingProviderInterface
{
    function __construct($config)
    {
        parent::__construct($config);
        Environment::configure($this->config['site_key'], $this->config['key']);
    }

    public function subscribe($customer_id)
    {
        $saas_modules = [];
        foreach ($this->getPlans() as $plan) {
            $items["subscriptionItems"] = [[
                "itemPriceId" => $plan['default_item_price_id'][$this->getDefaultCurrency()],
            ]];
            $subscription = Subscription::createWithItems($customer_id,  $items);
            $saas_modules[] = $plan['saas_modules'];
        }

        if (sizeof($saas_modules) > 0) {
            event(new TenantSubscribed($customer_id, $saas_modules));
        }

        return false;
    }

    public function createCustomer(CustomerDTO $customer)
    {
        $result = Customer::create(array(
            // "id" => $customer->customerId,
            "email" => $customer->email,
            "firstName" => $customer->firstName,
            "lastName" => "",
            "billingAddress" => array(
                "firstName" => $customer->firstName,
                "lastName" => "",
                "city" => $customer->city,
                "country" => $customer->country
            )
        ));

        try {
            $customer = $result->customer();
            return $customer->id;
        } catch (\Exception $e) {
            throw new \Exception("Error chargebee creating customer: " . $e->getMessage());
        }
    }

    public function fetch(array $attributes): array
    {
        return [];
    }

    public function setPortalSessionToken($customer_id)
    {
        $result = PortalSession::create(array(
            "customer" => array(
                "id" => $customer_id
            )
        ));
        $portalSession = $result->portalSession();
        return $portalSession->getValues(); // send the output in a json format
    }

    /**
     * Process Chargebee Webhooks
     * @param array $payload
     */
    public function processWebhook(array $payload)
    {
    }
}
