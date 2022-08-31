<?php

namespace App\Models;

use Hyperzod\HyperzodServiceFunctions\HyperzodServiceFunctions;
use Hyperzod\HyperzodServiceFunctions\Traits\SettingsServiceTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Tenant extends Model
{
    use SoftDeletes, HasSlug, SettingsServiceTrait;

    protected $fillable = [
        'domain',
        'admin_domain',
        'name',
        'slug',
        'email',
        'mobile',
        'city',
        'country',
        'business_type',
        'is_setup_configured',
        'status',
        'is_open',
    ];

    protected $with = [
        'saasModules',
    ];

    protected $appends = [
        'native_domain_ordering',
        'native_domain_admin',
    ];

    protected $casts = [
        'is_setup_configured' => 'boolean',
        'is_open' => 'boolean',
    ];

    public function saasModules()
    {
        return $this->hasManyThrough(SaasModule::class, TenantModule::class, 'tenant_id', 'id', 'id', 'saas_module_id');
    }

    public function tenantModules()
    {
        return $this->hasMany(TenantModule::class);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = strtolower($value);
    }

    public function getNativeDomainOrderingAttribute()
    {
        $domain = HyperzodServiceFunctions::hyperzodOrderingAppNativeDomainTLD();
        return $this->slug . "." . $domain;
    }

    public function getNativeDomainAdminAttribute()
    {
        return HyperzodServiceFunctions::hyperzodTenantAdminAppNativeDomain($this->slug);
    }
}
