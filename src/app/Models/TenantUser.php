<?php

namespace App\Models;

use App\Enums\TenantUserRole;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Database\Concerns\BelongsToPrimaryModel;

class TenantUser extends Pivot
{
    use HasUuids;
    use BelongsToPrimaryModel;

    protected $table = 'tenant_users';

    protected $casts = [
        'role' => TenantUserRole::class,
    ];

    public function getRelationshipToPrimaryModel(): string
    {
        return 'tenant';
    }
}