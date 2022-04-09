<?php

namespace App\Models;

use Hyperzod\HyperzodServiceFunctions\Enums\TerminologyEnum;
use Illuminate\Database\Eloquent\Model;

class TenantModule extends Model
{
    protected $with = ['saasModule'];

    public function saasModule()
    {
        return $this->belongsTo(SaasModule::class);
    }

    public function scopeTenant($query, $tenant_id)
    {
        return $query->where(TerminologyEnum::TENANT_ID, $tenant_id);
    }
}
