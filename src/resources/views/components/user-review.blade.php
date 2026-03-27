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
<div class="bg-white rounded-xl border border-blue-100 shadow-sm p-5 ring-1 ring-blue-200">
    <div class="flex justify-between items-start mb-2 gap-2">
        <div class="flex flex-col gap-2">
            <div class="flex items-start gap-3">
                <img
                    src="{{ $userReview->user->avatar_url }}"
                    alt="{{ $userReview->user->username }}"
                    class="w-6 h-6 rounded-full object-cover ring-2 ring-blue-100"
                />
                <div class="flex flex-col gap-1">
                    <h4 class="text-gray-900 leading-none">{{ $userReview->user->username }}</h4>
                    <span class="w-fit text-xs bg-blue-50 text-blue-600 font-medium px-2 py-0.5 rounded-full">تقييمك</span>
                </div>
            </div>
            <x-simple-rating-stars :rating="$userReview->rating"/>
        </div>

        <div class="flex flex-row gap-3">
            <p class="text-sm text-gray-600">{{ $userReview->helpfulCount() }} وجدوهُ مفيدًا</p>
            <button wire:click="startEdit"
                    class="p-1 -me-2 -mt-2 rounded-lg hover:bg-blue-50 text-gray-400 hover:text-blue-500 transition cursor-pointer"
                    title="تعديل تقييمك">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                     viewBox="2 1 22 20" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 112.828 2.828L11.828 15.828a2 2 0 01-1.414.586H9v-2a2 2 0 01.586-1.414z"/>
                </svg>
            </button>
        </div>
    </div>

    @if($userReview->comment)
        <p class="text-gray-600 text-sm">{{ $userReview->comment }}</p>
    @endif

    <div class="flex items-center gap-2 mt-3">
        <span class="text-xs text-gray-400">
            {{ $userReview->created_at->locale(app()->getLocale())->diffForHumans() }}
        </span>
        @if($userReview->wasEdited())
            <span class="text-xs text-gray-400">·</span>
            <span class="text-xs text-gray-400 italic">
                تم التعديل {{ $userReview->updated_at->locale(app()->getLocale())->diffForHumans() }}
            </span>
        @endif
    </div>
</div>

@elseif($editing)
<div class="bg-white rounded-2xl border border-blue-100 shadow-sm p-6 ring-1 ring-blue-200">
    <h3 class="text-base font-bold text-gray-900 mb-4">تعديل تقييمك</h3>

    <div class="mb-4">
        <div class="flex gap-1" x-data="{ hovered: 0 }">
            @for($i = 1; $i <= 5; $i++)
                <button
                    wire:click="{{ $i }} === $wire.rating ? $set('rating', 0) : $set('rating', {{ $i }})"
                    @mouseenter="hovered = {{ $i }}"
                    @mouseleave="hovered = 0"
                    :class="{{ $i }} <= $wire.rating ? 'text-yellow-400' : (hovered >= {{ $i }} ? 'text-yellow-200' : 'text-gray-300')"
                    class="text-3xl transition-transform hover:scale-110 cursor-pointer leading-none">★</button>
            @endfor
        </div>
        @error('rating') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>

    <textarea wire:model="comment" rows="3"
              class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none text-sm mb-4"
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
                class="px-4 py-2.5 rounded-lg border border-gray-200 text-gray-700 bg-gray-100 hover:bg-gray-200 text-sm shadow-sm transition">
            إلغاء
        </button>
        <button wire:click="deleteReview(); $parent.loadStats?.(); $parent.$parent?.refreshReviews();"
                wire:confirm="هل تريد حذف تقييمك؟"
                wire:loading.class="opacity-75 pointer-events-none"
                wire:target="deleteReview"
                class="bg-red-500 text-white font-bold px-4 py-2.5 rounded-lg hover:bg-red-600 transition text-sm">
            <span wire:loading.remove wire:target="deleteReview">🗑</span>
            <x-spinner wire:loading wire:target="deleteReview" class="h-4 w-4" />
        </button>
    </div>
</div>

@else
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <h3 class="text-base font-bold text-gray-900 mb-4">أضف تقييمك</h3>

    @if(session('review_message'))
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded-lg mb-4 text-sm font-medium">
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
                    :class="{{ $i }} <= $wire.rating ? 'text-yellow-400' : (hovered >= {{ $i }} ? 'text-yellow-200' : 'text-gray-300')"
                    class="text-3xl transition-transform hover:scale-110 cursor-pointer leading-none">★</button>
            @endfor
        </div>
        @error('rating') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>

    <textarea wire:model="comment" rows="3"
              class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400 resize-none text-sm mb-4"
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