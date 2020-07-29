<?php
namespace App\Services;

use App\SaasPlan;
use Illuminate\Support\Facades\Validator;

class SaasPlanService extends BaseService
{
    /**
     * Save SAAS Plan Details
     */
    public function save(array $attributes)
    {
        $validator = Validator::make($attributes, [
            'name' => 'required|unique:saas_plans,name',
            'modules' => 'required',
            'amount'  => 'required',
            'status'  => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->all());
        }

        $saas_plan_details = [
            'name' => $attributes['name'],
            'description' => $attributes['description'],
            'modules' => $attributes['modules'],
            'amount' => $attributes['amount'],
            'status' => $attributes['status'],
        ];

        $saas_plan = SaasPlan::create($saas_plan_details);

        if ($saas_plan) {
            return $this->successResponse('SAAS Plan has been created successfully.');
        }
    }

    /**
     * Update SAAS Plan Details
     */
    public function update(array $attributes)
    {
        $validator = Validator::make($attributes, [
            'name' => 'required|unique:saas_plans,name,'.$attributes['id'],
            'modules' => 'required',
            'amount'  => 'required',
            'status'  => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->all());
        }

        $saas_plan = SaasPlan::findOrFail($attributes['id']);

        $saas_plan->name  = $attributes['name'];
        $saas_plan->description  = $attributes['description'];
        $saas_plan->modules = $attributes['modules'];
        $saas_plan->amount = $attributes['amount'];
        $saas_plan->status = $attributes['status'];

        $saas_plan->save();

        return $this->successResponse('SAAS Plan has been updated successfully.');
    }

    /**
     * Fetch list of SAAS Plans
     */
    public function fetchAll(array $attributes = null)
    {
        $saas_plans = SaasPlan::get();
        return $this->successResponse(null, $saas_plans);
    }

    /**
     * Fetch SAAS Plan Details
     */
    public function fetch($id)
    {
        $sass_plan = SaasPlan::findOrFail($id);
        return $this->successResponse(null, $sass_plan);
    }

    /**
     * Destroy SAAS Plan
     */
    public function destroy($id)
    {
        SaasPlan::find($id)->delete();
        return $this->successResponse('SAAS Plan has been deleted successfully.');
    }
}
 ?>
