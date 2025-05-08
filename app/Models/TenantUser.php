<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class TenantUser  extends Authenticatable
{
    protected $connection = 'tenant'; // Use dynamically set 'tenant' connection
    protected $table = 'users';

    protected $fillable = ['name', 'email', 'role', 'password'];

    protected $hidden = ['password', 'remember_token'];
}