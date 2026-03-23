<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ tenant('logo_url')? asset('storage/' . tenant('logo_url')) : asset('images/logo.svg') }}">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Tajawal', sans-serif; }</style>
    <title>{{ $title ?? tenant('name') }}</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
 
    @livewireStyles
</head>
<body class="bg-gray-50">

<header class="container mb-6 pt-6 sm:pt-10 flex flex-row-reverse items-center justify-between">
    <div class="flex flex-col items-center">
        <div class="flex flex-row gap-4">
            <h1 class="text-4xl font-extrabold text-gray-900">{{ tenant('name') }}</h1>
            <img class="h-8" src="{{ tenant('logo_url')? asset('storage/' . tenant('logo_url')) : asset('images/logo.svg') }}" />
        </div>
        <p class="text-lg text-gray-600 mt-2">تصفح جميع منتجاتنا</p>
    </div>
    
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