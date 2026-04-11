<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), ['ar']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @php
        $storeName = tenant('name') ?? '';
        $storeLogo = tenant('logo_url');
        $storeSlogan = tenant()->settings?->slogan;
        $storeFavicon = tenant()->settings?->favicon_url;
        $contactEmail = tenant()->settings?->contact_email;
        $contactPhone = tenant()->settings?->contact_phone;
        $theme = tenant()->resolvedTheme();

        $theme_font = $theme->resolvedFont();
        $iconPack = $theme->resolvedIconPack();

        $primaryFamily = explode(',', $theme_font['primary_family'])[0];
        $secondaryFamily = explode(',', $theme_font['secondary_family'])[0];
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

        .header    { 
            background-color: var(--color-header);
            color: var(--color-on-header);
            box-shadow: var(--shadow-header);
        }
        .border-theme { border-color: var(--color-border); }
        .text-theme  { color: var(--color-text); }
        .text-muted    { color: var(--color-text-muted); }

        .card {
            background-color: var(--color-card-bg);
            border-radius: var(--radius-card);
            box-shadow: var(--shadow-card);
            border: 1px solid var(--color-border-muted);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.375rem;
            padding: var(--btn-py) var(--btn-px);
            font-weight: var(--btn-font-weight);
            border-radius: var(--radius-btn);
            box-shadow: var(--shadow-btn);
            text-transform: var(--btn-uppercase);
            cursor: pointer;
            transition-property: all;
            transition-timing-function: var(--tw-ease, var(--default-transition-timing-function));
            transition-duration: var(--tw-duration, var(--default-transition-duration));
        }
        .btn:hover { opacity: 0.9; }
        .btn:active { scale: 0.95; }
        .btn-primary {
            background-color: var(--color-primary);
            color: var(--color-on-primary);
        }
        .btn-secondary {
            background-color: var(--color-secondary);
            color: var(--color-on-secondary);
        }
        .btn-accent {
            background-color: var(--color-accent);
            color: var(--color-on-accent);
        }

        .input {
            border-radius: var(--radius-input);
            border: 1px solid var(--color-border-input);
            box-shadow: var(--shadow-input);
            padding: var(--input-py) var(--input-px);
            transition-property: all;
            transition-timing-function: var(--tw-ease, var(--default-transition-timing-function));
            transition-duration: var(--tw-duration, var(--default-transition-duration));
        }
        .input:focus {
            border-color: var(--color-primary) !important;
            outline-color: color-mix(in oklab, var(--color-primary) 10%, transparent);
            outline-width: 4px;
            outline-style: solid;
        }
        .header-input {
            border: 1px solid var(--color-border-input-m-header) !important;
            box-shadow: none !important;
            padding: var(--m-header-search-py) var(--m-header-search-px) !important;
        }
        .header-input:focus {
            border-color: var(--color-on-m-header) !important;
            outline-color: color-mix(in oklab, var(--color-on-m-header) 10%, transparent) !important;
        }
        @media (width >= 40rem) {
            .header-input {
                border: 1px solid var(--color-border-input-header) !important;
                box-shadow: none !important;
                padding: var(--header-search-py) var(--header-search-px) !important;
            }
            .header-input:focus {
                border-color: var(--color-on-header) !important;
                outline-color: color-mix(in oklab, var(--color-on-header) 10%, transparent) !important;
            }
        }

        .badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
            padding: 0.125rem 0.75rem;
            border-radius: var(--radius-badge);
            font-size: 0.875rem;
            font-weight: 700;
        }

        .modal-box {
            border-radius: var(--radius-model);
            box-shadow: var(--shadow-modal);
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
 
    @livewireStyles
</head>

<body class="text-theme-base">
<header class="sm:w-header-w sm:pt-header-pt sm:pb-header-pb sm:mt-header-mt sm:mb-header-mb sm:[position:var(--header-position)] sm:top-header-st left-0 right-0 z-50 mx-auto sm:rounded-header sm:border-b-header-bb sm:border-t-header-bt sm:border-l-header-bl sm:border-r-header-br sm:border-border-header sm:bg-header sm:backdrop-blur-header sm:shadow-header sm:text-on-header
               w-m-header-w pt-m-header-pt pb-m-header-pb mt-m-header-mt mb-m-header-mb [position:var(--m-header-position)] top-m-header-st rounded-m-header border-b-m-header-bb border-t-m-header-bt border-l-m-header-bl border-r-m-header-br border-border-m-header bg-m-header backdrop-blur-m-header shadow-m-header text-on-m-header">
    <div class="flex flex-row gap-m-header-gap sm:gap-header-gap items-center justify-between
                sm:w-header-content-w sm:mr-header-content-mr sm:ml-header-content-ml sm:pl-header-content-pl sm:pr-header-content-pr
                w-m-header-content-w mr-m-header-content-mr ml-m-header-content-ml pl-m-header-content-pl pr-m-header-content-pr">
        <div class="flex flex-col items-center">
            <a class="flex flex-row gap-2 items-center" href="{{ route('shop.index') }}">
                <img class="sm:w-header-logo-w sm:h-header-logo-h w-m-header-logo-w h-m-header-logo-h object-contain" src="{{ tenant('logo_url') ? asset('storage/' . tenant('logo_url')) : asset('images/logo.svg') }}" />
                <h1 class="sm:text-header-title sm:font-header-title sm:text-on-header text-m-header-title font-m-header-title text-on-m-header text-nowrap">{{ tenant('name') }}</h1>
            </a>
        </div>
    
        <div class="grow">
            {{ $header ?? '' }}
        </div>
    
        <x-locale-switcher />
        <livewire:cart-icon />
    </div>
</header>
</header>
@if(session('success'))
    <div class="theme-container max-w-4xl bg-success/10 border border-success/60 text-success py-3 rounded-theme-md relative mb-6 text-center shadow-sm">
        <strong class="font-bold">رائع!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
@endif
@if(session('error'))
    <div class="theme-container max-w-4xl bg-danger/10 border border-danger/60 text-danger py-3 rounded-theme-md relative mb-6 text-center shadow-sm">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
@endif

{{ $top ?? '' }}

<div class="theme-container">
    <main>
        {{ $slot }}
    </main>
</div>

@livewireScripts
</body>
</html>