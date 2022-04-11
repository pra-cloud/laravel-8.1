<?php

namespace App\Repositories;

use App\Models\Tenant;
use App\Rules\Domain;
use Hyperzod\HyperzodServiceFunctions\HyperzodServiceFunctions;
use Illuminate\Support\Facades\Validator;
use Hyperzod\HyperzodServiceFunctions\Traits\HelpersServiceTrait;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class TenantRepository extends BaseRepository
{
    use HelpersServiceTrait;

    /**
     * Save Tenant Details and
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
            'domain'                => ['nullable', 'unique:tenants', new Domain],
            'admin_domain'          => ['nullable', 'unique:tenants', new Domain],
            'name'                  => 'required',
            'email'                 => 'required|email',
            'mobile'                => 'nullable',
            'city'                  => 'nullable',
            'country'               => 'nullable|country_exists',
            'status'                => 'required|boolean',
            'business_type'         => 'nullable|string|in:food_delivery,grocery_delivery,bakery_delivery,pet_food_delivery,bouquet_delivery,stationary_delivery,accessories_delivery,clothing_delivery,beverages_delivery',
        ]);

        if ($validator->fails()) {
            $this->errors = $validator->errors()->all();
            throw new \Exception("Validation error");
        }

        $tenant_details = [
            'domain'                => $this->parseDomain($attributes['domain'] ?? null),
            'admin_domain'          => $attributes['admin_domain'] ?? null,
            'name'                  => $attributes['name'],
            'email'                 => $attributes['email'],
            'mobile'                => $attributes['mobile'] ?? null,
            'city'                  => $attributes['city'] ?? null,
            'country'               => $attributes['country'] ?? null,
            'status'                => $attributes['status'],
            'business_type'         => $attributes['business_type'] ?? null,
            'is_open'               => true,
        ];

        $tenant = Tenant::create($tenant_details);

        # Generate unique domain from slug if not any domain has been provided
        if (is_null($tenant_details['domain'])) {
            $tenant->domain = $this->getUniqueTenantDomain($tenant->slug);
            $tenant->save();
        }

        return $tenant;
    }

    /**
     * Update Tenant Details and
     */
    public function update(array $attributes)
    {
        $business_types = $this->fetchBusinessTypes();
        $validator = Validator::make($attributes, [
            'domain'                => ['nullable', new Domain, "unique:tenants,domain,{$attributes['tenant_id']},id"],
            'admin_domain'          => ['nullable', new Domain, "unique:tenants,admin_domain,{$attributes['tenant_id']},id"],
            'name'                  => 'required',
            'email'                 => 'required|email',
            'mobile'                => 'nullable',
            'city'                  => 'nullable',
            'country'               => 'nullable',
            'status'                => 'required|boolean',
            'business_type' => ['nullable', Rule::in($business_types)],
        ]);

        if ($validator->fails()) {
            $this->errors = $validator->errors()->all();
            throw new \Exception("Validation error");
        }

        $tenant = Tenant::findOrFail($attributes['tenant_id']);

        $tenant->domain                 = $this->parseDomain($attributes['domain'] ?? null);
        $tenant->admin_domain           = $attributes['admin_domain'] ?? null;
        $tenant->name                   = $attributes['name'];
        $tenant->email                  = $attributes['email'];
        $tenant->mobile                 = $attributes['mobile'] ?? null;
        $tenant->city                   = $attributes['city'] ?? null;
        $tenant->country                = $attributes['country'] ?? null;
        $tenant->status                 = $attributes['status'];
        $tenant->business_type          = $attributes['business_type'] ?? null;
        $tenant->save();

        return $tenant;
    }

    /**
     * Fetch list of Tenants
     */
    public function fetchAll(array $attributes = [])
    {
        $tenants = Tenant::where($attributes)->get();
        return $tenants;
    }

    public function listByIds(array $attributes)
    {
        $validator = Validator::make($attributes, [
            'list_type' => 'nullable|string|in:default,tenant_names',
            'tenant_ids' => 'required|array',
            'tenant_ids.*' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $this->errors = $validator->errors()->all();
            throw new \Exception("Validation error");
        }
        $tenants = Tenant::query();
        $validated = $validator->validated();
        $validated['tenant_ids'] = $this->castNumerics($validated['tenant_ids']);
        $tenants->whereIn('id', $validated['tenant_ids']);
        if (isset($validated['list_type']) && $validated['list_type'] == 'tenant_names') {
            $tenants = $tenants->select([
                'id',
                'name',
                'slug',
                'status',
                'business_type',
            ])->get();
            return $tenants;
        }
        return $tenants->get();
    }

    /**
     * Fetch Tenant Details
     s
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

        return Tenant::where($attributes)->firstOrFail();
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

        $tenant = Tenant::setEagerLoads([])->select($this->tenant_public_details)->where('admin_domain', $attributes['admin_domain'])->first();

        if (!$tenant) {
            throw new \Exception("Tenant not found by this admin domain");
        }

        return $tenant;
    }

    public function getTenantIdByDomain($attributes)
    {
        $validator = Validator::make($attributes, [
            'domain' => ['required', new Domain],
        ]);

        if ($validator->fails()) {
            $this->errors = $validator->errors()->all();
            throw new \Exception("Validation error");
        }

        $attributes['domain'] = $this->parseDomain($attributes['domain']);

        $tenant = Tenant::setEagerLoads([])->select('id')->where('domain', $attributes['domain'])->first();

        # Check if domain is a subdomain of any tenant like tenant.hyperzod.app
        if (!$tenant) {
            $nativeStoreDomain = HyperzodServiceFunctions::frontendStoreDomain();
            if (strpos($attributes['domain'], $nativeStoreDomain) !== false) {
                $slug = explode(".", $attributes['domain'])[0];
                $tenant = Tenant::setEagerLoads([])->select('id')->where('slug', $slug)->first();
            }
        }

        if (!$tenant) {
            throw new \Exception("Tenant not found by this domain");
        }

        return ['tenant_id' => $tenant->id];
    }

    public function getDomainsByTenantId($attributes)
    {
        $validator = Validator::make($attributes, [
            'tenant_id' => ['required'],
        ]);

        if ($validator->fails()) {
            $this->errors = $validator->errors()->all();
            throw new \Exception("Validation error");
        }

        $tenant = Tenant::setEagerLoads([])->select('id', 'slug', 'domain', 'admin_domain')->findOrFail($attributes['tenant_id']);

        return $tenant->toArray();
    }

    // parseDomain
    public function parseDomain($domain)
    {
        if (is_null($domain)) return null;

        $domain_parts = explode('.', $domain);
        if (count($domain_parts) > 2) {
            return $domain;
        }
        // insert element to the beginning of the array
        array_unshift($domain_parts, 'www');
        return implode('.', $domain_parts);
    }

    /**
     * Soft Delete Tenant
     s
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

    /**
     * Force Delete Tenant
     s
     */
    public function forceDestroy($id)
    {
        $tenant = Tenant::find($id);

        if (!$tenant) {
            throw new \Exception("Cannot find tenant.");
        }

        if ($tenant->forceDelete()) {
            return 'Tenant has been deleted successfully.';
        }
        throw new \Exception("Error in deleting tenant.");
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

    public function updateDomain(array $attributes)
    {
        $validator = Validator::make($attributes, [
            'tenant_id' => 'integer',
            'domain' => ['nullable', new Domain, "unique:tenants,domain,{$attributes['tenant_id']},id"],
            'admin_domain' => ['nullable', new Domain, "unique:tenants,admin_domain,{$attributes['tenant_id']},id"]
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
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
        if (config('app.env') == 'prod') return "$slug.hyperzod.app";
        if (config('app.env') == 'dev') return "$slug.hyperzod.shop";
        return "$slug.hyperzod.local";
    }

    public function onboarding(array $params)
    {
        $business_types = $this->fetchBusinessTypes();

        $validator = Validator::make($params, [
            'user_name' => 'required|string',
            'tenant_name' => 'required|string',
            'email' => 'required|email',
            'mobile' => 'nullable',
            'business_type' => ['nullable', Rule::in($business_types)],
            'city' => 'nullable',
            'country' => 'nullable',
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
            'mobile' => $validated['mobile'] ?? null,
            'city' => $validated['city'] ?? null,
            'country' => $validated['country'] ?? null,
            'business_type' => $validated['business_type'] ?? null,
            'status' => true,
            'is_open' => true,
        ]);

        if (!$tenant) {
            throw new \Exception("Error while creating tenant");
        }
        return $tenant;
    }

    public function viewPublic(array $params)
    {
        $validator = Validator::make($params, [
            'tenant_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $this->errors = $validator->errors()->all();
            throw new \Exception("Validation error");
        }

        $validated = $validator->validated();

        return Tenant::select($this->tenant_public_details)->where('id', $validated['tenant_id'])->firstOrFail();
    }
}
