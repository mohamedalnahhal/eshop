<?php

use Livewire\Component;
use App\Models\Product;
use App\Models\Review;
use App\Services\CartService;
use App\Services\ReviewService;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    use WithPagination;

    public Product $product;

    // Review form state
    public int $rating = 0;
    public string $comment = '';
    public ?string $editingReviewId = null;
    public string $reviewSort = 'latest';

    protected function rules()
    {
        return [
            'rating'  => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
        ];
    }

    public function mount(string $id)
    {
        $this->product = Product::with(['categories', 'media', 'reviews.user'])->findOrFail($id);

        if (Auth::check()) {
            $existing = Review::where('user_id', Auth::id())
                ->where('product_id', $this->product->id)
                ->first();

            if ($existing) {
                $this->editingReviewId = $existing->id;
                $this->rating  = $existing->rating;
                $this->comment = $existing->comment ?? '';
            }
        }
    }

    public function addToCart(CartService $cartService)
    {
        $cartService->add($this->product->id);

        $this->dispatch('cart-updated');

        session()->flash('success', 'تمت إضافة المنتج إلى السلة بنجاح! 🛒');
    }

    public function submitReview(ReviewService $service)
    {
        $this->validate();

        if ($this->editingReviewId) {
            $review = Review::findOrFail($this->editingReviewId);
            $service->update($review, $this->rating, $this->comment ?: null);
            session()->flash('review_message', 'تم تحديث تقييمك!');
        } else {
            $service->submit($this->product->id, $this->rating, $this->comment ?: null);
            session()->flash('review_message', 'تم إرسال تقييمك!');
        }

        $this->product->refresh();
    }

    public function deleteReview(ReviewService $service)
    {
        $review = Review::findOrFail($this->editingReviewId);
        $service->delete($review);

        $this->editingReviewId = null;
        $this->rating  = 0;
        $this->comment = '';
        $this->product->refresh();

        session()->flash('review_message', 'تم حذف تقييمك.');
    }

    public function vote(int $reviewId, bool $isHelpful, ReviewService $service)
    {
        abort_if(!Auth::check(), 403);

        $existing = ReviewVote::where('user_id', Auth::id())
            ->where('review_id', $reviewId)->first();

        if ($existing && $existing->is_helpful === $isHelpful) {
            $service->removeVote($reviewId);
        } else {
            $service->vote($reviewId, $isHelpful);
        }
    }

    public function updatedReviewSort(): void { $this->resetPage(); }

    public function render(ReviewService $service)
    {
        $reviewQuery = Review::where('product_id', $this->product->id)->with(['user', 'votes']);

        $reviews = (match($this->reviewSort) {
            'highest' => $reviewQuery->orderByDesc('rating'),
            'lowest'  => $reviewQuery->orderBy('rating'),
            'helpful' => $reviewQuery->withCount(['votes as helpful_count' => fn($q) => $q->where('is_helpful', true)])->orderByDesc('helpful_count'),
            default   => $reviewQuery->latest(),
        })->paginate(5);

        return view('pages.products.show', [
            // 'canReview' => Auth::check() && $service->hasPurchased($this->product->id),
            'canReview' => true,
            'reviews' => $reviews,
            'avgRating' => $this->product->avg_rating,
            'totalReviews' => Review::where('product_id', $this->product->id)->count(),
        ]);
    }
};
?>

<x-slot name="top">
    <x-breadcrumbs :links="[
        'المنتجات' => route('shop.products'),
        'تفاصيل المنتج' => null,
    ]" />
</x-slot>

<div>

<div class="mb-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start"
        x-data="{
            activeImage: '{{ $product->media->first() ? asset('storage/' . $product->media->first()->file_path) : 'https://via.placeholder.com/600x600?text=No+Image' }}',
            activeTab: 'details'
        }">
        <div class="lg:col-span-7 bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col md:flex-row-reverse gap-4">
            @if($product->media->count() > 0)
                <div class="flex-1 bg-gray-50 max-h-125 rounded-xl overflow-hidden relative border border-gray-100 aspect-square flex items-center justify-center cursor-crosshair"
                     x-data="zoomLens()"
                     @mousemove="onMove($event)"
                     @mouseleave="active = false"
                     x-ref="container">

                    <img :src="activeImage"
                         alt="{{ $product->name }}"
                         class="max-w-full max-h-125 object-contain"
                         x-ref="img" />

                    <div x-show="active"
                         class="absolute pointer-events-none rounded-full border-2 border-white shadow-lg overflow-hidden"
                         :style="`
                           width: ${lensW}px;
                           height: ${lensH}px;
                           left: ${lx}px;
                           top: ${ly}px;
                           background-image: url(${activeImage});
                           background-repeat: no-repeat;
                           background-size: ${bgW}px ${bgH}px;
                           background-position: ${bgX}px ${bgY}px;
                         `">
                    </div>

                    <div class="absolute top-4 right-4">
                        <span class="bg-white/90 backdrop-blur px-4 py-2 rounded-full text-xs font-bold shadow-sm border border-gray-100">تكبير 🔍</span>
                    </div>
                </div>
                @if($product->media->count() > 1)
                    <div class="flex md:flex-col gap-3 overflow-x-auto md:overflow-y-auto max-h-125 custom-scrollbar pb-2 pr-2">
                        @foreach($product->media as $media)
                            <button @click="activeImage = '{{ asset('storage/' . $media->file_path) }}'" 
                                    class="relative shrink-0 w-20 h-20 rounded-xl border-2 overflow-hidden transition-all duration-300"
                                    :class="activeImage === '{{ asset('storage/' . $media->file_path) }}' ? 'border-blue-600 ring-4 ring-blue-50' : 'border-transparent hover:border-blue-200'">
                                <img src="{{ asset('storage/' . $media->file_path) }}" class="w-full h-full object-cover"/>
                            </button>
                        @endforeach
                    </div>
                @endif
            @else
                <div class="w-full h-96 flex flex-col items-center justify-center text-gray-400">
                    <span class="text-6xl mb-4">📷</span>
                    <p>لا توجد صورة لهذا المنتج</p>
                </div>
            @endif
        </div>
        <div class="lg:col-span-5 py-2 flex flex-col gap-6 h-full justify-start">
   
            @if($product->categories->isNotEmpty())
                <span class="bg-white/90 backdrop-blur-md px-4 py-1.5 rounded-2xl text-[10px] font-black text-blue-600 shadow-sm border border-white/50 uppercase">
                    {{ $product->categories->first()->name }}
                </span>
            @endif

            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900">{{ $product->name }}</h1>

            @if($totalReviews > 0)
                <x-rating-stars :rating="$avgRating" :reviewsCount="$totalReviews" size="large" />
            @endif

            <div class="flex items-center gap-4">
                <div class="text-5xl font-black text-blue-600">
                    ${{ number_format($product->price, 2) }}
                </div>
                @if($product->stock > 0)
                    <div class="bg-green-100 text-green-700 px-3 py-1 rounded-lg text-sm font-bold">
                        متاح : ({{ $product->stock }}) 🟢
                    </div>
                @else
                    <div class="bg-red-100 text-red-700 px-3 py-1 rounded-lg text-sm font-bold">
                        نفذ من المتجر
                    </div>
                @endif
            </div>

            <div>
                <h3 class="text-lg font-bold mb-2 text-gray-800">وصف المنتج:</h3>
                <p class="text-gray-600 leading-relaxed">
                    {{ $product->description ?? 'لا يوجد وصف متاح لهذا المنتج حالياً.' }}
                </p>
            </div>

            <div class="mt-12">
                <button wire:click="addToCart" {{ $product->stock == 0 ? 'disabled' : '' }} class="w-full bg-blue-600 text-white disabled:bg-gray-300 disabled:text-gray-700 text-lg font-bold py-3 px-6 rounded-xl hover:bg-blue-700 transition not-disabled:shadow-lg not-disabled:hover:shadow-xl flex justify-center items-center gap-2 disabled:cursor-not-allowed">
                    إضافة للسلة 🛒
                </button>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
    <div class="lg:col-span-2">
        <div class="flex items-center justify-between border-b border-gray-300 pb-4 mb-6">
            <h2 class="text-2xl font-bold text-gray-900">
                آراء الزبائن ({{ $totalReviews }})
            </h2>
            <select wire:model.live="reviewSort"
                    class="text-sm border border-gray-100 rounded-lg px-3 py-1.5 outline-none focus:ring-4 focus:ring-blue-50 shadow-sm bg-white text-gray-600">
                <option value="latest">الأحدث</option>
                <option value="highest">الأعلى تقييماً</option>
                <option value="lowest">الأدنى تقييماً</option>
                <option value="helpful">الأكثر إفادة</option>
            </select>
        </div>

        <div class="space-y-4">
            @forelse($reviews as $review)
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="flex justify-between items-center mb-3">
                        <h4 class="font-bold text-lg">{{ $review->user->name }}</h4>
                        <div class="flex text-lg">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="{{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                            @endfor
                        </div>
                    </div>

                    @if($review->comment)
                        <p class="text-gray-600">{{ $review->comment }}</p>
                    @endif

                    <div class="flex items-center justify-between mt-4">
                        <span class="text-xs text-gray-400">{{ $review->created_at->locale('ar')->diffForHumans() }}</span>

                        @auth
                            <div class="flex items-center gap-3 text-sm text-gray-500">
                                <span>مفيد؟</span>
                                <button wire:click="vote({{ $review->id }}, true)"
                                        class="flex items-center gap-1 px-2 py-1 rounded-lg transition hover:bg-green-50
                                               {{ $review->userVote() === true ? 'text-green-600 font-bold' : '' }}">
                                    👍 {{ $review->helpfulCount() }}
                                </button>
                                <button wire:click="vote({{ $review->id }}, false)"
                                        class="flex items-center gap-1 px-2 py-1 rounded-lg transition hover:bg-red-50
                                               {{ $review->userVote() === false ? 'text-red-500 font-bold' : '' }}">
                                    👎
                                </button>
                            </div>
                        @endauth
                    </div>
                </div>
            @empty
                <div class="text-center py-10 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                    <p class="text-gray-500">لا توجد تقييمات حتى الآن. كن أول من يقيّم هذا المنتج!</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">{{ $reviews->links() }}</div>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100 h-fit">
        <h3 class="text-xl font-bold text-gray-900 mb-6 text-center">
            {{ $editingReviewId ? 'تعديل تقييمك' : 'أضف تقييمك' }}
        </h3>

        @if(session('review_message'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded-lg mb-4 text-sm font-medium">
                {{ session('review_message') }}
            </div>
        @endif

        @auth
            @if($canReview)
                <div class="mb-4">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        التقييم <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-1" x-data="{ hovered: 0 }">
                        @for($i = 1; $i <= 5; $i++)
                            <button
                                wire:click="{{ $i }} === $wire.rating ? $set('rating', 0) : $set('rating', {{ $i }})"
                                @mouseenter="hovered = {{ $i }}"
                                @mouseleave="hovered = 0"
                                :class="{{ $i }} <= $wire.rating? 'text-yellow-300' :
                                    (hovered >= {{ $i }} ? 'text-gray-400'
                                    : 'text-gray-300')"
                                class="text-3xl transition hover:scale-110 cursor-pointer">
                                ★
                            </button>
                        @endfor
                    </div>
                    @error('rating')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">رأيك (اختياري)</label>
                    <textarea wire:model="comment" rows="4"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="اكتب رأيك في المنتج هنا..."></textarea>
                </div>

                    <div class="flex gap-2">
                        <button wire:click="submitReview"
                                class="grow bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition shadow-md">
                            {{ $editingReviewId ? 'تحديث التقييم' : 'إرسال التقييم' }}
                        </button>

                    @if($editingReviewId)
                        <button wire:click="deleteReview"
                                wire:confirm="هل تريد حذف تقييمك؟"
                                class="bg-red-500 text-white font-bold px-4 py-3 rounded-lg hover:bg-red-600 transition">
                            🗑
                        </button>
                    @endif
                </div>
            @else
                <p class="text-center text-sm text-gray-500 bg-gray-50 p-4 rounded-lg">
                    يمكن فقط للمشترين الذين أتموا الشراء كتابة تقييم.
                </p>
            @endif
        @else
            <p class="text-center text-sm text-gray-500">
                <a href="" class="text-blue-600 underline font-medium">سجّل دخولك</a>
                لترك تقييم.
            </p>
        @endauth
    </div>
</div>
</div>

<script>
    function zoomLens() {
      return {
        active: false,
        lx: 0, ly: 0,
        lensW: 150, lensH: 150,
        bgX: 0, bgY: 0, bgW: 0, bgH: 0,
        zoom: 3,

        onMove(e) {
          this.active = true;
          const img = this.$refs.img;
          const rect = this.$refs.container.getBoundingClientRect();

          const cx = e.clientX - rect.left;
          const cy = e.clientY - rect.top;

          // clamp lens inside container
          this.lx = Math.max(0, Math.min(cx - this.lensW / 2, rect.width  - this.lensW));
          this.ly = Math.max(0, Math.min(cy - this.lensH / 2, rect.height - this.lensH));

          // actual rendered size of image (object-contain adds letterboxing)
          const scale = Math.min(rect.width / img.naturalWidth, rect.height / img.naturalHeight);
          const rendW = img.naturalWidth  * scale;
          const rendH = img.naturalHeight * scale;

          // image offset inside container (centered)
          const offX = (rect.width  - rendW) / 2;
          const offY = (rect.height - rendH) / 2;

          // scale up the rendered image by zoom factor
          this.bgW = rendW * this.zoom;
          this.bgH = rendH * this.zoom;

          // position so the zoomed region is centered under the cursor
          this.bgX = -((cx - offX) * this.zoom - this.lensW / 2);
          this.bgY = -((cy - offY) * this.zoom - this.lensH / 2);
        }
      }
    }
</script>