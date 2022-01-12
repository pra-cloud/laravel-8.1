<?php

namespace App\Listeners;

use Hyperzod\HyperzodServiceFunctions\Mq\MQHelper;
use Illuminate\Contracts\Queue\ShouldQueue;

class PublishEventsToMq implements ShouldQueue
{
   public $tries = 5;
   public $backoff = 5;
   public $EVENT_QUEUE_MAPPINGS;
   public $EXCHANGE = "tenant_exchange";

   public function __construct()
   {
   }

   public function handle($event)
   {
      $event_class = get_class($event);
      $event_type = explode("\\", $event_class);
      $event_type = end($event_type);
      if (isset($event->data) && !is_null($event->data) && !empty($event->data)) {
         $data = $event->data;
         $this->getEventQueueMappings();
         if (isset($this->EVENT_QUEUE_MAPPINGS[$event_type])) {
            try {
               MQHelper::publishToMQ($this->EXCHANGE, $this->EVENT_QUEUE_MAPPINGS[$event_type], $data);
            } catch (\Exception $e) {
               echo $e->getMessage();
            }
         }
      }
   }

   public function getEventQueueMappings()
   {
      $this->EVENT_QUEUE_MAPPINGS = [
         "GenerateApiKeyForTenantEvent" => "tenant.apikey.generate",
         "DeleteTenantApiKeyEvent" => "tenant.apikey.delete",
      ];
   }
}
