<?php

return [
    'default_provider' => env('BILLING_PROVIDER', null),

    'providers' => [

        "chargebee" => [
            "site_key" => env('BP_CHARGEBEE_SITE_KEY', null),
            "key" => env('BP_CHARGEBEE_KEY', null),
            "default_plan_id" => 'Hyperzod-Enterprise'
        ]

    ]
];
