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

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    @if(app()->getLocale() === 'ar')
        <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
    @else
        <link href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    @endif

    {{-- Tailwind (swap for compiled asset in production) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: {{ app()->getLocale() === 'ar'
                            ? "['Cairo','sans-serif']"
                            : "['Geist','sans-serif']" }},
                        display: {{ app()->getLocale() === 'ar'
                            ? "['Cairo','sans-serif']"
                            : "['Geist','sans-serif']" }},
                    },
                    colors: {
                        brand: {
                            50:  '#eff6ff',
                            100: '#dbeafe',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        },
                    },
                    borderRadius: {
                        '4xl': '2rem',
                    },
                },
            },
        }
    </script>

    @stack('styles')

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

    @stack('scripts')
</body>
</html>