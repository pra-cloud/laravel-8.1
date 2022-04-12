<?php

namespace Database\Seeders;

use App\Models\SaasModule;
use Hyperzod\HyperzodServiceFunctions\Enums\SaasModuleEnum;
use Illuminate\Database\Seeder;

class SaasModulesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $saas_modules = [
            ['module_name' => SaasModuleEnum::ADMIN, 'active' => 1],
            ['module_name' => SaasModuleEnum::WEB_ORDERING, 'active' => 1],
            ['module_name' => SaasModuleEnum::APP_ORDERING, 'active' => 1],
            ['module_name' => SaasModuleEnum::APP_MERCHANT, 'active' => 1],
            ['module_name' => SaasModuleEnum::APP_DRIVER, 'active' => 1],
        ];
        SaasModule::truncate();
        SaasModule::insert($saas_modules);
    }
}
