<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class TenantSetting extends Model
{
    use HasUuids;
    use BelongsToTenant;
    
    protected $fillable = [
        'shop_name',
        'slogan',
        'logo_url',
        'favicon_url',
        'contact_email',
        'contact_phone',
        'currency',
        'currency_decimals',
        'theme_id',
        'supported_languages',
        'default_language',
        'guest_checkout_enabled',
    ];
    
    protected $casts = [
        'supported_languages' => 'array',
        'currency_decimals' => 'integer',
        'guest_checkout_enabled' => 'boolean',
    ];

    public function theme() { return $this->belongsTo(Theme::class); }
}