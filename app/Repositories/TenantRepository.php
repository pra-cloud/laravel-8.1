<?php
namespace App\Repositories;

use App\Tenant;
use Carbon\Carbon;
use App\Rules\Domain;
use App\TenantModule;
use App\TenantBillingDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\SaasPlanRepository;
use Illuminate\Support\Facades\Validator;
use Hyperzod\HyperzodServiceFunctions\Traits\HelpersServiceTrait;

class TenantRepository extends BaseRepository
{
    use HelpersServiceTrait;
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
        Validator::extend('country_exists', function ($attribute, $value, $parameters) {
            $country_codes = $this->fetchCountries();
            $country_codes = collect($country_codes)->pluck('code')->toArray();

            $country_present = in_array($value, $country_codes);
            if (!$country_present) {
                return false;
            }

            return true;
        }, "Invalid country");

        $validator = Validator::make($attributes, [
            'domain'                => ['nullable','unique:tenants', new Domain],
            'admin_domain'          => ['nullable','unique:tenants', new Domain],
            'name'                  => 'required',
            'email'                 => ['required', 'email', 'unique:tenants'],
            'mobile'                => 'required',
            'city'                  => 'required',
<<<<<<< HEAD
            'country'               => 'required|country_exists',
            'status'                => 'required',
            'saas_plan_id'          => 'required',
            'plan_start_date'       => 'required|date',
            'plan_billing_cycle'    => 'required',
=======
            'country'               => 'required',
            'status'                => 'required|boolean',
            'business_type'         => 'required|string|in:food_delivery,   grocery_delivery,bakery_delivery,pet_food_delivery,bouquet_delivery,stationary_delivery,accessories_delivery,clothing_delivery,beverages_delivery',
            'saas_plan_id'          => 'nullable',
            'plan_start_date'       => 'nullable|date',
            'plan_billing_cycle'    => 'nullable',
>>>>>>> fb1fc3f8f38de8dd25d1705e2b2dba1e744a9f0e
            'tenant_billing_detail.billing_name'    => 'required',
            'tenant_billing_detail.billing_email'   => 'required|email',
            'tenant_billing_detail.billing_phone'   => 'required',
            'tenant_billing_detail.billing_address' => 'required',
        ]);

        if ($validator->fails()) {
            $this->errors = $validator->errors()->all();
            throw new \Exception("Validation error");
        }

        // Calculate plan_expiry_date
        if (isset($attributes['plan_billing_cycle']) && isset($attributes['plan_start_date'])) {
            $attributes['plan_expiry_date'] = $this->calculatePlanExpiryDate($attributes['plan_billing_cycle'], $attributes['plan_start_date']);
        }

        $tenant_details = [
            'domain'                => $attributes['domain'] ?? null,
            'admin_domain'          => $attributes['admin_domain'] ?? null,
            'name'                  => $attributes['name'],
            'email'                 => $attributes['email'],
            'mobile'                => $attributes['mobile'],
            'city'                  => $attributes['city'],
            'country'               => $attributes['country'],
            'status'                => $attributes['status'],
            'business_type'         => $attributes['business_type'],
            'saas_plan_id'          => $attributes['saas_plan_id'] ?? null,
            'plan_expiry_date'      => $attributes['plan_expiry_date'] ?? null,
            'plan_billing_cycle'      => $attributes['plan_billing_cycle'] ?? null,
        ];

        $tenant = null;
        
        \DB::transaction(function () use ($tenant_details, $attributes, &$tenant) {
            $tenant = Tenant::create($tenant_details);
            if (is_null($tenant_details['domain'])) {
                $tenant->domain = $this->getUniqueTenantDomain($tenant->slug);
                $tenant->save();
                $tenant->refresh();
            }
            $tenant_billing_details = [
                'tenant_id'             => $tenant->id,
                'billing_name'          => $attributes['tenant_billing_detail']['billing_name'],
                'billing_email'         => $attributes['tenant_billing_detail']['billing_email'],
                'billing_phone'         => $attributes['tenant_billing_detail']['billing_phone'],
                'billing_address'       => $attributes['tenant_billing_detail']['billing_address'],
            ];

            TenantBillingDetail::create($tenant_billing_details);
<<<<<<< HEAD

            $saas_plan = $this->SAAS_PLAN_REPOSITORY->fetch([ 'id' => $tenant->saas_plan_id ]);

            foreach ($saas_plan['modules'] as $module) {
                $tenant_module = new TenantModule();
                $tenant_module->tenant_id = $tenant->id;
                $tenant_module->saas_module_id = $module['module_id'];
                $tenant_module->module_limit = $module['module_limit'];
                $tenant_module->save();
=======
            if ($tenant->saas_plan_id) {
                $saas_plan = $this->SAAS_PLAN_REPOSITORY->fetch([ 'id' => $tenant->saas_plan_id ]);
                
                foreach ($saas_plan['modules'] as $module) {
                    $tenant_module = new TenantModule();
                    $tenant_module->tenant_id = $tenant->id;
                    $tenant_module->saas_module_id = $module['module_id'];
                    $tenant_module->module_limit = $module['module_limit'];
                    $tenant_module->save();
                }
>>>>>>> fb1fc3f8f38de8dd25d1705e2b2dba1e744a9f0e
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
            'domain'                => ['nullable', new Domain, "unique:tenants,domain,{$attributes['tenant_id']},id"],
            'admin_domain'          => ['nullable', new Domain, "unique:tenants,admin_domain,{$attributes['tenant_id']},id"],
            'name'                  => 'required',
            'email'                 => ['required', 'email', "unique:tenants,email,{$attributes['tenant_id']},id"],
            'mobile'                => 'required',
            'city'                  => 'required',
            'country'               => 'required',
            'status'                => 'required|boolean',
            'business_type'         => 'required|string|in:food_delivery,   grocery_delivery,bakery_delivery,pet_food_delivery,bouquet_delivery,stationary_delivery,accessories_delivery,clothing_delivery,beverages_delivery',
            'saas_plan_id'          => 'nullable',
            'plan_expiry_date'      => 'nullable|date',
            'plan_billing_cycle'    => 'nullable',
            'tenant_billing_detail.billing_name'    => 'required',
            'tenant_billing_detail.billing_email'   => 'required|email',
            'tenant_billing_detail.billing_phone'   => 'required',
            'tenant_billing_detail.billing_address' => 'required',
        ]);

        if ($validator->fails()) {
            $this->errors = $validator->errors()->all();
            throw new \Exception("Validation error");
        }

        $tenant = Tenant::findOrFail($attributes['tenant_id']);

        $tenant->domain                 = $attributes['domain'] ?? null;
        $tenant->admin_domain           = $attributes['admin_domain'] ?? null;
        $tenant->name                   = $attributes['name'];
        $tenant->email                  = $attributes['email'];
        $tenant->mobile                 = $attributes['mobile'];
        $tenant->city                   = $attributes['city'];
        $tenant->status                 = $attributes['status'];
        $tenant->business_type          = $attributes['business_type'];
        $tenant->saas_plan_id           = $attributes['saas_plan_id'] ?? null;
        $tenant->plan_expiry_date       = $attributes['plan_expiry_date'] ?? null;
        $tenant->plan_billing_cycle     = $attributes['plan_billing_cycle'] ?? null;
        $tenant->save();
        if (is_null($tenant->domain)) {
            $tenant->domain = $this->getUniqueTenantDomain($tenant->slug);
            $tenant->save();
            $tenant->refresh();
        }
        // dd($tenant->slug);
        $tenant->tenantBillingDetail->billing_name        = $attributes['tenant_billing_detail']['billing_name'];
        $tenant->tenantBillingDetail->billing_email       = $attributes['tenant_billing_detail']['billing_email'];
        $tenant->tenantBillingDetail->billing_phone       = $attributes['tenant_billing_detail']['billing_phone'];
        $tenant->tenantBillingDetail->billing_address     = $attributes['tenant_billing_detail']['billing_address'];
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

    public function configureSetup(array $params)
    {
        $validator = Validator::make($params, [
            'tenant_id' => 'required',
            'is_setup_configured' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            $this->errors = $validator->errors()->all();
            throw new \Exception("Validation error");
        }

        $validated = $validator->validated();

        $tenant = Tenant::findOrFail($validated['tenant_id']);
        $tenant->is_setup_configured = boolval($validated['is_setup_configured']);
        $tenant->save();

        return $tenant;
    }

    public function tenantExists(array $params)
    {
        $validator = Validator::make($params, [
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            $this->errors = $validator->errors()->all();
            throw new \Exception("Validation error");
        }

        $validated = $validator->validated();

        $tenant_exists = Tenant::where('email', $validated['email'])->exists();

        return [
            "exists" => $tenant_exists
        ];
    }
    
    public function updateDomain(array $attributes)
    {
        $validator = Validator::make($attributes, [
            'tenant_id' => 'integer',
            'domain' => ['nullable', new Domain, "unique:tenants,domain,{$attributes['tenant_id']},id"],
            'admin_domain' => ['nullable', new Domain, "unique:tenants,admin_domain,{$attributes['tenant_id']},id"]
        ]);
        
        if ($validator->fails()) {
            $this->errors = $validator->errors()->all();
            throw new \Exception("Validation error");
        }
        
        $validated_values = $validator->validated();
        $response['tenant_id'] = $validated_values['tenant_id'];

        if (isset($validated_values['domain'], $validated_values['admin_domain'])) {
            $db_response = Tenant::where('id', $validated_values['tenant_id'])->update([
                'domain' => $validated_values['domain'],
                'admin_domain' => $validated_values['admin_domain']
            ]);

            $response['updated_domain'] = $validated_values['domain'];
            $response['updated_admin_domain'] = $validated_values['admin_domain'];

            if ($db_response != true) {
                return $response;
            }
            return $response;
        }

        if (isset($validated_values['domain']) || isset($validated_values['admin_domain'])) {
            if (isset($validated_values['domain'])) {
                $db_response = Tenant::where('id', $validated_values['tenant_id'])->update(['domain' => $validated_values['domain']]);
                $response['updated_domain'] = $validated_values['domain'];
                
                if ($db_response != true) {
                    return $response;
                }
                
                return $response;
            }

            if (isset($validated_values['admin_domain'])) {
                $db_response = Tenant::where('id', $validated_values['tenant_id'])->update(['admin_domain' => $validated_values['admin_domain']]);
                $response['updated_admin_domain'] = $validated_values['admin_domain'];

                if ($db_response != true) {
                    return $response;
                }
        
                return $this->successResponse("Domain: {$validated_values['admin_domain']} updated successfully!", $response);
            }
        }
        
        return $response;
    }

    public function getUniqueTenantDomain($slug)
    {
        return "$slug.hyperzod.app";
    }

    public function onboarding(array $params)
    {
        $validator = Validator::make($params, [
            'user_name' => 'required|string',
            'email' => ['required', 'email', 'unique:tenants'],
            'password' => 'required|string',
            'mobile' => 'required',
            'tenant_name' => 'required|string',
            'business_type' => 'required|string|in:food_delivery,   grocery_delivery,bakery_delivery,pet_food_delivery,bouquet_delivery,stationary_delivery,accessories_delivery,clothing_delivery,beverages_delivery',
            'city' => 'required',
            'country' => 'required',
            'login_type' => 'required|string|in:tenant'
        ]);

        if ($validator->fails()) {
            $this->errors = $validator->errors()->all();
            throw new \Exception("Validation error");
        }

        $validated = $validator->validated();

        //Create tenant
        $tenant = $this->save([
            'name' => $validated['tenant_name'],
            'email' => $validated['email'],
            'mobile' => $validated['mobile'],
            'city' => $validated['city'],
            'country' => $validated['country'],
            'status' => true,
            'business_type' => $validated['business_type'],
            'tenant_billing_detail' => [
                'billing_name' => $validated['tenant_name'],
                'billing_email' => $validated['email'],
                'billing_phone' => $validated['mobile'],
                'billing_address' => $validated['city'],
            ],
        ]);

        if (!$tenant) {
            throw new \Exception("Error while creating tenant");
        }

        return $tenant;
    }
}
