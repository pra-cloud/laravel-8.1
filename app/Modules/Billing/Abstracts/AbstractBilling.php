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

    public function getPlans(): array
    {
        return $this->config['plans'];
    }

    public function getDefaultCurrency(): string
    {
        return $this->config['default_currency'];
    }
}
