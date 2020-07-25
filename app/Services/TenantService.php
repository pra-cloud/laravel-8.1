<?php 
namespace App\Services;

use App\Traits\ApiResponse;
use App\Tenant;
use App\TenantBillingDetail;
use Illuminate\Support\Facades\Validator;
use App\Rules\Domain;

class TenantService
{
    use ApiResponse;
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
            'plan_expiry_date'      => 'required|date',
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
    
            return $this->successResponse('Tenant has been created successfully.', [ 'tenant_id' => $tenant->id ]);
        }
    }

    /**
     * Update Tenant Details and
     * Tenant Billing Details related to Tenant
     */
    public function update(array $attributes)
    {
        $validator = Validator::make($attributes, [
            'domain'                => ['required', new Domain, 'unique:tenants,'.$attributes['tenant_id']],
            'name'                  => 'required',
            'email'                 => 'required|email',
            'mobile'                => 'required',
            'city'                  => 'required',
            'country'               => 'required',
            'status'                => 'required',
            'saas_plan_id'          => 'required',
            'plan_expiry_date'      => 'required|date',
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

        $tenant->save();

        $tenant->tenantBillingDetail->billing_name        = $attributes['tenant_billing_detail']['billing_name'];
        $tenant->tenantBillingDetail->billing_email       = $attributes['tenant_billing_detail']['billing_email'];
        $tenant->tenantBillingDetail->billing_phone       = $attributes['tenant_billing_detail']['billing_phone'];
        $tenant->tenantBillingDetail->billing_address     = $attributes['tenant_billing_detail']['billing_address'];
        $tenant->tenantBillingDetail->tax_type_id         = $attributes['tenant_billing_detail']['tax_type_id'];
        $tenant->tenantBillingDetail->tax_id              = $attributes['tenant_billing_detail']['tax_id'];

        $tenant->tenantBillingDetail->save();

        return $this->successResponse('Tenant has been updated successfully.');
    }

    /**
     * Fetch list of Tenants
     * with Tenant Billing Details
     */
    public function fetchAll(array $attributes = null)
    {
        $tenants = Tenant::get();
        return $this->successResponse(null, $tenants);
    }

    /**
     * Fetch Tenant Details
     * with Tenant Billing Details
     */
    public function fetch($id)
    {
        $tenant = Tenant::findOrFail($id);
        return $this->successResponse(null, $tenant);
    }

    /**
     * Destroy Tenant
     * with Tenant Billing Details
     */
    public function destroy($id)
    {
        Tenant::find($id)->delete();
        return $this->successResponse('Tenant has been deleted successfully.');
    }
}
 ?>