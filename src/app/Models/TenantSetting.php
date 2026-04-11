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
        'tenant_id',
        'language',
        'theme_id',
        'favicon_url',
        'slogan',
        'currency',
        'contact_email',
        'contact_phone',
        'supported_languages',
        'default_language',
    ];

    protected $casts = [
        'supported_languages' => 'array',
    ];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function theme() { return $this->belongsTo(Theme::class); }
}