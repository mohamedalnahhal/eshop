<section class="relative overflow-hidden rounded-card shadow-glow px-8 py-14 md:px-16 md:py-20">
  <div class="pointer-events-none absolute -top-20 -inset-e-20 w-72 h-72 rounded-theme-full bg-primary/10 blur-3xl"></div>
  <div class="pointer-events-none absolute -bottom-16 -inset-s-16 w-56 h-56 rounded-theme-full bg-primary/10 blur-3xl"></div>

  <div class="relative flex flex-col md:flex-row items-center gap-10">
      <div class="flex-1">
          <h1 class="text-theme-4xl md:text-theme-5xl font-black text-theme leading-tight mb-4">
              {{ $section['title'] }}
          </h1>

          @if(!empty($section['subtitle']))
              <p class="text-muted text-theme-lg sm:text-theme-xl mb-8 max-w-md me-auto">
                  {{ $section['subtitle'] }}
              </p>
          @endif

          <div class="flex flex-wrap gap-3 justify-center md:justify-start">
              @if(!empty($section['cta_primary_label']))
                  <a href="{{ route('shop.products') }}"
                     wire:navigate
                     class="btn btn-primary hover:opacity-75 font-bold rounded-cta! transition-all! shadow-glow! hover:-translate-y-0.5">
                      {{ $section['cta_primary_label'] }}
                      @icon('arrow-r', 'w-4 h-4 rotate-180')
                  </a>
              @endif
              @if(!empty($section['cta_secondary_label']))
                  <a href="#categories"
                     class="btn bg-surface-200 hover:bg-surface-300 text-theme font-bold rounded-cta! transition-all! hover:-translate-y-0.5">
                      {{ $section['cta_secondary_label'] }}
                  </a>
              @endif
          </div>
      </div>
  </div>
</section>