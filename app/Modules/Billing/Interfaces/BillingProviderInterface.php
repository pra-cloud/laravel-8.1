<?php

namespace App\Modules\Billing\Interfaces;

interface BillingProviderInterface
{
    public function subscribe(array $attributes) : array;

    public function fetch(array $attributes) : array;
}