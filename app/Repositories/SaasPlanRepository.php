<?php
namespace App\Repositories;

use App\SaasPlan;
use Illuminate\Support\Facades\Validator;

class SaasPlanRepository extends BaseRepository
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
            $this->errors = $validator->errors()->all();
            throw new \Exception("Validation error");
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
            return  $saas_plan;
        }
        throw new \Exception("Error creating SAAS Plan");
    }

    /**
     * Update SAAS Plan Details
     */
    public function update(array $attributes)
    {
        $validator = Validator::make($attributes, [
            'name' => 'required',
            'id' => 'required',
            'modules' => 'required',
            'amount'  => 'required',
            'status'  => 'required',
        ]);

        if ($validator->fails()) {
            $this->errors = $validator->errors()->all();
            throw new \Exception("Validation error");
        }

        $saas_plan = SaasPlan::findOrFail($attributes['id']);

        $saas_plan->name  = $attributes['name'];
        $saas_plan->description  = $attributes['description'];
        $saas_plan->modules = $attributes['modules'];
        $saas_plan->amount = $attributes['amount'];
        $saas_plan->status = $attributes['status'];

        if ($saas_plan->save()) {
            return $saas_plan;
        }
        throw new \Exception("Error updating SAAS Plan.");
    }

    /**
     * Fetch list of SAAS Plans
     */
    public function fetchAll(array $attributes = null)
    {
        $saas_plans = SaasPlan::where($attributes)->get();
        return $saas_plans;
    }

    /**
     * Fetch SAAS Plan Details
     */
    public function fetch(array $attributes)
    {
        $saas_plan = SaasPlan::where($attributes)->firstOrFail();
        return $saas_plan;
    }

    /**
     * Destroy SAAS Plan
     */
    public function destroy($id)
    {
        SaasPlan::findOrFail($id)->delete();
        return 'SAAS Plan has been deleted successfully.';
    }

    public function listPlanBillingCycle()
    {
        return [
            'monthly' => 'Monthly',
            'quaterly' => 'Quaterly',
            'yearly' => 'Yearly',
        ];
    }
}
