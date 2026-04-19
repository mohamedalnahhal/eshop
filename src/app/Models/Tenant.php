<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Domain;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Enums\TenantStatus;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends BaseTenant
{
    use HasUuids, HasDomains , SoftDeletes;

    protected $fillable = ['name', 'status', 'owner_id', 'data'];

    protected $casts = [
        'status' => TenantStatus::class,
        'data'   => 'array',
    ];

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'status',
            'owner_id',
        ];
    }
    
    public function tenantUsers()
    {
        return $this->hasMany(TenantUser::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'tenant_users')
        ->using(TenantUser::class)
        ->withPivot('role')
        ->withTimestamps();
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function customers() { return $this->hasMany(Customer::class); }
    public function domain() { return $this->hasOne(Domain::class); }
    public function products() { return $this->hasMany(Product::class); }
    public function orders() { return $this->hasMany(Order::class); }
    public function locations() { return $this->hasMany(Location::class); }
    public function carts() { return $this->hasMany(Cart::class); }
    public function categories() { return $this->hasMany(Category::class); }
    public function settings() { return $this->hasOne(TenantSetting::class); }
    public function subscriptions() { return $this->hasMany(TenantSubscription::class); }
    public function payments() { return $this->hasMany(Payment::class); }

    // internal name
    public function getSlugAttribute(): string
    {
        return $this->name;
    }

    // public facing store name
    public function getNameAttribute($value): string
    {
        return $this->settings?->shop_name ?? $value;
    }

    public function getLogoUrlAttribute($value): ?string
    {
        return $this->settings?->logo_url ?? $value;
    }

    public function getFaviconUrlAttribute($value): ?string
    {
        return $this->settings?->favicon_url ?? $value;
    }

    public function getLanguage(string $default = 'ar'): string
    {
        return $this->settings?->language ?? $default;
    }

    public function resolvedTheme()
    {
        if ($this->settings?->theme) {
            return $this->settings->theme;
        }
 
        $default = Theme::where('is_default', true)->first();
        if ($default) {
            return $default;
        }
 
        $new = new Theme();
        $new->forceFill([
            'name'      => 'System Default',
            'icon_pack' => Theme::defaultIconPack(),
            'currency'  => Theme::defaultCurrency(),
            'palette'   => Theme::defaultPalette(),
            'font'      => Theme::defaultFont(),
            'buttons'   => Theme::defaultButtons(),
            'glows'     => Theme::defaultGlows(),
            'corners'   => Theme::defaultCorners(),
        ]);
 
        return $new;
    }
}