<?php

use Hyperzod\HyperzodServiceFunctions\Enums\Mq\BillingMqEnum;
use Hyperzod\HyperzodServiceFunctions\Enums\Mq\TenantMqEnum;

return [
  'connection' => [
    'host' => env('AMQP_HOST', 'localhost'),
    'port' => env('AMQP_PORT', 5672),
    'user' => env('AMQP_USER', 'guest'),
    'password' => env('AMQP_PASSWORD', 'guest'),
    'vhost' => env('AMQP_VHOST', '/'),
    'ssl' => env('AMQP_SSL', false),
  ],
  'queue_config' => [
    [
      'exchange' => TenantMqEnum::EXCHANGE,
      'queues' => [
        [
          "name" => "tenant.isopen",
          "binding_key" => TenantMqEnum::TENANT_UPDATED_ISOPEN,
          "callback" => \App\Modules\Mq\Callbacks\TenantOpenStatusUpdatedCallback::class,
        ],
      ]
    ],
    [
      'exchange' => BillingMqEnum::EXCHANGE,
      'queues' => [
        [
          "name" => "tenant.billing",
          "binding_key" => BillingMqEnum::TENANT_BILLING_SUBSCRIPTION_UPDATED,
          "callback" => \App\Modules\Mq\Callbacks\SubscribeTenantToSaasModule::class,
        ],
      ]
    ]
  ]
];
