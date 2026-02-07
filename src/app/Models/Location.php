<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Location extends Model
{
    use HasUuids;

    protected $fillable = ['tenant_id', 'name', 'is_pickup_point'];

    protected $casts = [
        'is_pickup_point' => 'boolean',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function address() { return $this->morphOne(Address::class, 'addressable'); }
}