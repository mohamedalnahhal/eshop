<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToTenantOrGlobal
{
    public static $tenantIdColumn = 'tenant_id';

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(config('tenancy.tenant_model'), static::$tenantIdColumn);
    }

    protected static function bootBelongsToTenantOrGlobal(): void
    {
        static::addGlobalScope('tenant_or_global', function ($query) {
            $tenantId = tenant()?->getTenantKey();

            $query->where(function ($q) use ($tenantId) {
                $q->whereNull(static::$tenantIdColumn);

                if ($tenantId) {
                    $q->orWhere(static::$tenantIdColumn, $tenantId);
                }
            });
        });

        static::creating(function ($model) {
            $col = static::$tenantIdColumn;

            if (! array_key_exists($col, $model->getAttributes()) && tenancy()->initialized) {
                $model->setAttribute($col, tenant()->getTenantKey());
            }
        });
    }
}