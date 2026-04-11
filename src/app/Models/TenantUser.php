<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TenantUser extends Model
{
    use HasUuids;

    protected $table = 'tenant_users';
}