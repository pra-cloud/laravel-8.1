<?php

use Hyperzod\HyperzodServiceFunctions\Enums\Mq\BillingMqEnum;
use Hyperzod\HyperzodServiceFunctions\Enums\Mq\SettingMqEnum;
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
          "name" => "tenant.billing.subscribed",
          "binding_key" => BillingMqEnum::BILLING_TENANT_SUBSCRIBED,
          "callback" => \App\Modules\Mq\Callbacks\SubscribeTenantToSaasModule::class,
        ],
        [
          "name" => "tenant.billing.unsubscribed",
          "binding_key" => BillingMqEnum::BILLING_TENANT_UNSUBSCRIBED,
          "callback" => \App\Modules\Mq\Callbacks\UnsubscribeTenantToSaasModule::class,
        ],
      ]
    ],
    [
      'exchange' => SettingMqEnum::EXCHANGE,
      'queues' => [
        [
          "name" => "tenant.update.name",
          "binding_key" => SettingMqEnum::SETTING_UPDATED_SITE_SETTINGS,
          "callback" => \App\Modules\Mq\Callbacks\UpdateTenantNameMqCallback::class,
        ]
      ]
    ]
  ]
];
