<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

class Product extends Model implements TranslatableContract
{
    use HasUuids;
    use BelongsToTenant;
    use SoftDeletes;
    use Translatable;

    public array $translatedAttributes = ['name', 'description'];

    protected $fillable = [
        'price',
        'avg_rating',
        'stock',
        'tenant_id',
        'reviews_count',
        'rating_sum',
    ];

    protected $casts = [
        'price'         => 'decimal:2',
        'avg_rating'    => 'decimal:1',
        'stock'         => 'integer',
        'reviews_count' => 'integer',
        'rating_sum'    => 'integer',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_product');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'id')->whereIn('id', function ($query) {
            $query->select('category_id')
                  ->from('category_product')
                  ->where('product_id', $this->id);
        });
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}