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
        try {
            $response = $this->SAAS_PLAN_REPOSITORY->save($request->all());
            return $this->successResponse(null, $response);
        } catch (\Exception $e) {
            $errors = $this->SAAS_PLAN_REPOSITORY->getErrors();
            return $this->errorResponse($e->getMessage(), $errors);
        }
    }

    /**
     * Edit SAAS Plan
     */
    public function update(Request $request)
    {
        try {
            $response = $this->SAAS_PLAN_REPOSITORY->update($request->all());
            return $this->successResponse(null, $response);
        } catch (\Exception $e) {
            $errors = $this->SAAS_PLAN_REPOSITORY->getErrors();
            return $this->errorResponse($e->getMessage(), $errors);
        }
    }

    /**
     * List of SAAS Plans
     */
    public function list()
    {
        try {
            $response = $this->SAAS_PLAN_REPOSITORY->fetchAll();
            return $this->successResponse(null, $response);
        } catch (\Exception $e) {
            $errors = $this->SAAS_PLAN_REPOSITORY->getErrors();
            return $this->errorResponse($e->getMessage(), $errors);
        }
    }

    /**
     * View SAAS Plan Details
     */
    public function view(Request $request)
    {
        try {
            $response = $this->SAAS_PLAN_REPOSITORY->fetch($request->all());
            return $this->successResponse(null, $response);
        } catch (\Exception $e) {
            $errors = $this->SAAS_PLAN_REPOSITORY->getErrors();
            return $this->errorResponse($e->getMessage(), $errors);
        }
    }

    /**
     * Delete SAAS Plan
     */
    public function delete(Request $request)
    {
        try {
            $response = $this->SAAS_PLAN_REPOSITORY->destroy($request->id);
            return $this->successResponse(null, $response);
        } catch (\Exception $e) {
            $errors = $this->SAAS_PLAN_REPOSITORY->getErrors();
            return $this->errorResponse($e->getMessage(), $errors);
        }
    }

    /**
     * List plan billing cycle
     */
    public function listPlanBillingCycle(Request $request)
    {
        try {
            $response = $this->SAAS_PLAN_REPOSITORY->listPlanBillingCycle();
            return $this->successResponse(null, $response);
        } catch (\Exception $e) {
            $errors = $this->SAAS_PLAN_REPOSITORY->getErrors();
            return $this->errorResponse($e->getMessage(), $errors);
        }
    }
}
