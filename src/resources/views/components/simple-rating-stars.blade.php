@props(['rating'])

<div class="flex text-theme-xl">
  @for($i = 1; $i <= 5; $i++)
      <span class="{{ $i <= $rating ? 'text-gold' : 'text-surface-300' }}">★</span>
  @endfor
</div>