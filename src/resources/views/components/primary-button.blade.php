@props(['type' => 'submit'])

<button 
    {{ $attributes->merge([
        'type' => $type, 
        'class' => 'w-full bg-blue-600 text-white text-md font-bold py-2 rounded-lg hover:bg-blue-700 transition shadow-xl shadow-blue-100 active:scale-95 disabled:opacity-70 disabled:cursor-not-allowed flex justify-center items-center gap-2'
    ]) }}
>
    {{ $slot }}
</button>