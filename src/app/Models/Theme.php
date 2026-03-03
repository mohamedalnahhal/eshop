<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Theme extends Model
{
    use HasUuids;
    use HasFactory;
    protected $fillable = ['name', 'palette', 'font'];

    protected $casts = [
        'palette' => 'array',
    ];
}