<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class TenantUser extends Authenticatable
{
    use HasRoles;

    protected $connection = 'tenant'; // Use dynamically set 'tenant' connection
    protected $table = 'users';

    protected $guarded = [];

    protected $hidden = ['password', 'remember_token'];
}
