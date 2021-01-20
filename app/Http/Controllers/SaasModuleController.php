<?php

namespace App\Http\Controllers;

use App\Repositories\SaasModuleRepository;
use Illuminate\Http\Request;

class SaasModuleController extends Controller
{
    private $SAAS_MODULE_REPOSITORY;

    public function __construct(SaasModuleRepository $saasModuleRepository)
    {
        $this->SAAS_MODULE_REPOSITORY = $saasModuleRepository;
    }

    public function list()
    {
        return $this->SAAS_MODULE_REPOSITORY->fetchAll();
    }
}
