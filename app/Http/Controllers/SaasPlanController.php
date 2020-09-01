<?php

namespace App\Http\Controllers;

use App\SaasPlan;
use App\Services\SaasPlanService;
use Illuminate\Http\Request;

class SaasPlanController extends Controller
{
    private $SAAS_PLAN_SERVICE;

    public function __construct(SaasPlanService $saas_plan_service)
    {
        $this->SAAS_PLAN_SERVICE = $saas_plan_service;
    }

    /**
     * Create SAAS Plan
     */
    public function create(Request $request)
    {
        $attributes = $request->all();
        $response = $this->SAAS_PLAN_SERVICE->save($attributes);

        return $this->processServiceResponse($response);
    }

    /**
     * Edit SAAS Plan
     */
    public function update(Request $request)
    {
        $attributes = $request->all();
        $response = $this->SAAS_PLAN_SERVICE->update($attributes);

        return $this->processServiceResponse($response);
    }

    /**
     * List of SAAS PLans
     */
    public function list()
    {
        $response = $this->SAAS_PLAN_SERVICE->fetchAll();
        return $this->processServiceResponse($response);
    }

    /**
     * View SAAS Plan Details
     */
    public function view(Request $request)
    {
        $response = $this->SAAS_PLAN_SERVICE->fetch($request->all());
        return $this->processServiceResponse($response);
    }

    /**
     * Delete SAAS Plan
     */
    public function delete(Request $request)
    {
        $response = $this->SAAS_PLAN_SERVICE->destroy($request->id);
        return $this->processServiceResponse($response);
    }

    /**
     * List plan billing cycle
     */
    public function listPlanBillingCycle(Request $request)
    {
        $response = $this->SAAS_PLAN_SERVICE->listPlanBillingCycle();
        return $response;
    }

}
