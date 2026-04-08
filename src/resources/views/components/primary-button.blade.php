@props(['type' => 'submit'])

<button 
    {{ $attributes->merge([
        'type' => $type, 
        'class' => 'w-full btn btn-primary hover:opacity-75 shadow-glow! disabled:shadow-none! disabled:bg-surface-300! disabled:text-text-muted! disabled:cursor-not-allowed flex justify-center items-center gap-2'
    ]) }}
>
    {{ $slot }}
</button>