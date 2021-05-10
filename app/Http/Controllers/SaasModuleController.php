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
        try {
            $response = $this->SAAS_MODULE_REPOSITORY->fetchAll();
            return $this->successResponse(null, $response);
        } catch (\Exception $e) {
            $errors = $this->SAAS_MODULE_REPOSITORY->getErrors();
            return $this->errorResponse($e->getMessage(), $errors);
        }
    }
}
