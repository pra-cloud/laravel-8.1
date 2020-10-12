<?php

namespace App\Http\Controllers;

use App\Services\SaasModuleService;
use Illuminate\Http\Request;

class SaasModuleController extends Controller
{
    private $SAAS_MODULE_SERVICE;

    public function __construct(SaasModuleService $saasModuleService)
    {
        $this->SAAS_MODULE_SERVICE = $saasModuleService;
    }

    public function list()
    {
        return $this->SAAS_MODULE_SERVICE->fetchAll();
    }
}
