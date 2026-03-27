@props(['rating'])

<div class="flex text-xl">
  @for($i = 1; $i <= 5; $i++)
      <span class="{{ $i <= $rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
  @endfor
</div>