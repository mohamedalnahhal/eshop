<?php

namespace App\Models;

use App\Enums\PaymentProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;

class PaymentMethod extends Model
{
    use HasUuids;
    use BelongsToTenant;

    protected $fillable = [
        'provider',
        'name',
        'is_active',
        'config'
    ];

    protected $hidden = ['config'];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
        'provider' => PaymentProvider::class,
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}