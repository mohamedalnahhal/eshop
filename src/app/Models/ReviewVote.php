<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ReviewVote extends Model
{
    use HasUuids;

    protected $fillable = ['customer_id', 'review_id', 'is_helpful'];

    public function review() { return $this->belongsTo(Review::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
}