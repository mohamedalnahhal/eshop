<?php

namespace App\Models;

use App\Enums\StockAdjustmentStatus;
use App\Enums\StockAdjustmentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class StockAdjustment extends Model
{
    use HasUuids;
    use BelongsToTenant;

    protected $fillable = [
        'product_id',
        'type',
        'supplier_id',
        'updated_value',
        'status',
    ];

    protected $casts = [
        'type' => StockAdjustmentType::class,
        'status' => StockAdjustmentStatus::class,
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}