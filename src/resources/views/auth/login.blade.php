@component('layouts.guest')
    @slot('title')
        {{ $isAr ? 'تسجيل الدخول — eShop' : 'Sign In — eShop' }}
    @endslot

@php $isAr = $isAr ?? (app()->getLocale() === 'ar'); @endphp

<div class="min-h-screen flex items-center justify-center px-4 py-16"
     style="background: linear-gradient(135deg,#f8faff 0%,#eef2ff 100%)">

    <div class="w-full max-w-sm">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2">
                <img src="{{ asset('images/logo.svg') }}" alt="eShop" class="h-9 w-auto" />
                <span class="text-2xl font-bold text-slate-900">eShop</span>
            </a>
            <p class="text-slate-500 mt-2 text-sm">
                {{ $isAr ? 'أدر متجرك من أي مكان' : 'Manage your store from anywhere' }}
            </p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-8">

            <h1 class="text-xl font-bold text-slate-900 mb-6">
                {{ $isAr ? 'تسجيل الدخول' : 'Sign In' }}
            </h1>

            @if($errors->any())
                <div class="mb-5 p-4 bg-red-50 border border-red-100 rounded-xl text-sm text-red-700">
                    <ul class="space-y-1 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('merchant.login.store') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">
                        {{ $isAr ? 'البريد الإلكتروني' : 'Email Address' }}
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-400 @enderror"
                           placeholder="you@example.com">
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-sm font-medium text-slate-700">
                            {{ $isAr ? 'كلمة المرور' : 'Password' }}
                        </label>
                    </div>
                    <input type="password" name="password" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-400 @enderror"
                           placeholder="••••••••">
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" id="remember" name="remember"
                           class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <label for="remember" class="text-sm text-slate-600">
                        {{ $isAr ? 'تذكرني' : 'Remember me' }}
                    </label>
                </div>

                <button type="submit"
                        class="w-full py-3 rounded-xl bg-blue-600 text-white font-semibold text-sm hover:bg-blue-700 transition-colors shadow-sm hover:shadow-md">
                    {{ $isAr ? 'الدخول إلى متجري' : 'Go to My Store' }}
                </button>
            </form>
        </div>

        <p class="text-center text-sm text-slate-500 mt-6">
            {{ $isAr ? 'ليس لديك متجر بعد؟' : "Don't have a store yet?" }}
            <a href="{{ route('merchant.register') }}" class="text-blue-600 font-medium hover:underline">
                {{ $isAr ? 'أنشئ متجرك مجاناً' : 'Start for Free' }}
            </a>
        </p>
    </div>
</div>
@endcomponent
