<section id="categories">
  <div class="flex items-center justify-between mb-6">
      <h2 class="text-theme-2xl font-bold text-theme">{{ __('Categories') }}</h2>
      @if($section['show_view_all'] ?? true)
          <a href="{{ route('shop.products') }}" wire:navigate
             class="text-theme-sm font-semibold text-primary hover:opacity-75 transition-colors flex items-center gap-1">
              {{ __('View all') }}
              @icon('chevron-r', 'w-4 h-4 rtl:rotate-180')
          </a>
      @endif
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
      @forelse($this->featuredCategories as $category)
          <div class="card h-fit overflow-hidden">
              <a href="{{ route('shop.products', ['category' => $category->id]) }}"
                 class="flex items-center gap-3 px-5 py-4 hover:bg-surface-100 transition-colors group">
                  <div class="w-10 h-10 rounded-icon bg-primary/10 text-primary group-hover:bg-primary/15 flex items-center justify-center text-theme-xl shrink-0 transition-colors">
                      @icon('tag', 'w-5 h-5')
                  </div>
                  <div class="flex-1 min-w-0">
                      <span class="font-bold text-theme group-hover:text-primary! transition-colors">
                          {{ $category->name }}
                      </span>
                      <span class="block text-theme-xs text-muted">{{ $category->products_count }} {{ __('Products') }}</span>
                  </div>
                  @icon('chevron-r', 'w-4 h-4 text-muted group-hover:text-primary! rtl:rotate-180 shrink-0 transition-colors')
              </a>

              @if($category->children->isNotEmpty())
                  <div class="border-t border-border-muted px-5 py-3 flex flex-wrap gap-2">
                      @foreach($category->children as $child)
                          <a href="{{ route('shop.products', ['category' => $child->id]) }}"
                             class="badge bg-bg text-muted border border-border hover:bg-primary/10 hover:text-primary! hover:border-primary/30 transition-all">
                              {{ $child->name }}
                              <span class="opacity-60">{{ $child->products_count }}</span>
                          </a>
                      @endforeach
                  </div>
              @endif
          </div>
      @empty
          <p class="col-span-full text-center text-muted py-8">{{ __('No categories found') }}</p>
      @endforelse
  </div>
</section>