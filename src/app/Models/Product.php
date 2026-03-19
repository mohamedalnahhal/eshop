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

    // تأكد من إضافة 'image' إذا كنت تستخدم حقل صورة في الجدول
    protected $fillable = ['name', 'price', 'description', 'stock', 'tenant_id'];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    /**
     * العلاقة مع المستأجر
     */
    public function tenant(): BelongsTo 
    { 
        return $this->belongsTo(Tenant::class); 
    }

    /**
     * علاقة متعدد لمتعدد مع الأقسام (الجدول الوسيط)
     * هذه هي العلاقة التي سنستخدمها في Filament
     */
    public function categories(): BelongsToMany 
    { 
        return $this->belongsToMany(Category::class, 'category_product'); 
    }

    /**
     * المراجعات والتقييمات
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    /**
     * الوسائط (الصور وغيرها)
     */
    public function media(): MorphMany 
    { 
        return $this->morphMany(Media::class, 'mediable'); 
    }
}