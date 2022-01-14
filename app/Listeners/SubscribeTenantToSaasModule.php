<?php

namespace App\Listeners;

use App\Events\TenantSubscribed;
use App\SaasModule;
use App\TenantModule;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;

class SubscribeTenantToSaasModule
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\TenantSubscribed  $event
     * @return void
     */
    public function handle(TenantSubscribed $event)
    {
        $saas_modules = Arr::flatten($event->saas_modules);

        # Get SaaS modules
        $saas_modules = SaasModule::whereIn('module_name', $saas_modules)->active()->get();

        # Delete all existing tenant modules
        TenantModule::where('tenant_id', $event->tenant_id)->delete();
        # Add new saas modules to tenant modules
        foreach ($saas_modules as $saas_module) {
            $rows[] = [
                'tenant_id' => $event->tenant_id,
                'saas_module_id' => $saas_module->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        TenantModule::insert($rows);
    }
}
