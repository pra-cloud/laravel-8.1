<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaasPlan extends Model
{
    protected $casts = [
        'modules' => 'array',
    ];

    protected $fillable = [
        'name', 'description', 'modules', 'amount', 'status'
    ];
}
