<?php

namespace App\Models;

use App\Enums\TenantUserRole;
use App\Traits\HasTenantPermissions;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class TenantUser extends Pivot
{
    use HasUuids;
    use BelongsToTenant;
    use HasTenantPermissions;

    public $timestamps = true;

    protected $table = 'tenant_users';

    protected $fillable = [
        'user_id',
        'tenant_id',
        'role',
    ];

    protected $casts = [
        'role' => TenantUserRole::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function permissions()
    {
        return $this->hasMany(TenantUserPermission::class, 'tenant_user_id');
    }
}