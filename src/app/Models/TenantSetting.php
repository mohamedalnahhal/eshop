<?php

namespace App\Models;
use App\Models\Traits\HasUuid;

use Illuminate\Database\Eloquent\Model;

class TenantSetting extends Model
{
    use HasUuid;
    protected $primaryKey = 'setting_id';
    protected $fillable = ['tenant_id', 'key', 'value'];
}
