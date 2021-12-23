<?php

return [
     'queue_config' => [
        [
          'exchange' => 'tenant_exchange',
          'queues' => [
            'tenant.updated.is_open' => \App\Modules\Mq\Callbacks\TenantOpenStatusUpdatedCallback::class,
          ]
      ],
   ]
];