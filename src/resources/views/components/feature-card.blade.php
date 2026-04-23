{{--
  x-feature-card — Bento Box style card
  Props:
    icon, title, description, badge, bg, accent, badge_bg, size ('normal'|'large')
--}}
@props([
    'icon'        => 'zap',
    'title'       => '',
    'description' => '',
    'badge'       => '',
    'bg'          => 'bg-slate-50',
    'accent'      => 'text-slate-600',
    'badge_bg'    => 'bg-slate-100 text-slate-700',
    'size'        => 'normal',
])

@php
    $svgMap = [
        'palette' => '<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="13.5" cy="6.5" r=".5" fill="currentColor"/><circle cx="17.5" cy="10.5" r=".5" fill="currentColor"/><circle cx="8.5" cy="7.5" r=".5" fill="currentColor"/><circle cx="6.5" cy="12.5" r=".5" fill="currentColor"/><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"/></svg>',
        'credit-card' => '<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>',
        'truck'       => '<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 17H3a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11a2 2 0 0 1 2 2v3m0 0h3l3 4v3h-6m0-7H14"/><circle cx="7.5" cy="17.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/></svg>',
        'globe'       => '<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 1 0 20 14.5 14.5 0 0 1 0-20"/><path d="M2 12h20"/></svg>',
        'zap'         => '<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"/></svg>',
        'tag'         => '<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12.586 2.586A2 2 0 0 0 11.172 2H4a2 2 0 0 0-2 2v7.172a2 2 0 0 0 .586 1.414l8.704 8.704a2.426 2.426 0 0 0 3.42 0l6.58-6.58a2.426 2.426 0 0 0 0-3.42z"/><circle cx="7.5" cy="7.5" r="1.5" fill="currentColor"/></svg>',
    ];

    $svg      = $svgMap[$icon] ?? $svgMap['zap'];
    $colSpan  = $size === 'large' ? 'md:col-span-2' : '';
@endphp

<div {{ $attributes->merge(['class' => "group relative flex flex-col gap-5 p-7 rounded-[2rem] $bg $colSpan overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-black/5"]) }}>
    {{-- decorative blob --}}
    <div class="absolute -inset-e-8 -top-8 w-40 h-40 rounded-full opacity-[.07] bg-current {{ $accent }} pointer-events-none"></div>

    <div class="flex items-start justify-between">
        <div class="p-3 rounded-2xl bg-white/80 shadow-sm {{ $accent }}">{!! $svg !!}</div>
        @if($badge)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $badge_bg }}">{{ $badge }}</span>
        @endif
    </div>

    <div class="flex flex-col gap-2 flex-1">
        <h3 class="text-lg font-bold text-slate-900 leading-snug">{{ $title }}</h3>
        <p class="text-sm text-slate-500 leading-relaxed">{{ $description }}</p>
    </div>

    <div class="{{ $accent }} opacity-0 group-hover:opacity-100 transition-opacity self-end">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="rtl:rotate-180"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
    </div>
</div>