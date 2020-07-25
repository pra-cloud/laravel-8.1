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
        return $response;
    }

    /**
     * Edit SAAS Plan
     */
    public function update(Request $request)
    {
        $attributes = $request->all();
        $response = $this->SAAS_PLAN_SERVICE->update($attributes);
        return $response;
    }

    /**
     * List of SAAS PLans
     */
    public function list()
    {
        $response = $this->SAAS_PLAN_SERVICE->fetchAll();
        return $response;
    }

    /**
     * View SAAS Plan Details
     */
    public function show($id)
    {
        $response = $this->SAAS_PLAN_SERVICE->fetch($id);
        return $response;
    }

    /**
     * Delete SAAS Plan
     */
    public function delete(Request $request)
    {   
        $response = $this->SAAS_PLAN_SERVICE->destroy($request->id);
        return $response;
    }    
}