<?php 
namespace App\Services;

use App\TenantBillingDetail;

class TenantBillingDetailService
{
    /**
     * Destroy billing detail
     * Related to Tenant
     */
    public function destroyTenantBillingDetail($id)
    {
        TenantBillingDetail::where('tenant_id',$id)->delete();
    }
}