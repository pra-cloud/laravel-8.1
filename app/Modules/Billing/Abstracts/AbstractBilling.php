<?php

namespace App\Modules\Billing\Abstracts;

class AbstractBilling
{
    public $config;

    function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getProviderName(): string
    {
        return $this->config['provider'];
    }

    public function getEnv(): string
    {
        return config('billing.env');
    }

    public function getDefaultCurrency(): string
    {
        return $this->config['default_currency'];
    }

    public function getDefaultPlanPriceIds(string $currency = null): array
    {
        return [];
    }
}
