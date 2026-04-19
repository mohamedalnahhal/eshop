@props(['title'])

<div x-data="{ open: true }" class="border border-gray-100 dark:border-gray-800 rounded-xl overflow-hidden">
    <button @click="open = !open"
            class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors text-start">
        <span class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">{{ $title }}</span>
        <div ::class="open ? 'rotate-180' : ''">
            @icon('chevron-r', 'w-4 h-4 text-gray-400 transition-transform duration-200 rotate-90')
        </div>
    </button>
    <div x-show="open" x-collapse class="p-3 space-y-2 bg-white dark:bg-gray-900">
        {{ $slot }}
    </div>
</div>