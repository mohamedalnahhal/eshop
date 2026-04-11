<?php

namespace App\Services;

use App\Models\Review;
use App\Models\ReviewVote;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewService
{
    // TODO: Implement proper auth


    public function hasPurchased(string $productId)
    {
        return Order::where('user_id', Auth::id())
            ->whereHas('items', fn($q) => $q->where('product_id', $productId))
            ->where('status', 'completed')
            ->exists();
    }

    public function hasReviewed(string $productId)
    {
        return Review::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->exists();
    }

    private function incrementRating(string $productId, int $rating)
    {
        DB::statement('
            UPDATE products
            SET
                rating_sum    = rating_sum + ?,
                reviews_count = reviews_count + 1,
                avg_rating    = rating_sum / reviews_count
            WHERE id = ?
        ', [$rating, $productId]);
    }
    
    private function updateRating(string $productId, int $oldRating, int $rating)
    {
        DB::statement('
            UPDATE products
            SET
                rating_sum = rating_sum - ? + ?,
                avg_rating = rating_sum / reviews_count
            WHERE id = ?
        ', [$oldRating, $rating, $productId]);
    }

    private function decrementRating(string $productId, int $rating)
    {
        DB::statement('
            UPDATE products
            SET
                rating_sum = rating_sum - ?,
                reviews_count = reviews_count - 1,
                avg_rating = IF(reviews_count = 0, 0, rating_sum / reviews_count )
            WHERE id = ?
        ', [$rating, $productId]);
    }
    
    public function submit(string $productId, int $rating, ?string $comment)
    {
        // abort_unless($this->hasPurchased($productId), 403);
        // abort_if($this->hasReviewed($productId), 422);
    
        return DB::transaction(function () use ($productId, $rating, $comment) {
            $review = Review::create([
                'user_id'    => Auth::id(),
                'product_id' => $productId,
                'rating'     => $rating,
                'comment'    => $comment,
            ]);
        
            $this->incrementRating($productId, $rating);
            return $review;
        });

    }
    
    public function update(Review $review, int $rating, ?string $comment)
    {
        // abort_unless($review->user_id === Auth::id(), 403);

        DB::transaction(function () use ($review, $rating, $comment) {
            $oldRating = $review->rating;
            $review->update(['rating' => $rating, 'comment' => $comment]);
        
            $this->updateRating($review->product_id, $oldRating, $rating);
        });
    }
    
    public function delete(Review $review)
    {
        // abort_unless($review->user_id === Auth::id(), 403);
        
        DB::transaction(function () use ($review) {
            $productId = $review->product_id;
            $rating = $review->rating;
            $review->delete();
  
            $this->decrementRating($productId, $rating);
        });
    }

    public function vote(string $reviewId, bool $isHelpful)
    {
        ReviewVote::updateOrCreate(
            ['user_id' => Auth::id(), 'review_id' => $reviewId],
            ['is_helpful' => $isHelpful]
        );
    }

    public function removeVote(string $reviewId)
    {
        ReviewVote::where('user_id', Auth::id())
            ->where('review_id', $reviewId)
            ->delete();
    }
}