<section class="relative overflow-hidden rounded-card shadow-glow px-8 py-14 md:px-16 md:py-20">
  <div class="pointer-events-none absolute -top-20 -inset-e-20 w-72 h-72 rounded-theme-full bg-primary/10 blur-3xl"></div>
  <div class="pointer-events-none absolute -bottom-16 -inset-s-16 w-56 h-56 rounded-theme-full bg-primary/10 blur-3xl"></div>

  <div class="relative flex flex-col md:flex-row items-center gap-10">
      <div class="flex-1">
          <h1 class="text-theme-4xl md:text-theme-5xl font-black text-theme leading-tight mb-4">
              {{ __('Hero Title') }}
          </h1>

          <p class="text-muted text-theme-lg sm:text-theme-xl mb-8 max-w-md me-auto">
              {{ __('Hero Subtitle') }}
          </p>

          <div class="flex flex-wrap gap-3 justify-center md:justify-start">
              <a href="{{ route('shop.products') }}"
                 wire:navigate
                 class="btn btn-primary hover:opacity-75 font-bold rounded-cta! transition-all! shadow-glow! hover:-translate-y-0.5">
                  {{ __('CTA Primary Label') }}
                  @icon('arrow-r', 'w-4 h-4 rtl:rotate-180')
              </a>
              <a href="#categories"
                 class="btn bg-surface-200 hover:bg-surface-300 text-theme font-bold rounded-cta! transition-all! hover:-translate-y-0.5">
                  {{ __('CTA Secondary Label') }}
              </a>
          </div>
      </div>
  </div>
</section>