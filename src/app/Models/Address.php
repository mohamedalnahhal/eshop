<?php

namespace App\Models;

use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasUuid;

    protected $primaryKey = 'address_id';
    protected $fillable = ['user_id', 'type', 'address_line_1', 'city', 'state', 'postal_code', 'country'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
