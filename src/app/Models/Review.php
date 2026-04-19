<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\Auth;

class Review extends Model
{
    use HasUuids;

    protected $fillable = ['product_id', 'customer_id', 'rating', 'comment'];
    
    protected $casts = [
        'rating' => 'integer',
    ];

    public function wasEdited()
    {
        return $this->updated_at->diffInSeconds($this->created_at) > 1;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function votes()
    {
        return $this->hasMany(ReviewVote::class);
    }

    public function helpfulCount(): int
    {
        return $this->votes()->where('is_helpful', true)->count();
    }

    public function userVote(): ?bool
    {
        $vote = $this->votes()->where('customer_id', Auth::id())->first();
        return $vote?->is_helpful;
    }
}
