<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Category extends Model
{
    use HasUuids;
    use BelongsToTenant;
    use HasFactory;
    
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['name', 'type', 'parent_id', 'tenant_id'];

    public function tenant() { return $this->belongsTo(Tenant::class); }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'category_product');
    }
}
