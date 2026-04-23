<!DOCTYPE html>
<html
    lang="{{ app()->getLocale() }}"
    dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}"
    class="scroll-smooth"
>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="{{ $description ?? (app()->getLocale() === 'ar' ? 'eShop — منصة التجارة الإلكترونية متعددة المتاجر' : 'eShop — Multi-tenant E-commerce Platform') }}" />
    <title>{{ $title ?? 'eShop' }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/logo.svg') }}" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles

    <style>
        ::selection { background: #dbeafe; color: #1e3a5f; }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #f8fafc; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(22px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes floatY {
            0%,100% { transform: translateY(0); }
            50%      { transform: translateY(-8px); }
        }
        .afu   { animation: fadeUp .6s ease forwards; }
        .d1    { animation-delay:.10s; opacity:0; }
        .d2    { animation-delay:.20s; opacity:0; }
        .d3    { animation-delay:.30s; opacity:0; }
        .d4    { animation-delay:.40s; opacity:0; }
        .float { animation: floatY 4s ease-in-out infinite; }
        .float2{ animation: floatY 4s ease-in-out infinite; animation-delay:2s; }
    </style>
</head>
<body class="bg-white text-slate-900 font-sans antialiased">

    {{ $slot }}

    @livewireScripts
</body>
</html>