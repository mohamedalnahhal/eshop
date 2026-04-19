<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenantUserPermission extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_user_id',
        'permission',
        'granted',
    ];

    protected function casts(): array
    {
        return [
            'granted' => 'boolean',
        ];
    }

    public function tenantUser(): BelongsTo
    {
        return $this->belongsTo(TenantUser::class);
    }
}