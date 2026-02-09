<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Enums\AddressType;

class Address extends Model
{
    use HasUuids;

    protected $fillable = [
        'addressable_id',
        'addressable_type',
        'type',
        'address_line_1',
        'city',
        'state',
        'postal_code',
        'country',
        'lng',
        'lat'
    ];

    protected $casts = [
        'lng' => 'decimal:8',
        'lat' => 'decimal:8',
        'type' => AddressType::class,
    ];

    public function addressable() { return $this->morphTo(); }
}
