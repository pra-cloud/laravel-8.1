<?php

namespace Database\Seeders;

use App\SaasModule;
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
            ['module_name' => 'web_portal', 'active' => 1],
            ['module_name' => 'app_ordering', 'active' => 1],
            ['module_name' => 'app_merchant', 'active' => 1],
        ];
        SaasModule::truncate();
        SaasModule::insert($saas_modules);
    }
}
