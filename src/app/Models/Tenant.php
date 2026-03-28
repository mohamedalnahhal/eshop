<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Domain;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use App\Enums\TenantStatus;
use App\Enums\UserRole;

class Tenant extends BaseTenant
{
    use HasUuids, HasDomains;

    protected $fillable = ['name', 'logo_url', 'status'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'tenant_users')
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

    public function domain() { return $this->hasOne(Domain::class); }
    public function products() { return $this->hasMany(Product::class); }
    public function orders() { return $this->hasMany(Order::class); }
    public function locations() { return $this->hasMany(Location::class); }
    public function carts() { return $this->hasMany(Cart::class); }
    public function categories() { return $this->hasMany(Category::class); }
    public function settings() { return $this->hasOne(TenantSetting::class); }
    public function subscriptions() { return $this->hasMany(TenantSubscription::class); }
    public function payments() { return $this->hasMany(Payment::class); }

    
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