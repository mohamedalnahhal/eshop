{{--
  x-button — reusable button/anchor component
  Props:
    type:     'primary' | 'secondary' | 'ghost'  (default: primary)
    href:     string  — renders <a> when provided
    size:     'sm' | 'md' | 'lg'                 (default: md)
    full:     bool                               (default: false)
    disabled: bool                               (default: false)
--}}
@props([
    'type'     => 'primary',
    'href'     => null,
    'size'     => 'md',
    'full'     => false,
    'disabled' => false,
])

@php
    $base  = 'inline-flex items-center justify-center gap-2 font-semibold rounded-xl transition-all duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 select-none cursor-pointer';

    $sizes = [
        'sm' => 'px-4 py-2 text-sm',
        'md' => 'px-6 py-3 text-sm',
        'lg' => 'px-8 py-4 text-base',
    ];

    $variants = [
        'primary'   => 'bg-blue-600 text-white hover:bg-blue-700 focus-visible:ring-blue-600 active:scale-[.98] shadow-sm hover:shadow-md',
        'secondary' => 'bg-white text-slate-800 border border-slate-200 hover:bg-slate-50 hover:border-slate-300 focus-visible:ring-slate-400 shadow-sm',
        'ghost'     => 'bg-transparent text-slate-700 hover:bg-slate-100 focus-visible:ring-slate-400',
    ];

    $cls = implode(' ', array_filter([
        $base,
        $sizes[$size]    ?? $sizes['md'],
        $variants[$type] ?? $variants['primary'],
        $full     ? 'w-full' : '',
        $disabled ? 'opacity-50 cursor-not-allowed pointer-events-none' : '',
    ]));
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $cls]) }}>{{ $slot }}</a>
@else
    <button {{ $attributes->merge(['class' => $cls, 'disabled' => $disabled]) }}>{{ $slot }}</button>
@endif