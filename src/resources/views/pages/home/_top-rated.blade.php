<section>
  <div class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-3">
          <h2 class="text-theme-2xl font-bold text-theme">{{ __('Top Rated') }}</h2>
          @if(!empty($section['badge_label']))
              <span class="badge bg-gold-surface text-on-gold border border-gold">{{ __('★ Featured') }}</span>
          @endif
      </div>
      @if($section['show_view_all'] ?? true)
          <a href="{{ route('shop.products', ['sort' => 'top_rated']) }}" wire:navigate
             class="text-theme-sm font-semibold text-primary hover:opacity-75 transition-opacity flex items-center gap-1">
              {{ __('View all') }}
              @icon('chevron-r', 'w-4 h-4 rotate-180')
          </a>
      @endif
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
      @forelse($this->topRated as $product)
          <livewire:listing-product :product="$product" :key="'top-'.$product->id" />
      @empty
          <div class="col-span-full text-center py-16 card border-2 border-dashed border-border">
              <p class="text-theme-xl font-bold text-muted">{{ __('No products available') }}</p>
          </div>
      @endforelse
  </div>
</section>