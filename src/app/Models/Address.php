<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\AddressType;

class Address extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'line_1',
        'line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'lng',
        'lat',
        'is_default'
    ];

    protected $casts = [
        'lng' => 'decimal:8',
        'lat' => 'decimal:8',
        'type' => AddressType::class,
        'is_default' => 'boolean',
    ];

    public function addressable() { return $this->morphTo(); }
}
