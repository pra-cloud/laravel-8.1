<?php

namespace App\Http\Controllers;

use App\SaasPlan;
use App\Repositories\SaasPlanRepository;
use Illuminate\Http\Request;

class SaasPlanController extends Controller
{
    private $SAAS_PLAN_REPOSITORY;

    public function __construct(SaasPlanRepository $saasPlanRepository)
    {
        $this->SAAS_PLAN_REPOSITORY = $saasPlanRepository;
    }

    /**
     * Create SAAS Plan
     */
    public function create(Request $request)
    {
        //$this->hasPermission('saas_plan_create');

        $attributes = $request->all();
        $response = $this->SAAS_PLAN_REPOSITORY->save($attributes);

        return $this->processServiceResponse($response);
    }

    /**
     * Edit SAAS Plan
     */
    public function update(Request $request)
    {
        $attributes = $request->all();
        $response = $this->SAAS_PLAN_REPOSITORY->update($attributes);

        return $this->processServiceResponse($response);
    }

    /**
     * List of SAAS PLans
     */
    public function list()
    {
        $response = $this->SAAS_PLAN_REPOSITORY->fetchAll();
        return $this->processServiceResponse($response);
    }

    /**
     * View SAAS Plan Details
     */
    public function view(Request $request)
    {
        $response = $this->SAAS_PLAN_REPOSITORY->fetch($request->all());
        return $this->processServiceResponse($response);
    }

    /**
     * Delete SAAS Plan
     */
    public function delete(Request $request)
    {
        $response = $this->SAAS_PLAN_REPOSITORY->destroy($request->id);
        return $this->processServiceResponse($response);
    }

    /**
     * List plan billing cycle
     */
    public function listPlanBillingCycle(Request $request)
    {
        $response = $this->SAAS_PLAN_REPOSITORY->listPlanBillingCycle();
        return $this->successResponse(null, $response);
    }

}
