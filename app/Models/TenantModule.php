<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantModule extends Model
{
    protected $with = ['saasModule'];

    public function saasModule()
    {
        return $this->belongsTo(SaasModule::class);
    }
}
