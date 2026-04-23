<section class="relative overflow-hidden rounded-card bg-primary/5 border border-primary/20 px-8 py-12 text-center">
  <div class="pointer-events-none absolute inset-0 bg-linear-to-br from-primary/5 to-transparent"></div>

  <div class="relative">
      <h2 class="text-theme-3xl font-black text-theme mb-3">{{ __('Promo Title') }}</h2>

      @if($section['show_subtitle'])
          <p class="text-muted text-theme-lg mb-6">{{ __('Promo Subtitle') }}</p>
      @endif

      @if($section['show_cta'])
          <a href="{{ $section['cta_url'] ?: route('shop.products') }}"
             wire:navigate
             class="btn btn-primary rounded-cta! shadow-glow! font-bold hover:opacity-75 hover:-translate-y-0.5 transition-all!">
              {{ __('Promo CTA') }}
              @icon('arrow-r', 'w-4 h-4 rtl:rotate-180')
          </a>
      @endif
  </div>
</section>