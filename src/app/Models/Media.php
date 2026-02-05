<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasUuid;

    protected $primaryKey = 'media_id';
    public $incrementing = false;
    protected $keyType = 'string';


    protected $fillable = [
        'model_type',
        'model_id',
        'file_path',
        'file_type',
        'file_size',
        'collection_name'
    ];

    public function model()
    {
        return $this->morphTo();
    }
}