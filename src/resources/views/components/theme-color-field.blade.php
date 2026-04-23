@props(['label'])

<div class="flex items-center gap-2 py-1"
     x-data="{ color: @entangle($attributes->wire('model')) }">
    <label class="flex-1 text-xs text-gray-600 dark:text-gray-400 truncate">{{ $label }}</label>
    <div class="flex items-center gap-1.5 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-2 py-1.5">
        <label class="cursor-pointer relative flex items-center">
            <input type="color" x-model="color" class="sr-only" />
            <div class="w-5 h-5 rounded-md border border-gray-300 dark:border-gray-600 shadow-inner shrink-0"
                 :style="`background-color: ${color}`"></div>
        </label>
        <input type="text"
               x-model="color"
               maxlength="9"
               class="w-20 text-xs bg-transparent text-gray-700 dark:text-gray-300 font-mono focus:outline-none uppercase"
               placeholder="#000000"
        />
    </div>
</div>
<!-- It's located in resources/views/components/theme-color-field.blade.php and is accessed in the editor
 and it is used for selecting and displaying color values in the theme editor -->