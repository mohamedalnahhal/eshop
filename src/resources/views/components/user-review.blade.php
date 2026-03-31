<?php

use Livewire\Component;
use App\Services\ReviewService;
use App\Models\Product;
use App\Models\Review;
use Livewire\Attributes\Validate;

new class extends Component
{
    public Product $product;
    public ?Review $userReview = null;

    // Review form state
    #[Validate('required|integer|between:1,5')]
    public int $rating = 0;
    #[Validate('nullable|string|max:1000')]
    public string $comment = '';
    public bool $editing = false;

    public function startEdit()
    {
        $this->rating = $this->userReview->rating;
        $this->comment = $this->userReview->comment ?? '';
        $this->editing = true;
    }

    public function cancelEdit()
    {
        $this->editing = false;
    }

    public function submitReview(ReviewService $service)
    {
        $this->validate();
        
        if ($this->editing) {
            $service->update($this->userReview, $this->rating, $this->comment ?: null);
            session()->flash('review_message', 'تم تحديث تقييمك!');

            $this->userReview?->refresh();
            $this->editing = false;
        }
        else
        {
            $this->userReview = $service->submit($this->product->id, $this->rating, $this->comment ?: null);
            session()->flash('review_message', 'تم إرسال تقييمك!');
        }
    }

    public function deleteReview(ReviewService $service)
    {
        $service->delete($this->userReview);

        $this->userReview = null;
        $this->editing = false;

        session()->flash('review_message', 'تم حذف تقييمك.');
    }
};
?>

<div
{{ $attributes->merge([
    'class' => ''
]) }}>
@if($userReview && !$editing)
<div class="card p-5 outline-3 outline-primary/20 border-primary/30">
    <div class="flex justify-between items-start mb-2 gap-2">
        <div class="flex flex-col gap-2">
            <div class="flex items-center max-sm:items-start gap-2">
                <img
                    src="{{ $userReview->user->avatar_url }}"
                    alt="{{ $userReview->user->username }}"
                    class="w-6 h-6 rounded-theme-full object-cover"
                />
                <div class="flex flex-row gap-2 max-sm:flex-col max-sm:gap-1">
                    <h4 class="text-theme font-normal! leading-none">{{ $userReview->user->username }}</h4>
                    <span class="w-fit badge bg-primary/10 text-primary text-xs! font-medium!">تقييمك</span>
                </div>
            </div>
            <x-simple-rating-stars :rating="$userReview->rating"/>
        </div>

        <div class="flex flex-row gap-3">
            <p class="text-sm text-muted">{{ $userReview->helpfulCount() }} وجدوهُ مفيدًا</p>
            <button wire:click="startEdit"
                    class="p-2 -me-2 -mt-2 rounded-icon bg-surface-100 hover:bg-primary/10 text-muted hover:text-primary! transition cursor-pointer"
                    title="تعديل تقييمك">
                @icon('pen', 'w-4 h-4')
            </button>
        </div>
    </div>

    @if($userReview->comment)
        <p class="text-muted text-sm">{{ $userReview->comment }}</p>
    @endif

    <div class="flex items-center gap-2 mt-3">
        <span class="text-xs text-muted">
            {{ $userReview->created_at->locale(tenant()->getLanguage())->diffForHumans() }}
        </span>
        @if($userReview->wasEdited())
            <span class="text-xs text-muted">·</span>
            <span class="text-xs text-muted italic">
                تم التعديل {{ $userReview->updated_at->locale(tenant()->getLanguage())->diffForHumans() }}
            </span>
        @endif
    </div>
</div>

@elseif($editing)
<div class="card p-5 outline-3 outline-primary/20 border-primary/30">
    <h3 class="text-base font-bold text-theme mb-2">تعديل تقييمك</h3>

    <div class="mb-4">
        <div class="flex gap-1" x-data="{ hovered: 0 }">
            @for($i = 1; $i <= 5; $i++)
                <button
                    wire:click="{{ $i }} === $wire.rating ? $set('rating', 0) : $set('rating', {{ $i }})"
                    @mouseenter="hovered = {{ $i }}"
                    @mouseleave="hovered = 0"
                    :class="{{ $i }} <= $wire.rating ? 'text-gold' : (hovered >= {{ $i }} ? 'text-gold/50' : 'text-surface-300')"
                    class="text-3xl transition-transform hover:scale-110 cursor-pointer leading-none">★</button>
            @endfor
        </div>
        @error('rating') <span class="text-danger text-xs mt-1">{{ $message }}</span> @enderror
    </div>

    <textarea wire:model="comment" rows="3"
              class="input w-full resize-none text-sm mb-2"
              placeholder="اكتب رأيك في المنتج هنا..."></textarea>

    <div class="flex gap-2">
        <x-primary-button 
            wire:click="submitReview(); $parent.loadStats?.(); $parent.$parent?.refreshReviews();"
            wire:loading.class="opacity-75 pointer-events-none"
            wire:target="submitReview"
            >
            <span wire:loading.remove wire:target="submitReview">تحديث التقييم</span>
            <div wire:loading wire:target="submitReview">
                <span class="flex flex-row flex-nowrap items-center gap-2">
                    <x-spinner class="h-4 w-4" />
                    جاري التحديث...
                </span>
            </div>
        </x-primary-button>
        <button wire:click="cancelEdit"
                class="btn flex-1 text-center bg-surface-200 hover:bg-surface-300 text-theme text-sm">
            إلغاء
        </button>
        <button wire:click="deleteReview(); $parent.loadStats?.(); $parent.$parent?.refreshReviews();"
                wire:confirm="هل تريد حذف تقييمك؟"
                wire:loading.class="opacity-75 pointer-events-none"
                wire:target="deleteReview"
                class="btn flex-1 text-center bg-danger hover:opacity-75! text-bg text-sm">
            <div wire:loading.remove wire:target="deleteReview">
                @icon('trash', 'w-5 h-5')
            </div>
            <x-spinner wire:loading wire:target="deleteReview" class="h-4 w-4" />
        </button>
    </div>
</div>

@else
<div class="card p-5">
    <h3 class="text-base font-bold text-theme mb-2">أضف تقييمك</h3>

    @if(session('review_message'))
        <div class="bg-success/10 text-success px-4 py-2 rounded-lg mb-4 text-sm font-medium">
            {{ session('review_message') }}
        </div>
    @endif

    <div class="mb-4">
        <div class="flex gap-1" x-data="{ hovered: 0 }">
            @for($i = 1; $i <= 5; $i++)
                <button
                    wire:click="{{ $i }} === $wire.rating ? $set('rating', 0) : $set('rating', {{ $i }})"
                    @mouseenter="hovered = {{ $i }}"
                    @mouseleave="hovered = 0"
                    :class="{{ $i }} <= $wire.rating ? 'text-gold' : (hovered >= {{ $i }} ? 'text-gold/50' : 'text-surface-300')"
                    class="text-3xl transition-transform hover:scale-110 cursor-pointer leading-none">★</button>
            @endfor
        </div>
        @error('rating') <span class="text-danger text-xs mt-1">{{ $message }}</span> @enderror
    </div>

    <textarea wire:model="comment" rows="3"
              class="input w-full resize-none text-sm mb-2"
              placeholder="اكتب رأيك في المنتج هنا..."></textarea>

    <x-primary-button 
        wire:click="submitReview(); $parent.loadStats?.(); $parent.$parent?.refreshReviews();"
        wire:loading.class="opacity-75 pointer-events-none"
        wire:target="submitReview">
        <span wire:loading.remove wire:target="submitReview">إرسال التقييم</span>
        <div wire:loading wire:target="submitReview">
            <span class="flex flex-row flex-nowrap items-center gap-2">
                <x-spinner class="h-4 w-4" />
                جاري الإرسال...
            </span>
        </div>
    </x-primary-button>
</div>
@endif
</div>