<x-layouts::auth>
<x-slot name="title">{{ __('Sign In') }} — {{ tenant('name') }}</x-slot>

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
            <p class="text-muted text-theme-sm mt-2">{{ __('Welcome back! Sign in to your account.') }}</p>
        </div>

        {{-- Card --}}
        <div class="card p-8">
            <h1 class="text-theme-xl font-bold text-theme mb-6 text-center">{{ __('Sign In') }}</h1>

            {{-- Social errors --}}
            @if($errors->has('social'))
                <div class="bg-danger/10 border border-danger/30 text-danger rounded-theme-md px-4 py-3 mb-6 text-theme-sm">
                    {{ $errors->first('social') }}
                </div>
            @endif

            {{-- Social login buttons --}}
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
                </a> --}}

                {{-- Uncomment to add more providers:
                <a href="{{ route('shop.auth.social.redirect', ['locale' => app()->getLocale(), 'provider' => 'facebook']) }}"
                   class="btn w-full border border-border-input bg-card-bg hover:bg-surface-100 text-theme text-theme-sm gap-3 transition-colors">
                    <svg class="w-5 h-5 shrink-0 text-[#1877F2]" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    {{ __('Continue with Facebook') }}
                </a>
                --}}
            {{-- </div> --}}

            {{-- Divider --}}
            {{-- <div class="relative flex items-center gap-3 mb-6">
                <div class="flex-1 h-px bg-border"></div>
                <span class="text-theme-xs text-muted font-medium">{{ __('or sign in with email') }}</span>
                <div class="flex-1 h-px bg-border"></div>
            </div> --}}

            {{-- Email / Password form --}}
            <form action="{{ route('shop.login.store', ['locale' => app()->getLocale()]) }}" method="POST" class="flex flex-col gap-5">
                @csrf

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
                        autofocus
                        placeholder="{{ __('you@example.com') }}"
                        class="input w-full {{ $errors->has('email') ? 'border-danger!' : '' }}"
                    >
                    @error('email')
                        <span class="text-danger text-theme-xs">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="flex flex-col gap-1.5">
                    <div class="flex items-center justify-between">
                        <label for="password" class="text-theme-sm font-semibold text-theme">
                            {{ __('Password') }}
                        </label>
                        {{-- Uncomment when forgot-password is implemented:
                        <a href="{{ route('shop.password.request', ['locale' => app()->getLocale()]) }}"
                           class="text-theme-xs text-primary hover:underline">
                            {{ __('Forgot password?') }}
                        </a>
                        --}}
                    </div>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="current-password"
                        placeholder="password"
                        class="input w-full {{ $errors->has('password') ? 'border-danger!' : '' }}"
                    >
                    @error('password')
                        <span class="text-danger text-theme-xs">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="flex items-center gap-2">
                    <input
                        type="checkbox"
                        id="remember"
                        name="remember"
                        class="w-4 h-4 accent-primary cursor-pointer"
                        {{ old('remember') ? 'checked' : '' }}
                    >
                    <label for="remember" class="text-theme-sm text-muted cursor-pointer select-none">
                        {{ __('Remember me') }}
                    </label>
                </div>

                <x-primary-button type="submit" class="mt-1">
                    {{ __('Sign In') }}
                </x-primary-button>
            </form>
        </div>

        {{-- Switch to signup --}}
        <p class="text-center text-theme-sm text-muted mt-6">
            {{ __("Don't have an account?") }}
            <a href="{{ route('shop.signup', ['locale' => app()->getLocale()]) }}"
               class="text-primary font-semibold hover:underline">
                {{ __('Create one') }}
            </a>
        </p>

    </div>
</div>
</x-layouts::app>