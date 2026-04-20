<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class ShippingZone extends Model
{
    use HasUuids, BelongsToTenant, SoftDeletes;

    protected $fillable = [
        'name',
        'countries',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'countries' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function methods(): HasMany
    {
        return $this->hasMany(ShippingMethod::class)->orderBy('sort_order');
    }

    /**
     * returns true when countries list is empty
     */
    public function isCatchAll(): bool
    {
        return empty($this->countries);
    }

    public function coversCountry(string $countryCode): bool
    {
        if ($this->isCatchAll()) {
            return true;
        }

        return in_array(strtoupper($countryCode), array_map('strtoupper', $this->countries ?? []), true);
    }
}