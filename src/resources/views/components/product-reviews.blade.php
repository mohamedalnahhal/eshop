<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Review;
use App\Models\Product;

new class extends Component
{
    use WithPagination;

    public Product $product;
    public ?Review $userReview = null;

    public bool $canReview = false;
    public string $reviewSort = 'latest';
    public array $ratingCounts = [];
    public float $avgRating = 0;
    public int $totalReviews = 0;

    public function mount(): void
    {
        $this->loadStats();
        // $this->canReview = Auth::check() && $service->hasPurchased($this->product->id);
        $this->canReview = true;
    }

    public function loadStats()
    {
        $this->ratingCounts = $this->product->reviews()
            ->selectRaw('rating, count(*) as cnt')
            ->groupBy('rating')
            ->pluck('cnt', 'rating')
            ->toArray();

        $this->userReview = auth()->check()
            ? $this->product->reviews()->where('user_id', auth()->id())->first()
            : null;
    }

    public function updatedReviewSort()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Review::where('product_id', $this->product->id)
            ->with(['user', 'votes']);

        if ($this->userReview) {
            $query->where('id', '!=', $this->userReview->id);
        }

        $reviews = match($this->reviewSort) {
            'highest' => $query->orderByDesc('rating'),
            'lowest'  => $query->orderBy('rating'),
            'helpful' => $query->withCount(['votes as helpful_count' => fn($q) => $q->where('is_helpful', true)])->orderByDesc('helpful_count'),
            default   => $query->latest(),
        };

        return $this->view([
            'reviews' => $reviews->paginate(5),
        ]);
    }
};
?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <div wire:loading.class="animate-pulse opacity-50" wire:target="loadStats" class="transition-all duration-200">
        <x-product-reviews-overview :avgRating="$product->avg_rating" :totalReviews="$product->reviews_count" :ratingCounts="$ratingCounts"/>
    </div>

    <div class="lg:col-span-2">
        <div class="flex items-center justify-between border-b border-border pb-4 mb-6">
            <h2 class="text-theme-2xl font-bold text-theme">آراء الزبائن</h2>
            <div class="relative">
                <select wire:model.live="reviewSort"
                        wire:loading.attr="disabled"
                        wire:target="reviewSort"
                        class="input w-auto text-muted cursor-pointer appearance-none disabled:opacity-50 disabled:cursor-not-allowed">
                    <option value="latest">الأحدث</option>
                    <option value="highest">الأعلى تقييماً</option>
                    <option value="lowest">الأدنى تقييماً</option>
                    <option value="helpful">الأكثر إفادة</option>
                </select>
                <div wire:loading wire:target="reviewSort" class="absolute -right-6 top-2">
                    <x-spinner class="h-4 w-4" />
                </div>
            </div>
        </div>

        <div wire:loading.class="opacity-50 pointer-events-none" 
            wire:target="reviewSort, gotoPage, nextPage, previousPage" 
            class="space-y-4 transition-all duration-200 relative">
            @auth
            @if($canReview)
                <livewire:user-review :product="$product" :userReview="$userReview" />
            @endif
            @endauth
            @forelse($reviews as $review)
                <livewire:review-item :review="$review" :key="'review-'.$review->id"/>
            @empty
                @if($userReview)
                <p class="text-center py-5 text-muted">تقييمك هو الوحيد، شكراً</p>
                @else
                <div class="text-center py-10 rounded-card border-2 border-dashed border-border">
                    <p class="text-muted">لا توجد تقييمات حتى الآن.</p>
                </div>
                @endif
            @endforelse
        </div>

        <div class="mt-6">{{ $reviews->links() }}</div>
    </div>
</div>