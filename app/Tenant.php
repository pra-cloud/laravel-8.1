<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'domain', 'name', 'status', 'plan_expiry_date', 'payment_failed_tries', 'email', 'mobile','country', 'city', 'tenant_plan_id', 'mobile'
    ];

    /**
     * One to one relation between 
     * Tenant and Tenant Billing Details
     */
    public function tenantBillingDetail()
    {
        return $this->hasOne(TenantBillingDetail::class);
    }
}
