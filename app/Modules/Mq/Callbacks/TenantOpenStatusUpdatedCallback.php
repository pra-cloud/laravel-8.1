<?php

namespace App\Modules\Mq\Callbacks;

use App\Models\Tenant;

class TenantOpenStatusUpdatedCallback
{
   public function handle($data)
   {
      if (isset($data["tenant_id"]) && isset($data["is_open"])) {
         echo "Open status: $data[is_open] for tenant: $data[tenant_id]";
         $tenant = Tenant::find($data["tenant_id"]);
         if ($tenant) {
            $tenant->is_open = $data["is_open"];
            $tenant->save();
         }
      }
   }
}
