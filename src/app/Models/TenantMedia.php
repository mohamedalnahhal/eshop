<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Media extends Model
{
    use HasUuids;
    use BelongsToTenant;

    protected $fillable = [
        'mediable_id',
        'mediable_type',
        'tenant_id',
        'collection_name',
        'file_path',
        'file_type',
        'file_size'
    ];

    protected $casts = [
        'file_size' => 'decimal:2',
    ];

    public function mediable() { return $this->morphTo(); }
    public function tenant() { return $this->belongsTo(Tenant::class); }
}