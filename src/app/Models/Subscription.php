<?php

namespace App\Models;
use App\Models\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasUuid;
    protected $primaryKey = 'subscription_id';
    protected $fillable = ['name', 'price', 'duration_days'];
}
