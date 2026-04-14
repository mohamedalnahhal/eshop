@props(['label', 'options' => []])

<div class="flex items-center gap-2 py-1">
    <label class="w-28 flex-shrink-0 text-xs text-gray-600 dark:text-gray-400">{{ $label }}</label>
    <select {{ $attributes->only(['wire:model', 'wire:model.live']) }}
            class="flex-1 text-xs bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-2.5 py-1.5 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-1 focus:ring-primary-400 transition-shadow">
        @foreach($options as $value => $optLabel)
            <option value="{{ $value }}">{{ $optLabel }}</option>
        @endforeach
    </select>
</div>