<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class CategoryTranslation extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $fillable = [
        'locale',
        'name',
    ];
}