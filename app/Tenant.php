<?php

namespace App;

use Hyperzod\HyperzodServiceFunctions\HyperzodServiceFunctions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Tenant extends Model
{
    use SoftDeletes, HasSlug;

    protected $fillable = [
        'domain', 'admin_domain', 'name', 'slug', 'email', 'mobile', 'city', 'country', 'business_type', 'is_setup_configured', 'status', 'is_open',
    ];

    protected $with = ['saasModules'];

    protected $appends = ['native_domain'];

    public function saasModules()
    {
        return $this->hasManyThrough(SaasModule::class, TenantModule::class, 'tenant_id', 'id', 'id', 'saas_module_id');
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function getNativeDomainAttribute()
    {
        $domain = HyperzodServiceFunctions::frontendStoreDomain();
        return $this->slug . "." . $domain;
    }
}
