<?php

namespace App\Modules\Billing;

use App\Modules\Billing\Abstracts\AbstractBilling;
use App\Modules\Billing\DataTransferObjects\CustomerDTO;
use App\Modules\Billing\Interfaces\BillingProviderInterface;

use ChargeBee\ChargeBee\Environment;
use ChargeBee\ChargeBee\Models\Customer;
use ChargeBee\ChargeBee\Models\Subscription;

class ChargeBee extends AbstractBilling implements BillingProviderInterface
{
    function __construct($config)
    {
        parent::__construct($config);
        Environment::configure($this->config['site_key'], $this->config['key']);
    }

    public function subscribe($customer_id, array $itemPriceIds = null)
    {
        # If no item price ids are passed, then we will use the default item price ids
        if (is_null($itemPriceIds)) {
            foreach ($this->getDefaultPlans() as $plan) {
                $items["subscriptionItems"] = [[
                    "itemPriceId" => $plan['item_price_id'][$this->getDefaultCurrency()],
                ]];
                $subscription = Subscription::createWithItems($customer_id,  $items);
            }
            return true;
        }
        # If item price ids are passed, then we will use the item price ids passed
        if (sizeof($itemPriceIds) > 0) {
            foreach ($itemPriceIds as $priceId) {
                $items["subscriptionItems"] = [[
                    "itemPriceId" => $priceId,
                ]];
                $subscription = Subscription::createWithItems($customer_id,  $items);
            }
            return true;
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
}
