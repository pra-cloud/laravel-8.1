<?php

namespace App\Modules\Mq\Callbacks;

use App\Models\SaasModule;
use App\Models\TenantModule;
use Hyperzod\HyperzodServiceFunctions\Enums\TerminologyEnum;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;

class SubscribeTenantToSaasModule
{
    public function handle($data)
    {
        \Log::debug("SubscribeTenantToSaasModule");
        $saas_modules = Arr::flatten($data['saas_modules']);

        # Get SaaS modules
        $saas_modules = SaasModule::whereIn('module_name', $saas_modules)->active()->get();

        # Delete all existing tenant modules
        TenantModule::tenant($data['tenant_id'])->delete();
        # Add new saas modules to tenant modules
        $rows = [];
        foreach ($saas_modules as $saas_module) {
            $rows[] = [
                TerminologyEnum::TENANT_ID => $data[TerminologyEnum::TENANT_ID],
                'saas_module_id' => $saas_module->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        TenantModule::insert($rows);
    }
}
