<?php

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
            ['name' => 'Store Management'],
            ['name' => 'Order Management'],
            ['name' => 'Driver Management']
        ];

        SaasModule::insert($saas_modules);
    }
}
