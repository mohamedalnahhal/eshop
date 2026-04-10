<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\SoftDeletes;

class Location extends Model
{
    use HasUuids;
    use BelongsToTenant;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'phone',
        'is_pickup_point',
        'is_visible_to_customers',
    ];

    protected $casts = [
        'is_pickup_point'         => 'boolean',
        'is_visible_to_customers' => 'boolean',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    
    public function address() { return $this->morphOne(Address::class, 'addressable'); }
}