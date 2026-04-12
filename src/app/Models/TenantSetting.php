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
        'language',
        'currency',
        'theme_id',
        'supported_languages',
        'default_language',
    ];
    
    protected $casts = [
        'supported_languages' => 'array',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function theme() { return $this->belongsTo(Theme::class); }
}