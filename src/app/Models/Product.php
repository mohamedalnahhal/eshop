<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Product extends Model
{
    use HasUuids;
    use BelongsToTenant;

    protected $fillable = ['name', 'price', 'description', 'stock', 'tenant_id'];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function tenant(): BelongsTo 
    { 
        return $this->belongsTo(Tenant::class); 
    }

    /**
     * ✅ هذه الدالة هي الحل
     * يجب أن يكون اسمها categories بالجمع ليتوقف الخطأ
     */
    public function categories(): BelongsToMany 
    { 
        return $this->belongsToMany(Category::class, 'category_product'); 
    }

    /**
     * دالة للمفرد لضمان التوافق
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'id')->whereIn('id', function($query) {
            $query->select('category_id')->from('category_product')->where('product_id', $this->id);
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