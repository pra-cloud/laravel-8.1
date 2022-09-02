<?php

namespace App\Listeners;

use Hyperzod\HyperzodServiceFunctions\Enums\Mq\TenantMqEnum;
use Hyperzod\HyperzodServiceFunctions\Mq\Abstract\AbstractPublishEventToMQ;

class PublishTenantCreatedToMQ extends AbstractPublishEventToMQ
{
    public function __construct()
    {
        //
    }

    public function setExchange(): string
    {
        return TenantMqEnum::EXCHANGE;
    }

    public function setRoutingKey(): string
    {
        return TenantMqEnum::TENANT_CREATED;
    }
}
