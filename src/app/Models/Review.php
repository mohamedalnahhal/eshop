<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Facades\Auth;

class Review extends Model
{
    use HasUuids;

    protected $fillable = ['product_id', 'user_id', 'rating', 'comment'];

    public function wasEdited()
    {
        return $this->updated_at->notEqualTo($this->created_at);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
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
        $vote = $this->votes()->where('user_id', Auth::id())->first();
        return $vote?->is_helpful;
    }
}
