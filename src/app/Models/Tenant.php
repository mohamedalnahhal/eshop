<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Domain;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Enums\TenantStatus;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\UserRole;

class Tenant extends BaseTenant
{
    use HasUuids, HasDomains , SoftDeletes;

    protected $fillable = ['name', 'logo_url', 'status'];

    protected $dates = ['deleted_at'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'tenant_users')
        ->using(TenantUser::class)
        ->withPivot('role')
        ->withTimestamps();
    }

    public function owner()
    {
        return $this->users()->wherePivot('role', UserRole::TENANT_OWNER);
    }

    protected $casts = [
        'status' => TenantStatus::class,
    ];

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'status',
            'logo_url',
        ];
    }
    
    // internal name
    public function getSlugAttribute(): string
    {
        return $this->name;
    }

    // public facing store name
    public function getNameAttribute($value): string
    {
        return $this->settings?->store_name ?? $value;
    }

    public function domain() { return $this->hasOne(Domain::class); }
    public function products() { return $this->hasMany(Product::class); }
    public function orders() { return $this->hasMany(Order::class); }
    public function locations() { return $this->hasMany(Location::class); }
    public function carts() { return $this->hasMany(Cart::class); }
    public function categories() { return $this->hasMany(Category::class); }
    public function settings() { return $this->hasOne(TenantSetting::class); }
    public function subscriptions() { return $this->hasMany(TenantSubscription::class); }
    public function payments() { return $this->hasMany(Payment::class); }

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

    /**
     * Format a price amount using this theme's currency config.
     */
    public function formatPrice(float $amount)
    {
        $c = $this->resolvedTheme()->resolvedCurrency();
        $code = $this->settings?->currency ?? 'USD';
        $symbol = Theme::getSymbol($code);
        $decimals = $c['decimals'] ?? 2;
        $formatted = number_format($amount, $decimals);

        return $c['position'] === 'before'
            ? $symbol . $formatted
            : $formatted . ' ' . $symbol;
    }
}