<?php

namespace App\Modules\Mq\Callbacks;

use App\Models\Tenant;
use Hyperzod\HyperzodServiceFunctions\Enums\TerminologyEnum;

class TenantOpenStatusUpdatedCallback
{
   public function handle($data)
   {
      if (isset($data[TerminologyEnum::TENANT_ID]) && isset($data["is_open"])) {
         echo "Open status: $data[is_open] for tenant: $data[tenant_id]\n";
         $tenant = Tenant::find(intval($data[TerminologyEnum::TENANT_ID]));
         if ($tenant) {
            $tenant->is_open = $data["is_open"];
            $tenant->save();
         }
      }
   }
}
