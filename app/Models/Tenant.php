<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tenant extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'tenant_name',
        'full_name',
        'email',
        'role',
        'password',
        'status',
        'subscription_plan',
    ];

    protected $hidden = [
        'password',
    ];

    public function setTenantNameAttribute($value)
    {
        $value = str_replace(' ', '_', $value);
        $this->attributes['tenant_name'] = strtolower($value);
    }
}
