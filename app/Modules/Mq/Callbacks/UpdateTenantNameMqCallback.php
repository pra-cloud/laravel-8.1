<?php

namespace App\Modules\Mq\Callbacks;

use App\Models\Tenant;
use Hyperzod\HyperzodServiceFunctions\Enums\TerminologyEnum;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;

class UpdateTenantNameMqCallback
{
    public function handle($data)
    {
        $tenant_id = $data[TerminologyEnum::TENANT_ID];
        $site_title = $data['setting_value']['site_title'];

        $tenant = Tenant::findOrFail($tenant_id);
        $tenant->name = $site_title;
        $tenant->save();

        echo "Tenant Name Updated!";
    }
}
