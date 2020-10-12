<?php
namespace App\Services;

use App\Tenant;
use App\TenantBillingDetail;
use App\TenantModule;
use App\Services\SaasPlanService;
use Illuminate\Support\Facades\Validator;
use App\Rules\Domain;
use Carbon\Carbon;

class TenantService extends BaseService
{
    private $SAAS_PLAN_SERVICE;

    function __construct(SaasPlanService $saasPlanService)
    {
        $this->SAAS_PLAN_SERVICE = $saasPlanService;
    }
    /**
     * Save Tenant Details and
     * Tenant Billing Details related to Tenant
     */
    public function save(array $attributes)
    {
        $validator = Validator::make($attributes, [
            'domain'                => ['required','unique:tenants', new Domain],
            'name'                  => 'required',
            'email'                 => 'required|email',
            'mobile'                => 'required',
            'city'                  => 'required',
            'country'               => 'required',
            'status'                => 'required',
            'saas_plan_id'          => 'required',
            'plan_start_date'       => 'required|date',
            'plan_billing_cycle'    => 'required',
            'tenant_billing_detail.billing_name'    => 'required',
            'tenant_billing_detail.billing_email'   => 'required|email',
            'tenant_billing_detail.billing_phone'   => 'required',
            'tenant_billing_detail.billing_address' => 'required',
            'tenant_billing_detail.tax_type_id'     => 'required',
            'tenant_billing_detail.tax_id'          => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->all());
        }

        // Calculate plan_expiry_date
        $attributes['plan_expiry_date'] = $this->calculatePlanExpiryDate($attributes['plan_billing_cycle'], $attributes['plan_start_date']);

        $tenant_details = [
            'domain'                => $attributes['domain'],
            'name'                  => $attributes['name'],
            'email'                 => $attributes['email'],
            'mobile'                => $attributes['mobile'],
            'city'                  => $attributes['city'],
            'country'               => $attributes['country'],
            'status'                => $attributes['status'],
            'saas_plan_id'          => $attributes['saas_plan_id'],
            'plan_expiry_date'      => $attributes['plan_expiry_date'],
            'plan_billing_cycle'      => $attributes['plan_billing_cycle'],
        ];

        $tenant = Tenant::create($tenant_details);

        if ($tenant) {
            $tenant_billing_details = [
                'tenant_id'             => $tenant->id,
                'billing_name'          => $attributes['tenant_billing_detail']['billing_name'],
                'billing_email'         => $attributes['tenant_billing_detail']['billing_email'],
                'billing_phone'         => $attributes['tenant_billing_detail']['billing_phone'],
                'billing_address'       => $attributes['tenant_billing_detail']['billing_address'],
                'tax_type_id'           => $attributes['tenant_billing_detail']['tax_type_id'],
                'tax_id'                => $attributes['tenant_billing_detail']['tax_id'],
            ];

            TenantBillingDetail::create($tenant_billing_details);

            $saas_plan = $this->SAAS_PLAN_SERVICE->fetch([ 'id' => $tenant->saas_plan_id ]);

            foreach ($saas_plan['data']['modules'] as $module) {
                $tenant_module = new TenantModule();
                $tenant_module->tenant_id = $tenant->id;
                $tenant_module->saas_module_id = $module['module_id'];
                $tenant_module->module_limit = $module['module_limit'];
                $tenant_module->save();
            }

            return $this->successResponse('Tenant has been created successfully.', [ 'tenant' => $tenant ]);
        }

        return $this->errorResponse('Error creating tenant.');
    }

    /**
     * Update Tenant Details and
     * Tenant Billing Details related to Tenant
     */
    public function update(array $attributes)
    {
        $validator = Validator::make($attributes, [
            'domain'                => ['required', new Domain, 'unique:tenants,id,'.$attributes['tenant_id']],
            'name'                  => 'required',
            'email'                 => 'required|email',
            'mobile'                => 'required',
            'city'                  => 'required',
            'country'               => 'required',
            'status'                => 'required',
            'saas_plan_id'          => 'required',
            'plan_expiry_date'      => 'required|date',
            'plan_billing_cycle'    => 'required',
            'tenant_billing_detail.billing_name'    => 'required',
            'tenant_billing_detail.billing_email'   => 'required|email',
            'tenant_billing_detail.billing_phone'   => 'required',
            'tenant_billing_detail.billing_address' => 'required',
            'tenant_billing_detail.tax_type_id'     => 'required',
            'tenant_billing_detail.tax_id'          => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->all());
        }

        $tenant = Tenant::findOrFail($attributes['tenant_id']);

        $tenant->domain                 = $attributes['domain'];
        $tenant->name                   = $attributes['name'];
        $tenant->email                  = $attributes['email'];
        $tenant->mobile                 = $attributes['mobile'];
        $tenant->city                   = $attributes['city'];
        $tenant->status                 = $attributes['status'];
        $tenant->saas_plan_id           = $attributes['saas_plan_id'];
        $tenant->plan_expiry_date       = $attributes['plan_expiry_date'];
        $tenant->plan_billing_cycle     = $attributes['plan_billing_cycle'];
        $tenant->save();

        $tenant->tenantBillingDetail->billing_name        = $attributes['tenant_billing_detail']['billing_name'];
        $tenant->tenantBillingDetail->billing_email       = $attributes['tenant_billing_detail']['billing_email'];
        $tenant->tenantBillingDetail->billing_phone       = $attributes['tenant_billing_detail']['billing_phone'];
        $tenant->tenantBillingDetail->billing_address     = $attributes['tenant_billing_detail']['billing_address'];
        $tenant->tenantBillingDetail->tax_type_id         = $attributes['tenant_billing_detail']['tax_type_id'];
        $tenant->tenantBillingDetail->tax_id              = $attributes['tenant_billing_detail']['tax_id'];
        $tenant->tenantBillingDetail->save();

        if ($tenant)
            return $this->successResponse('Tenant has been updated successfully', [ 'tenant' => $tenant ]);
        else
            return $this->errorResponse('Error updating SAAS Plan.');
    }

    /**
     * Fetch list of Tenants
     * with Tenant Billing Details
     */
    public function fetchAll(array $attributes = [])
    {
        $tenants = Tenant::where($attributes)->get();
        return $this->successResponse(null, $tenants);
    }

    /**
     * Fetch Tenant Details
     * with Tenant Billing Details
     */
    public function fetch(array $attributes)
    {
        try {
            $tenant = Tenant::where($attributes)->firstOrFail();
            return $this->successResponse(null, $tenant);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Destroy Tenant
     * with Tenant Billing Details
     */
    public function destroy($id)
    {
        $tenant = Tenant::find($id)->delete();

        if ($tenant)
            return $this->successResponse('Tenant has been deleted successfully.');
        else
            return $this->errorResponse('Cannot delete tenant.');
    }

    public function calculatePlanExpiryDate($plan_billing_cycle, $date)
    {
        $plan_expiry_date = Carbon::parse('1970-01-01');

        if ($plan_billing_cycle == 'monthly')
            $plan_expiry_date = Carbon::parse($date)->addMonth();

        if ($plan_billing_cycle == 'quaterly')
            $plan_expiry_date = Carbon::parse($date)->addMonths(4);

        if ($plan_billing_cycle == 'yearly')
            $plan_expiry_date = Carbon::parse($date)->addYear();

        return $plan_expiry_date->toDateString();
    }
}
 ?>
