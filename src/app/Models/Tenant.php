<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasUuid;

    protected $primaryKey = 'tenant_id';
    protected $fillable = ['name', 'subdomain', 'status'];

    public function products() {
        return $this->hasMany(Product::class, 'tenant_id', 'tenant_id');
    }
    public function theme()
{
    return $this->belongsTo(Theme::class, 'theme_id', 'theme_id');
}
}