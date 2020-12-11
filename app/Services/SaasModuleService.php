<?php


namespace App\Services;


use App\SaasModule;

class SaasModuleService extends BaseService
{
    /**
     * Fetch list of SAAS Modules
     */
    public function fetchAll()
    {
        $saas_modules = SaasModule::all();
        return $this->successResponse("", $saas_modules);
    }
}
