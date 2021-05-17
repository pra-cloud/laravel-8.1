<?php
namespace App\Repositories;

use App\Tenant;
use Carbon\Carbon;
use App\Rules\Domain;
use App\TenantModule;
use App\TenantBillingDetail;
use Illuminate\Support\Facades\DB;
use App\Repositories\SaasPlanRepository;
use Illuminate\Support\Facades\Validator;

class TenantRepository extends BaseRepository
{
    private $SAAS_PLAN_REPOSITORY;

    public function __construct(SaasPlanRepository $saasPlanRepository)
    {
        $this->SAAS_PLAN_REPOSITORY = $saasPlanRepository;
    }
    /**
     * Save Tenant Details and
     * Tenant Billing Details related to Tenant
     */
    public function save(array $attributes)
    {
        $validator = Validator::make($attributes, [
            'domain'                => ['required','unique:tenants', new Domain],
            'admin_domain'          => ['required','unique:tenants', new Domain],
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
            $this->errors = $validator->errors()->all();
            throw new \Exception("Validation error");
        }

        // Calculate plan_expiry_date
        $attributes['plan_expiry_date'] = $this->calculatePlanExpiryDate($attributes['plan_billing_cycle'], $attributes['plan_start_date']);

        $tenant_details = [
            'domain'                => $attributes['domain'],
            'admin_domain'          => $attributes['admin_domain'],
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

        $tenant = null;
        
        \DB::transaction(function () use ($tenant_details, $attributes, &$tenant) {
            $tenant = Tenant::create($tenant_details);

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

            $saas_plan = $this->SAAS_PLAN_REPOSITORY->fetch([ 'id' => $tenant->saas_plan_id ]);
            
            foreach ($saas_plan['modules'] as $module) {
                $tenant_module = new TenantModule();
                $tenant_module->tenant_id = $tenant->id;
                $tenant_module->saas_module_id = $module['module_id'];
                $tenant_module->module_limit = $module['module_limit'];
                $tenant_module->save();
            }
        });

        return $tenant;
    }

    /**
     * Update Tenant Details and
     * Tenant Billing Details related to Tenant
     */
    public function update(array $attributes)
    {
        $validator = Validator::make($attributes, [
            'domain'                => ['required', new Domain, 'unique:tenants,id,'.$attributes['tenant_id']],
            'admin_domain'          => ['required', new Domain, 'unique:tenants,id,'.$attributes['tenant_id']],
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
            $this->errors = $validator->errors()->all();
            throw new \Exception("Validation error");
        }

        $tenant = Tenant::findOrFail($attributes['tenant_id']);

        $tenant->domain                 = $attributes['domain'];
        $tenant->admin_domain           = $attributes['admin_domain'];
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

        if ($tenant) {
            return $tenant;
        }
        throw new \Exception("Error updating tenant");
    }

    /**
     * Fetch list of Tenants
     * with Tenant Billing Details
     */
    public function fetchAll(array $attributes = [])
    {
        $tenants = Tenant::where($attributes)->get();
        return $tenants;
    }

    /**
     * Fetch Tenant Details
     * with Tenant Billing Details
     */
    public function fetch(array $attributes)
    {
        $validator = Validator::make($attributes, [
            'id' => ['nullable'],
        ]);

        if ($validator->fails()) {
            $this->errors = $validator->errors()->all();
            throw new \Exception("Validation error");
        }

        $attributes = $validator->validated();


        $tenant = Tenant::where($attributes)->firstOrFail();
        if ($tenant) {
            return $tenant;
        }
    }

    public function fetchTenantStatus(array $attributes)
    {
        $tenant = Tenant::where($attributes)->firstorFail();
        return $tenant->status;
    }

    public function getTenantIdByAdminDomain($attributes)
    {
        $validator = Validator::make($attributes, [
            'admin_domain'          => ['required', new Domain],
        ]);

        if ($validator->fails()) {
            $this->errors = $validator->errors()->all();
            throw new \Exception("Validation error");
        }

        $tenant = Tenant::setEagerLoads([])->select('id')->where('admin_domain', $attributes['admin_domain'])->first();

        if (!$tenant) {
            throw new \Exception("Tenant not found by this admin domain");
        }

        return ['tenant_id' => $tenant->id];
    }

    public function getTenantIdByDomain($attributes)
    {
        $validator = Validator::make($attributes, [
            'domain'          => ['required', new Domain],
        ]);

        if ($validator->fails()) {
            $this->errors = $validator->errors()->all();
            throw new \Exception("Validation error");
        }

        $tenant = Tenant::setEagerLoads([])->select('id')->where('domain', $attributes['domain'])->first();

        if (!$tenant) {
            throw new \Exception("Tenant not found by this domain");
        }

        return ['tenant_id' => $tenant->id];
    }

    public function isValidDomain($domain)
    {
        return preg_match('/^[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.[a-zA-Z0-9][a-zA-Z0-9-]{1,61}[a-zA-Z0-9]\.[a-zA-Z]{2,}$/', $domain);
    }

    /**
     * Destroy Tenant
     * with Tenant Billing Details
     */
    public function destroy($id)
    {
        $tenant = Tenant::find($id);

        if (!$tenant) {
            throw new \Exception("Cannot find tenant.");
        }

        if ($tenant->delete()) {
            return 'Tenant has been deleted successfully.';
        }
        throw new \Exception("Error in deleting tenant.");
    }

    public function calculatePlanExpiryDate($plan_billing_cycle, $date)
    {
        $plan_expiry_date = Carbon::parse('1970-01-01');

        if ($plan_billing_cycle == 'monthly') {
            $plan_expiry_date = Carbon::parse($date)->addMonth();
        }

        if ($plan_billing_cycle == 'quaterly') {
            $plan_expiry_date = Carbon::parse($date)->addMonths(4);
        }

        if ($plan_billing_cycle == 'yearly') {
            $plan_expiry_date = Carbon::parse($date)->addYear();
        }

        return $plan_expiry_date->toDateString();
    }

    public function updateImages(array $attributes)
    {
        $validator = Validator::make($attributes, [
            'tenant_id' => 'required',
            'logo_url' => 'nullable|url',
            'favicon_url' => 'nullable|url',
        ]);

        if ($validator->fails()) {
            $this->errors = $validator->errors()->all();
            throw new \Exception("Validation error");
        }

        $tenant = Tenant::findOrFail($attributes['tenant_id']);

        if (isset($attributes['logo_url'])) {
            $tenant->logo_url = $attributes['logo_url'];
        }

        if (isset($attributes['favicon_url'])) {
            $tenant->favicon_url = $attributes['favicon_url'];
        }

        $tenant->save();

        return $tenant;
    }
}
