<?php

namespace App\Models;

use App\Enums\TenantUserRole;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TenantUser extends Pivot
{
    use HasUuids;

    protected $table = 'tenant_users';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;

    protected $casts = [
        'role' => TenantUserRole::class,
    ];
}