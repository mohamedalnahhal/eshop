<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ProductTranslation extends Model
{
    use HasUuids;

    public $timestamps = false;

    protected $fillable = [
        'locale',
        'name',
        'description',
    ];
}