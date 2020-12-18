<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'domain', 'admin_domain', 'name', 'status', 'plan_expiry_date', 'plan_billing_cycle', 'payment_failed_tries', 'email', 'mobile','country', 'city', 'saas_plan_id', 'mobile'
    ];

    protected $with = ['tenantBillingDetail', 'tenantModules', 'saasPlan'];

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

    public function saasPlan()
    {
        return $this->belongsTo(SaasPlan::class);
    }

}
