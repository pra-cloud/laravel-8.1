<?php

return [
    'default_provider' => env('BILLING_PROVIDER', 'chargebee'),
    'providers' => [
        // Chargbee
        'chargebee' => [
            'site_key' => env('BP_CHARGEBEE_SITE_KEY', 'hyperzod-test'),
            'key' => env('BP_CHARGEBEE_KEY', null),
            'default_currency' => 'USD',
            'default_plans' => [
                [
                    'plan_id' => 'Hyperzod-Ordering-App',
                    'item_price_id' => [
                        'USD' => 'Hyperzod-Ordering-App-USD-Monthly',
                    ],
                    'saas_modules' => ['web_portal', 'app_ordering'],
                ],
                [
                    'plan_id' => 'Hyperzod-Merchant-App',
                    'item_price_id' => [
                        'USD' => 'Hyperzod-Merchant-App-USD-Monthly',
                    ],
                    'saas_modules' => ['web_portal', 'app_merchant'],
                ],
            ]
        ]
    ]
];
