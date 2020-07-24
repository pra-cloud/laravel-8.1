<?php 
namespace App\Services;

use App\Tenant;
use App\TenantBillingDetail;
use Illuminate\Support\Facades\Validator;

class TenantService
{
    /**
     * Save Tenant Details and
     * Tenant Billing Details related to Tenant
     */
    public function save(array $attributes)
    {
        Validator::extend('without_spaces', function ($attr, $value) {
            return preg_match('/^\S*$/u', $value);
        });
        $message = [
            'domain.without_spaces' => 'The :attribute must be without spaces.',
        ];

        $validator = Validator::make($attributes, [
            'domain'                => 'required|without_spaces|unique:tenants,domain',
            'name'                  => 'required',
            'email'                 => 'required|email',
            'mobile'                => 'required',
            'city'                  => 'required',
            'country'               => 'required',
            'status'                => 'required',
            'saas_plan_id'          => 'required',
            'plan_expiry_date'      => 'required|date',
            'payment_failed_tries'  => 'required',
            'tenant_billing_detail.billing_name'    => 'required',
            'tenant_billing_detail.billing_email'   => 'required|email',
            'tenant_billing_detail.billing_phone'   => 'required',
            'tenant_billing_detail.billing_address' => 'required',
            'tenant_billing_detail.tax_type_id'     => 'required',
            'tenant_billing_detail.tax_id'          => 'required',
        ],$message);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->errors()->all()]);
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
            'payment_failed_tries'  => $attributes['payment_failed_tries'],
        ];

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

        $message = 'Tenant has been created successfully.';

        return response()->json(['tenant_id' => $tenant->id, 'success' => true, 'message' => $message]);
    }

    /**
     * Update Tenant Details and
     * Tenant Billing Details related to Tenant
     */
    public function update(array $attributes)
    {
        Validator::extend('without_spaces', function ($attr, $value) {
            return preg_match('/^\S*$/u', $value);
        });
        $message = [
            'domain.without_spaces' => 'The :attribute must be without spaces.',
        ];

        $validator = Validator::make($attributes, [
            'domain'                => 'required|without_spaces|unique:tenants,domain,'.$attributes['tenant_id'],
            'name'                  => 'required',
            'email'                 => 'required|email',
            'mobile'                => 'required',
            'city'                  => 'required',
            'country'               => 'required',
            'status'                => 'required',
            'saas_plan_id'          => 'required',
            'plan_expiry_date'      => 'required|date',
            'payment_failed_tries'  => 'required',
            'tenant_billing_detail.billing_name'    => 'required',
            'tenant_billing_detail.billing_email'   => 'required|email',
            'tenant_billing_detail.billing_phone'   => 'required',
            'tenant_billing_detail.billing_address' => 'required',
            'tenant_billing_detail.tax_type_id'     => 'required',
            'tenant_billing_detail.tax_id'          => 'required',
        ],$message);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'error' => $validator->errors()->all()]);
        }

        $tenant = Tenant::find($attributes['tenant_id']);
        if (!$tenant) {
            return response()->json(['success' => false, 'error' => 'Tenant not found.']);
        }

        $tenant->domain                 = $attributes['domain'];
        $tenant->name                   = $attributes['name'];
        $tenant->email                  = $attributes['email'];
        $tenant->mobile                 = $attributes['mobile'];
        $tenant->city                   = $attributes['city'];
        $tenant->status                 = $attributes['status'];
        $tenant->saas_plan_id           = $attributes['saas_plan_id'];
        $tenant->plan_expiry_date       = $attributes['plan_expiry_date'];
        $tenant->payment_failed_tries   = $attributes['payment_failed_tries'];

        $tenant->save();

        $tenant->tenantBillingDetail->billing_name        = $attributes['tenant_billing_detail']['billing_name'];
        $tenant->tenantBillingDetail->billing_email       = $attributes['tenant_billing_detail']['billing_email'];
        $tenant->tenantBillingDetail->billing_phone       = $attributes['tenant_billing_detail']['billing_phone'];
        $tenant->tenantBillingDetail->billing_address     = $attributes['tenant_billing_detail']['billing_address'];
        $tenant->tenantBillingDetail->tax_type_id         = $attributes['tenant_billing_detail']['tax_type_id'];
        $tenant->tenantBillingDetail->tax_id              = $attributes['tenant_billing_detail']['tax_id'];

        $tenant->tenantBillingDetail->save();

        $message = 'Tenant has been updated successfully.';

        return response()->json(['success' => true, 'message' => $message]);
    }

    /**
     * Fetch list of Tenants
     * with Tenant Billing Details
     */
    public function fetchAll(array $attributes = null)
    {
        $tenants = Tenant::get();
        return response()->json(['success' => true, 'message' => 'List of Tenants','data' => $tenants]);
    }

    /**
     * Fetch Tenant Details
     * with Tenant Billing Details
     */
    public function fetch($id)
    {
        $tenant = Tenant::where('id', $id)->first();
        
        if(!$tenant)
            return response()->json(['success' => true, 'message' => 'View Tenant Details','data' => 'Tenant not found']);

        return response()->json(['success' => true, 'message' => 'View Tenant Details','data' => $tenant]);
    }

    /**
     * Destroy Tenant
     * with Tenant Billing Details
     */
    public function destroy($id)
    {
        Tenant::find($id)->delete();
        return response()->json(['success' => true, 'message' => 'Tenant has been deleted successfully.']);
    }
}
 ?>