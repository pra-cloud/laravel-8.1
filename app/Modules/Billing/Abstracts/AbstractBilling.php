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

    public function getDefaultPlans(): array
    {
        return $this->config['default_plans'];
    }

    public function getDefaultCurrency(): string
    {
        return $this->config['default_currency'];
    }
}
