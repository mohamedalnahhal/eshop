@props(['rating', 'reviewsCount', 'size'=>'small'])

<div class="flex items-center gap-2">
  <div class="flex items-center gap-0.5">
      @for($i = 1; $i <= 5; $i++)
          @php
              $fill = min(1, max(0, $rating - ($i - 1)));
              $percent = round($fill * 100);
          @endphp
          <span class="relative inline-block {{ $size === 'large'? 'text-theme-3xl' : 'text-theme-2xl' }} leading-none">
              <span class="text-surface-300">★</span>
              <span class="absolute inset-0 overflow-hidden text-gold"
                    style="width: {{ $percent }}%">★</span>
          </span>
      @endfor
  </div>
  <span class="{{ $size === 'large'? 'text-theme-base' : 'text-theme-sm' }} text-muted">({{ $reviewsCount }} {{ __('Reviews') }})</span>
</div>