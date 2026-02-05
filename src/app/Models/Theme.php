<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasUuid;

    protected $primaryKey = 'theme_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'preview_image',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];


    public function tenants()
    {
        return $this->hasMany(Tenant::class, 'theme_id', 'theme_id');
    }
}