<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Category extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'type', 'tenant_id'];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }
}
