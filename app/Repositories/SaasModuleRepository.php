<?php
namespace App\Repositories;

use App\SaasModule;

class SaasModuleRepository extends BaseRepository
{
    /**
     * Fetch list of SAAS Modules
     */
    public function fetchAll()
    {
        $saas_modules = SaasModule::all();
        return $saas_modules;
    }
}
