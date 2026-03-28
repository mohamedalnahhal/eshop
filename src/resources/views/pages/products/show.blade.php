<?php

use Livewire\Component;
use App\Models\Product;
use App\Models\Review;
use App\Services\CartService;
use App\Services\ReviewService;
use Illuminate\Support\Facades\Auth;

new class extends Component
{
    public Product $product;

    public function mount(string $id)
    {
        $this->product = Product::with(['categories', 'media', 'reviews.user'])->findOrFail($id);
    }
    
    public function refreshReviews()
    {
        $this->product->refresh();
    }

    public function addToCart(CartService $cartService)
    {
        $cartService->add($this->product->id);

        $this->dispatch('cart-updated');

        session()->flash('success', 'تمت إضافة المنتج إلى السلة بنجاح! 🛒');
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

<div wire:loading.class="animate-pulse opacity-50" wire:target="refreshReviews" class="mb-12 transition-all duration-200">
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

            <x-rating-stars :rating="$product->avg_rating" :reviewsCount="$product->reviews_count" size="large" />

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

            <x-primary-button 
                wire:click="addToCart" 
                wire:loading.class="opacity-75 pointer-events-none"
                :disabled="$product->stock == 0"
                class="mt-12 text-lg font-bold py-3 px-6 rounded-xl">
                <span wire:loading.remove wire:target="addToCart">
                    إضافة للسلة 🛒
                </span>
            
                <div wire:loading wire:target="addToCart" >
                    <span class="flex flex-row flex-nowrap items-center gap-2">
                        <x-spinner class="h-4 w-4" />
                        جاري الإضافة...
                    </span>
                </div>
            </x-primary-button>
        </div>
    </div>
</div>

{{-- <livewire:product-reviews :product="$product"/> --}}

@script
<script>
    window.zoomLens = function () {
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
@endscript

</div>

