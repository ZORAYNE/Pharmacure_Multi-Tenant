<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_name',
        'full_name',
        'email',
        'role',
        'password',
        'status',
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
