<?php

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
      'exchange' => 'tenant_exchange',
      'queues' => [
        [
          "name" => "tenant.open",
          "binding_key" => "tenant.updated.open",
          "callback" => \App\Modules\Mq\Callbacks\TenantOpenStatusUpdatedCallback::class,
        ],
        [
          "name" => "tenant.billing",
          "binding_key" => "tenant.billing.subscribed",
          "callback" => \App\Modules\Mq\Callbacks\SubscribeTenantToSaasModule::class,
        ],
      ]
    ]
  ]
];
