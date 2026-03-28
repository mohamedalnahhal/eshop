<?php

use Livewire\Component;

new class extends Component
{
    // --- Store Info & Branding ---
    public string $storeName;
    public ?string $storeLogo;
    public ?string $storeFavicon; 
    public string $storeSlogan; 
    public string $contactEmail;
    public ?string $contactPhone;

    // --- Colors ---
    public string $primaryColor;
    public string $secondaryColor;
    public string $accentColor; 
    public string $backgroundColor;
    public string $textColor;
    public string $navbarColor; 
    public string $footerColor; 
    public string $borderColor; 

    // --- Typography ---
    public string $primaryFontFamily;
    public string $secondaryFontFamily;
    public string $baseFontSize;
    public string $h1FontSize; 
    public string $primaryFontWeight; 
    public string $headingFontWeight; 
    public string $lineHeight;
    public string $letterSpacing; 

    // --- UI Elements ---
    public string $buttonShapeClass;
    public string $cardShapeClass;
    public string $inputShapeClass;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ tenant('logo_url')? asset('storage/' . tenant('logo_url')) : asset('images/logo.svg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Tajawal', sans-serif; }</style>
    <title>{{ $title ?? tenant('name') }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', explode(',', $primaryFontFamily)[0]) }}&family={{ str_replace(' ', '+', explode(',', $secondaryFontFamily)[0]) }}&display=swap" rel="stylesheet">

    <style>
        :root {
            --color-primary: {{ $primaryColor }};
            --color-secondary: {{ $secondaryColor }};
            --color-accent: {{ $accentColor }};
            --color-bg: {{ $backgroundColor }};
            --color-text: {{ $textColor }};
            --color-navbar: {{ $navbarColor }};
            --color-footer: {{ $footerColor }};
            --color-border: {{ $borderColor }};

            --font-primary: {{ $primaryFontFamily }};
            --font-secondary: {{ $secondaryFontFamily }};
            --font-size-base: {{ $baseFontSize }};
            --font-size-h1: {{ $h1FontSize }};
            --font-weight-primary: {{ $primaryFontWeight }};
            --font-weight-heading: {{ $headingFontWeight }};
            --font-line-height: {{ $lineHeight }};
            --font-letter-spacing: {{ $letterSpacing }};
        }

        .tenant-body {
            font-family: var(--font-primary);
            font-size: var(--font-size-base);
            font-weight: var(--font-weight-primary);
            color: var(--color-text);
            background-color: var(--color-bg);
            line-height: var(--font-line-height);
            letter-spacing: var(--font-letter-spacing);
        }

        h1, h2, h3, h4, h5, h6, .font-heading {
            font-family: var(--font-secondary);
            font-weight: var(--font-weight-heading);
        }

        .bg-navbar { background-color: var(--color-navbar); }
        .bg-footer { background-color: var(--color-footer); }
        .border-custom { border-color: var(--color-border); }
        .text-primary-custom { color: var(--color-primary); }
        .bg-primary-custom { background-color: var(--color-primary); }
        .bg-accent-custom { background-color: var(--color-accent); }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
 
    @livewireStyles
</head>
<body class="bg-gray-50">

<header class="container mb-10 pt-8 lg:pt-10 flex flex-row gap-8 max-lg:gap-6 items-center justify-between">
    <div class="flex flex-col items-center">
        <a class="flex flex-row gap-2" href="{{route('shop.index')}}">
            <img class="h-10" src="{{ tenant('logo_url')? asset('storage/' . tenant('logo_url')) : asset('images/logo.svg') }}" />
            <h1 class="text-4xl text-nowrap font-extrabold text-gray-900">{{ tenant('name') }}</h1>
        </a>
    </div>
    
    {{ $header ?? '' }}

    <livewire:cart-icon />
</header>
@if(session('success'))
    <div class="container max-w-4xl bg-green-100 border border-green-400 text-green-700 py-3 rounded relative mb-6 text-center shadow-sm">
        <strong class="font-bold">رائع!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif
@if(session('error'))
    <div class="container max-w-4xl bg-red-100 border border-red-400 text-red-700 py-3 rounded relative mb-6 text-center shadow-sm">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
@endif

{{ $top ?? '' }}

<div class="container">
    <main>
        {{ $slot }}
    </main>
</div>

@livewireScripts
</body>
</html>