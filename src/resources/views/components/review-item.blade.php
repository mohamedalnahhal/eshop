<?php

use Livewire\Component;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public Review $review;
    public bool $canVote = true;

    public function vote(ReviewService $service, bool $isHelpful): void
    {
        abort_if(!Auth::check(), 403);

        $existing = $this->review->votes()
            ->where('user_id', Auth::id())
            ->first();

        if ($existing && $existing->is_helpful === $isHelpful) {
            $service->removeVote($this->review->id);
        } else {
            $service->vote($this->review->id, $isHelpful);
        }

        $this->review->unsetRelation('votes');
        $this->review->load('votes');
    }
};
?>

<div class="card p-5">
    <div class="flex justify-between items-start mb-2 gap-2">
        <div class="flex flex-col gap-2">
            <div class="flex items-center gap-2">
                <img
                    src="{{ $review->user->avatar_url }}"
                    alt="{{ $review->user->username }}"
                    class="w-6 h-6 rounded-full object-cover"
                />
                <h4 class="text-theme font-normal! leading-none">{{ $review->user->username }}</h4>
            </div>
            <x-simple-rating-stars :rating="$review->rating"/>
        </div>
        <p class="text-sm text-muted">{{ $review->helpfulCount() }} وجدوهُ مفيدًا</p>
    </div>

    @if($review->comment)
        <p class="text-sm text-muted">{{ $review->comment }}</p>
    @endif

    <div class="flex items-end justify-between mt-3">
        <div class="flex items-center gap-2">
            <span class="text-xs text-muted">
                {{ $review->created_at->locale(tenant()->getLanguage())->diffForHumans() }}
            </span>
            @if($review->wasEdited())
                <span class="text-xs text-muted">·</span>
                <span class="text-xs text-muted italic">
                    تم التعديل {{ $review->updated_at->locale(tenant()->getLanguage())->diffForHumans() }}
                </span>
            @endif
        </div>

        @auth
        @if($canVote)
            <div class="flex items-center gap-3 text-sm text-muted">
                <span>مفيد؟</span>
                <button wire:click="vote(true)"
                        class="flex items-center gap-1 px-2 py-1 rounded-icon transition hover:bg-success/10
                            {{ $review->userVote() === true ? 'text-success font-bold' : '' }}">
                    👍
                </button>
                <button wire:click="vote(false)"
                        class="flex items-center gap-1 px-2 py-1 rounded-icon transition hover:bg-danger/10
                            {{ $review->userVote() === false ? 'text-danger font-bold' : '' }}">
                    👎
                </button>
            </div>
        @endif
        @endauth
    </div>
</div>