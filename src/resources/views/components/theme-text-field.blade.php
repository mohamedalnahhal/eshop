@props(['label', 'placeholder' => ''])

<div class="flex items-center gap-2 py-1">
    <label class="w-28 flex-shrink-0 text-xs text-gray-600 dark:text-gray-400 leading-tight">{{ $label }}</label>
    <input type="text"
           {{ $attributes->only(['wire:model', 'wire:model.live']) }}
           placeholder="{{ $placeholder }}"
           class="flex-1 text-xs bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-2.5 py-1.5 text-gray-700 dark:text-gray-300 font-mono focus:outline-none focus:ring-1 focus:ring-primary-400 transition-shadow"
    />
</div>
<!-- It uses CSS values ​​like 1.25rem, 0.625rem, 1.6, and normal—any value without specific options.
Both components are working correctly and don't need to be changed. -->