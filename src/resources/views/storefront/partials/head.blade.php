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

@vite(['resources/css/app.css', 'resources/js/app.js'])

@livewireStyles