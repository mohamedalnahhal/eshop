<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TenantSetting extends Model
{
    use HasUuids;
    use BelongsToTenant;
    use HasFactory;
    protected $fillable = ['tenant_id', 'language', 'theme_id'];

    public function tenant() { return $this->belongsTo(Tenant::class); }
    public function theme() { return $this->belongsTo(Theme::class); }
}
