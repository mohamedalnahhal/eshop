<x-layouts::auth>
<x-slot name="title">{{ __('Create Account') }} — {{ tenant('name') }}</x-slot>

<div class="min-h-[60vh] flex items-center justify-center py-12">
    <div class="w-full max-w-md">

        {{-- Brand --}}
        <div class="text-center mb-8">
            <a href="{{ route('shop.index') }}" class="inline-flex flex-col items-center gap-3">
                <img
                    src="{{ tenant('logo_url') ? asset('storage/' . tenant('logo_url')) : asset('images/logo.svg') }}"
                    class="w-16 h-16 object-contain"
                    alt="{{ tenant('name') }}"
                >
                <span class="text-theme-2xl font-bold text-theme">{{ tenant('name') }}</span>
            </a>
            <p class="text-muted text-theme-sm mt-2">{{ __('Join us today and start shopping!') }}</p>
        </div>

        {{-- Card --}}
        <div class="card p-8">
            <h1 class="text-theme-xl font-bold text-theme mb-6 text-center">{{ __('Create Account') }}</h1>

            {{-- <div class="flex flex-col gap-3 mb-6">
                <a href="{{ route('shop.auth.social.redirect', ['locale' => app()->getLocale(), 'provider' => 'google']) }}"
                   class="btn w-full border border-border-input bg-card-bg hover:bg-surface-100 text-theme text-theme-sm gap-3 transition-colors">
                    <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    {{ __('Continue with Google') }}
                </a>
            </div> --}}

            {{-- <div class="relative flex items-center gap-3 mb-6">
                <div class="flex-1 h-px bg-border"></div>
                <span class="text-theme-xs text-muted font-medium">{{ __('or sign up with email') }}</span>
                <div class="flex-1 h-px bg-border"></div>
            </div> --}}

            {{-- Registration form --}}
            <form action="{{ route('shop.signup.store', ['locale' => app()->getLocale()]) }}" method="POST" class="flex flex-col gap-5">
                @csrf

                {{-- Name --}}
                <div class="flex flex-col gap-1.5">
                    <label for="name" class="text-theme-sm font-semibold text-theme">
                        {{ __('Full Name') }}
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        autocomplete="name"
                        autofocus
                        placeholder="{{ __('Your name') }}"
                        class="input w-full {{ $errors->has('name') ? 'border-danger!' : '' }}"
                    >
                    @error('name')
                        <span class="text-danger text-theme-xs">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="flex flex-col gap-1.5">
                    <label for="email" class="text-theme-sm font-semibold text-theme">
                        {{ __('Email Address') }}
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        placeholder="{{ __('you@example.com') }}"
                        class="input w-full {{ $errors->has('email') ? 'border-danger!' : '' }}"
                    >
                    @error('email')
                        <span class="text-danger text-theme-xs">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="flex flex-col gap-1.5">
                    <label for="password" class="text-theme-sm font-semibold text-theme">
                        {{ __('Password') }}
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="new-password"
                        placeholder="password"
                        class="input w-full {{ $errors->has('password') ? 'border-danger!' : '' }}"
                    >
                    @error('password')
                        <span class="text-danger text-theme-xs">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="flex flex-col gap-1.5">
                    <label for="password_confirmation" class="text-theme-sm font-semibold text-theme">
                        {{ __('Confirm Password') }}
                    </label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        autocomplete="new-password"
                        placeholder="password"
                        class="input w-full"
                    >
                </div>

                <x-primary-button type="submit" class="mt-1">
                    {{ __('Create Account') }}
                </x-primary-button>
            </form>
        </div>

        {{-- Switch to login --}}
        <p class="text-center text-theme-sm text-muted mt-6">
            {{ __('Already have an account?') }}
            <a href="{{ route('shop.login', ['locale' => app()->getLocale()]) }}"
               class="text-primary font-semibold hover:underline">
                {{ __('Sign in') }}
            </a>
        </p>

    </div>
</div>
</x-layouts::app>