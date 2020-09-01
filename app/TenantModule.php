<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TenantModule extends Model
{
    protected $with = ['saasModule'];

    public function saasModule()
    {
        return $this->belongsTo(SaasModule::class);
    }
}
