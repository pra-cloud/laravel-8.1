<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'domain', 'name', 'status', 'plan_expiry_date', 'plan_billing_cycle', 'payment_failed_tries', 'email', 'mobile','country', 'city', 'saas_plan_id', 'mobile'
    ];

    protected $with = ['tenantBillingDetail', 'tenantModules'];

    /**
     * One to one relation between
     * Tenant and Tenant Billing Details
     */
    public function tenantBillingDetail()
    {
        return $this->hasOne(TenantBillingDetail::class);
    }

    public function tenantModules()
    {
        return $this->hasMany(TenantModule::class);
    }

}
