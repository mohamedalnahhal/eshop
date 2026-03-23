<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Theme extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'palette', 'font'];

    protected $casts = [
        'palette' => 'array',
    ];
}