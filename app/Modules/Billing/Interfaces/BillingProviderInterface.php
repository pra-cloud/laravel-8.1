<?php

namespace App\Modules\Billing\Interfaces;

use App\Modules\Billing\DataTransferObjects\CustomerDto;

interface BillingProviderInterface
{
    /**
     * Subscribe customer to plans.
     *
     * @param string $customerId
     * @param array $planIds
     * @return void
     */
    public function subscribe(string $customerId, array $planIds);

    /**
     * Create a new customer in the billing provider.
     * @param CustomerDto $CustomerDto
     */
    public function createCustomer(CustomerDto $customer);

    public function fetch(array $attributes): array;

    public function getProviderName(): string;

    public function processWebhook(array $payload);

    public function setPortalSessionToken(array $params);
}
