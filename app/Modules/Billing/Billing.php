<?php

namespace App\Modules\Billing;

class Billing
{
    public static function init($provider = false)
    {
        if (!$provider) {
            $provider = self::getDefaultProvider();
        }

        $config = config('billing.providers.' . $provider);
        $config['provider'] = $provider;
        $class = self::providerClass($provider);

        return new $class($config);
    }

    public static function getDefaultProvider()
    {
        $billing_providers = array_keys(config('billing.providers'));
        $default_provider = config('billing.default_provider');
        if (!in_array($default_provider, $billing_providers)) {
            throw new \Exception('Default ' . $default_provider . ' billing provider not found');
        }
        return $default_provider;
    }

    public static function providerClass($provider)
    {
        $class = [
            'chargebee' => ChargeBee::class,
        ];

        return $class[$provider] ?? false;
    }
}
