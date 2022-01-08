<?php

return [
    'default_provider' => env('BILLING_PROVIDER', 'chargebee'),
    'env' => env('BILLING_PROVIDER_ENV', 'test'), // test, live
    'providers' => [
        // Chargbee
        'chargebee' => [
            'site_key' => env('BP_CHARGEBEE_SITE_KEY', 'hyperzod-test'),
            'key' => env('BP_CHARGEBEE_KEY', null),
            'default_currency' => 'USD',
            // default item price ids according to currency
            'default_item_price_ids' => [
                'USD' => ['Hyperzod-Admin-USD-Monthly', 'Ordering-Website-USD-Monthly', 'Ordering-Mobile-App-USD-Monthly',         'Merchant-Mobile-App-USD-Monthly']
            ],
            // saas modules mapping with item price id acc. to env
            'saas_modules' => [
                "test" => [
                    'admin_panel' => ['Hyperzod-Admin-USD-Monthly', 'Hyperzod-Admin-USD-Yearly'],
                    'web_ordering' => ['Ordering-Website-USD-Monthly', 'Ordering-Website-USD-Yearly'],
                    'app_ordering' => ['Ordering-Mobile-App-USD-Monthly', 'Ordering-Mobile-App-USD-Yearly'],
                    'app_merchant' => ['Merchant-Mobile-App-USD-Monthly', 'Merchant-Mobile-App-USD-Yearly'],
                ]
            ],


        ]
    ]
];
