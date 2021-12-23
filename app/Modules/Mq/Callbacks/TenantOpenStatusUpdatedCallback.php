<?php

namespace App\Modules\Mq\Callbacks;

use App\Tenant;

class TenantOpenStatusUpdatedCallback
{
   public function handle($data)
   {
      if (isset($data["tenant_id"]) && isset($data["is_open"])) {
         echo "Open status: $data[is_open] for tenant: $data[tenant_id]";
         $tenant = Tenant::find($data["tenant_id"]);
         $tenant->is_open = $data["is_open"];
         if (isset($data["comment"])) {
            $tenant->comment = $data["comment"];
         }
         $tenant->save();
      }
   }
}
