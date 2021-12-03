<?php

namespace App;

use App\Modules\Billing\Traits\Billable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Tenant extends Model
{
    use SoftDeletes, HasSlug, Billable;

    protected $fillable = [
        'domain', 'admin_domain', 'name', 'status', 'business_type', 'plan_expiry_date', 'plan_billing_cycle', 'payment_failed_tries', 'email', 'mobile', 'country', 'city',  'mobile', 'is_setup_configured', 'slug',
        'billing_provider', 'billing_provider_customer_id'
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
        return $this->hasManyThrough(SaasModule::class, TenantModule::class, 'saas_module_id', 'id', 'id', 'saas_module_id');
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
