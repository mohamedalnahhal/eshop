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

<div class="bg-white p-5 rounded-lg shadow-sm border border-gray-100">
    <div class="flex justify-between items-start mb-2 gap-2">
        <div class="flex flex-col gap-2">
            <div class="flex items-center gap-3">
                <img
                    src="{{ $review->user->avatar_url }}"
                    alt="{{ $review->user->username }}"
                    class="w-6 h-6 rounded-full object-cover ring-2 ring-gray-100"
                />
                <h4 class="text-gray-900 leading-none">{{ $review->user->username }}</h4>
            </div>
            <x-simple-rating-stars :rating="$review->rating"/>
        </div>
        <p class="text-sm text-gray-600">{{ $review->helpfulCount() }} وجدوهُ مفيدًا</p>
    </div>

    @if($review->comment)
        <p class="text-sm text-gray-600">{{ $review->comment }}</p>
    @endif

    <div class="flex items-end justify-between mt-3">
        <div class="flex items-center gap-2">
            <span class="text-xs text-gray-400">
                {{ $review->created_at->locale(app()->getLocale())->diffForHumans() }}
            </span>
            @if($review->wasEdited())
                <span class="text-xs text-gray-400">·</span>
                <span class="text-xs text-gray-400 italic">
                    تم التعديل {{ $review->edited_at->locale(app()->getLocale())->diffForHumans() }}
                </span>
            @endif
        </div>

        @auth
        @if($canVote)
            <div class="flex items-center gap-3 text-sm text-gray-500">
                <span>مفيد؟</span>
                <button wire:click="vote(true)"
                        class="flex items-center gap-1 px-2 py-1 rounded-lg transition hover:bg-green-50
                            {{ $review->userVote() === true ? 'text-green-600 font-bold' : '' }}">
                    👍
                </button>
                <button wire:click="vote(false)"
                        class="flex items-center gap-1 px-2 py-1 rounded-lg transition hover:bg-red-50
                            {{ $review->userVote() === false ? 'text-red-500 font-bold' : '' }}">
                    👎
                </button>
            </div>
        @endif
        @endauth
    </div>
</div>