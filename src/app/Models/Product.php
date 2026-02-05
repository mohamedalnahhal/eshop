<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasUuid;

    protected $primaryKey = 'product_id';
    protected $fillable = ['name', 'price', 'description', 'stock', 'tenant_id'];

    public function tenant() {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'tenant_id');
    }
    public function media()
{
    return $this->morphMany(Media::class, 'model', 'model_type', 'model_id');
}
}