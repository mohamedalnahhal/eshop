<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasUuids;
    use BelongsToTenant;
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = ['user_id', 'tenant_id'];

    public function user() { return $this->belongsTo(User::class); }
    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function items() { return $this->hasMany(CartItem::class); }
}