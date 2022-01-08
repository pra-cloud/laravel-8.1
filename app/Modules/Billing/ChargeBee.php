<?php

namespace App\Modules\Billing;

use App\Events\TenantSubscribed;
use App\Modules\Billing\Abstracts\AbstractBilling;
use App\Modules\Billing\DataTransferObjects\CustomerDto;
use App\Modules\Billing\Exceptions\BillingProviderException;
use App\Modules\Billing\Interfaces\BillingProviderInterface;

use ChargeBee\ChargeBee\Environment;
use ChargeBee\ChargeBee\Models\Customer;
use ChargeBee\ChargeBee\Models\Subscription;
use ChargeBee\ChargeBee\Models\PortalSession;
use ChargeBee\ChargeBee\Models\HostedPage;

class ChargeBee extends AbstractBilling implements BillingProviderInterface
{
    function __construct($config)
    {
        parent::__construct($config);
        Environment::configure($this->config['site_key'], $this->config['key']);
    }

    public function subscribe(string $customerId, array $itemPriceIds)
    {
        $saas_modules = [];
        $items["subscriptionItems"] = [];
        foreach ($itemPriceIds as $itemPriceId) {
            $items["subscriptionItems"][] = [
                "itemPriceId" => $itemPriceId,
            ];
            $saas_modules[] = $this->getSaasModulesByItemPriceId($itemPriceId);
        }
        try {
            $subscription = Subscription::createWithItems($customerId,  $items);
        } catch (\Exception $e) {
            throw new BillingProviderException("ChargeBee: " . $e->getMessage());
        }

        if (sizeof($saas_modules) > 0) {
            event(new TenantSubscribed($customerId, $saas_modules));
        }

        \Log::debug($saas_modules, $items, $subscription);

        return false;
    }

    public function getDefaultPlanPriceIds(string $currency = null): array
    {
        $currency = $currency ?? $this->getDefaultCurrency();
        return $this->config['default_item_price_ids'][$currency];
    }

    public function getSaasModulesByItemPriceId($itemPriceId): array
    {
        $saas_modules = [];
        foreach ($this->config['saas_modules'][$this->getEnv()] as $saas_module => $itemPriceIds) {
            if (in_array($itemPriceId, $itemPriceIds)) {
                $saas_modules[] = $saas_module;
            }
        }
        return $saas_modules;
    }

    public function createCustomer(CustomerDto $customer)
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

    public function setPortalSessionToken($customerId)
    {
        $result = PortalSession::create(array(
            "customer" => array(
                "id" => $customerId
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
