<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasUuids;
    use BelongsToTenant;

    protected $fillable = ['name', 'price', 'description', 'stock', 'tenant_id'];
    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function categories() { return $this->belongsToMany(Category::class, 'category_product'); }
    public function media() { return $this->morphMany(Media::class, 'mediable'); }
    public function category(): BelongsTo
       {
         // افترضنا هنا أن العمود في جدول المنتجات هو category_id
           return $this->belongsTo(Category::class);
       }
       public function reviews()
    {
        return $this->hasMany(Review::class)->latest();
    }
}