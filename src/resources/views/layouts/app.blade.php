<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @php
        $storeName = tenant('name') ?? '';
        $storeLogo = tenant('logo_url');
        $storeSlogan = tenant()->setting?->slogan;
        $storeFavicon = tenant()->setting?->favicon_url;
        $contactEmail = tenant()->setting?->contact_email;
        $contactPhone = tenant()->setting?->contact_phone;
        $theme = tenant()->resolvedTheme();

        $font = $theme->resolvedFont();
        $iconPack = $theme->resolvedIconPack();

        $primaryFamily = explode(',', $font['primary_family'])[0];
        $secondaryFamily = explode(',', $font['secondary_family'])[0];
        $googleFamilies = array_unique([$primaryFamily, $secondaryFamily]);
        $googleQuery = implode('&family=', array_map(
            fn($f) => str_replace(' ', '+', trim($f)) . ':wght@400;600;700',
            $googleFamilies
        ));
    @endphp

    <title>{{ $title ?? ($storeName ?? 'Store') }}</title>
    <link rel="icon" type="image/x-icon" 
        href="{{ $storeFavicon ? 
            asset('storage/' . $storeFavicon) : 
            ($storeLogo ? asset('storage/' . $storeLogo) : asset('images/logo.svg')) }}"
    >
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family={{ $googleQuery }}&display=swap" rel="stylesheet">
    
    @once
    <style>
        {{ $theme->toCssVars() }}
    </style>
    @endonce

    <style>
        *, *::before, *::after { box-sizing: border-box; }
 
        body {
            font-family: var(--font-primary);
            font-size: var(--font-size-base);
            font-weight: var(--font-weight-base);
            color: var(--color-text);
            background-color: var(--color-bg);
            line-height: var(--line-height);
            letter-spacing: var(--letter-spacing);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-secondary);
            font-weight: var(--font-weight-heading);
        }

        .bg-navbar    { background-color: var(--color-navbar); box-shadow: var(--shadow-navbar); }
        .bg-footer    { background-color: var(--color-footer); }
        .bg-surface   { background-color: var(--color-surface); }
        .border-theme { border-color: var(--color-border); }

        .text-primary  { color: var(--color-primary) !important; }
        .text-muted    { color: var(--color-text-muted) !important; }
        .bg-primary    { background-color: var(--color-primary) !important; }
        .bg-secondary  { background-color: var(--color-secondary) !important; }
        .bg-accent     { background-color: var(--color-accent) !important; }

        .card {
            background-color: var(--color-surface);
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-card);
            border: 1px solid var(--color-border);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.375rem;
            padding: var(--btn-py) var(--btn-px);
            font-weight: var(--btn-font-weight);
            border-radius: var(--btn-radius);
            box-shadow: var(--btn-shadow);
            text-transform: var(--btn-uppercase);
            cursor: pointer;
            transition: opacity 0.15s, box-shadow 0.15s;
        }
        .btn:hover { opacity: 0.9; }
        .btn-primary {
            background-color: var(--color-primary);
            color: #fff;
        }
        .btn-secondary {
            background-color: var(--color-secondary);
            color: #fff;
        }
        .btn-accent {
            background-color: var(--color-accent);
            color: #fff;
        }

        .input {
            border-radius: var(--radius-sm);
            border: 1px solid var(--color-border);
            box-shadow: var(--shadow-input);
            padding: 0.5rem 0.75rem;
            font-family: var(--font-primary);
            font-size: var(--font-size-base);
            color: var(--color-text);
            background-color: var(--color-surface);
            width: 100%;
        }
        .input:focus {
            outline: none;
            border-color: var(--color-primary);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.625rem;
            border-radius: var(--radius-full);
            font-size: 0.75rem;
            font-weight: 600;
        }

        .modal-box {
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-modal);
        }

        .rounded-sm   { border-radius: var(--radius-sm); }
        .rounded-md   { border-radius: var(--radius-md); }
        .rounded-lg   { border-radius: var(--radius-lg); }
        .rounded-xl   { border-radius: var(--radius-xl); }
        .rounded-full { border-radius: var(--radius-full); }
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