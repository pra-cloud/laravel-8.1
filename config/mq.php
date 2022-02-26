<?php

use Hyperzod\HyperzodServiceFunctions\Enums\Mq\TenantMqEnum;

return [
  'connection' => [
    'host' => env('AMQP_HOST', 'localhost'),
    'port' => env('AMQP_PORT', 5672),
    'user' => env('AMQP_USER', 'guest'),
    'password' => env('AMQP_PASSWORD', 'guest'),
    'vhost' => env('AMQP_VHOST', '/')
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
    ]
  ]
];
