<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ \App\Helpers\LocaleHelper::isRtl(app()->getLocale()) ? 'rtl' : 'ltr' }}">
<head>
    @include('storefront.partials.head')
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
    
        <div class="flex items-center gap-m-header-gap sm:gap-header-gap shrink-0">
            <x-locale-switcher />
            <livewire:header-auth />
            <livewire:cart-icon />
        </div>
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

<x-footer />

@livewireScripts
</body>
</html>