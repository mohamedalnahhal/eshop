<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class Domain extends Model
{
    use HasUuids;
    use BelongsToTenant;
    
    protected $fillable = ['tenant_id', 'subdomain'];

    public function tenant() { return $this->belongsTo(Tenant::class); }
}
