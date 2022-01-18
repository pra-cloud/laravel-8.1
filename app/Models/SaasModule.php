<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaasModule extends Model
{
    protected $fillable = ['module_name', 'active'];

    protected $hidden = ['laravel_through_key'];

    // local scope for active only
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
}
