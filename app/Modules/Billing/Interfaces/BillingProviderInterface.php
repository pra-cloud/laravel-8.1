<?php

namespace App\Modules\Billing\Interfaces;

use App\Modules\Billing\DataTransferObjects\CustomerDTO;

interface BillingProviderInterface
{
    /**
     * Subscribe customer to a plan.
     * if planId is null, then customer will be subscribed to the default plans of the billing provider.
     */
    public function subscribe(string $customerId, array $planId = null);

    /**
     * Create a new customer in the billing provider.
     * @param CustomerDTO $customerDTO
     */
    public function createCustomer(CustomerDTO $customer);

    public function fetch(array $attributes): array;

    public function getProviderName(): string;
}
