<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TenantBillingDetail extends Model
{
    protected $fillable = [
        'tenant_id','billing_name', 'billing_email', 'billing_phone', 'billing_address', 'tax_type_id', 'tax_id'
    ];

    /**
     * One to one relation between 
     * Tenant Billing Detail and Tenant
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
