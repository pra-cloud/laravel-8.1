<?php

namespace App\Modules\Billing\Interfaces;

use App\Modules\Billing\DataTransferObjects\CustomerDto;

interface BillingProviderInterface
{
    /**
     * Subscribe customer to a plan.
     * Customer will be subscribed to the default plans of the billing provider.
     */
    public function subscribe(string $customerId);

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
