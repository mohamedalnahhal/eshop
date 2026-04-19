<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ \App\Helpers\LocaleHelper::isRtl(app()->getLocale()) ? 'rtl' : 'ltr' }}">
<head>
    @include('partials.head')
</head>

<body class="text-theme-base">

<div class="theme-container">
    <main>
        {{ $slot }}
    </main>
</div>

@livewireScripts
</body>
</html>