<?php

namespace App\Modules\Mq\Callbacks;

use App\Models\SaasModule;
use App\Models\TenantModule;
use Hyperzod\HyperzodServiceFunctions\Enums\TerminologyEnum;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;

class UnsubscribeTenantToSaasModule
{
    public function handle($data)
    {
        \Log::debug("UnsubscribeTenantToSaasModule");
        $saas_modules = Arr::flatten($data['saas_modules']);

        # Get SaaS modules
        $saas_modules = SaasModule::whereIn('module_name', $saas_modules)->active()->get();

        # Delete unsunsribed tenant modules
        TenantModule::tenant($data['tenant_id'])->whereIn('saas_module_id', $saas_modules->pluck('id'))->delete();
    }
}
