<?php

namespace App\Modules\Billing;

class Billing
{
    public static function init( $provider = null )
    {
        $provider = self::getDefaultProvider();
        $config = config('billing.providers.' . $provider);

        $class = self::providerClass($provider);
        
        if ($class) {
            return new $class($config);
        }
    }

    public static function getDefaultProvider()
    {
        return config('billing.default_provider');
    }

    public static function providerClass($provider)
    {
        $class = [
            'chargebee' => ChargeBee::class,
        ];

        return $class[$provider] ?? false;
    }
}