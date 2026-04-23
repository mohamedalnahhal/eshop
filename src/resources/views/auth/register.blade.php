@component('layouts.guest')
    @slot('title')
        {{ $isAr ? 'أنشئ متجرك — eShop' : 'Create Your Store — eShop' }}
    @endslot

@php $isAr = $isAr ?? (app()->getLocale() === 'ar'); @endphp

<div class="min-h-screen flex items-center justify-center px-4 py-16"
     style="background: linear-gradient(135deg,#f8faff 0%,#eef2ff 100%)">

    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2">
                <img src="{{ asset('images/logo.svg') }}" alt="eShop" class="h-9 w-auto" />
                <span class="text-2xl font-bold text-slate-900">eShop</span>
            </a>
            <p class="text-slate-500 mt-2 text-sm">
                {{ $isAr ? 'أنشئ متجرك وابدأ البيع اليوم' : 'Create your store and start selling today' }}
            </p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 p-8">

            <h1 class="text-xl font-bold text-slate-900 mb-6">
                {{ $isAr ? 'إنشاء حساب جديد' : 'Create your account' }}
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

            <form method="POST" action="{{ route('merchant.register.store') }}" class="space-y-5">
                @csrf

                {{-- Personal Info --}}
                <div class="grid grid-cols-1 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            {{ $isAr ? 'الاسم الكامل' : 'Full Name' }}
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-400 @enderror"
                               placeholder="{{ $isAr ? 'محمد أحمد' : 'John Doe' }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            {{ $isAr ? 'البريد الإلكتروني' : 'Email Address' }}
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-400 @enderror"
                               placeholder="{{ $isAr ? 'you@example.com' : 'you@example.com' }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            {{ $isAr ? 'كلمة المرور' : 'Password' }}
                        </label>
                        <input type="password" name="password" required
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-400 @enderror"
                               placeholder="••••••••">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            {{ $isAr ? 'تأكيد كلمة المرور' : 'Confirm Password' }}
                        </label>
                        <input type="password" name="password_confirmation" required
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="••••••••">
                    </div>
                </div>

                {{-- Divider --}}
                <div class="relative my-2">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-100"></div>
                    </div>
                    <div class="relative flex justify-center">
                        <span class="bg-white px-3 text-xs text-slate-400">
                            {{ $isAr ? 'معلومات المتجر' : 'Store Information' }}
                        </span>
                    </div>
                </div>

                {{-- Store Info --}}
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">
                        {{ $isAr ? 'اسم المتجر' : 'Store Name' }}
                    </label>
                    <input type="text" name="shop_name" value="{{ old('shop_name') }}" required
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('shop_name') border-red-400 @enderror"
                           placeholder="{{ $isAr ? 'متجر الأناقة' : 'My Awesome Store' }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">
                        {{ $isAr ? 'رابط المتجر' : 'Store URL' }}
                    </label>
                    <div class="flex items-center rounded-xl border border-slate-200 overflow-hidden focus-within:ring-2 focus-within:ring-blue-500 @error('subdomain') border-red-400 @enderror">
                        <input type="text" name="subdomain" value="{{ old('subdomain') }}" required
                               class="flex-1 px-4 py-2.5 text-sm focus:outline-none min-w-0"
                               placeholder="{{ $isAr ? 'my-store' : 'my-store' }}">
                        <span class="px-3 py-2.5 text-sm text-slate-400 bg-slate-50 border-s border-slate-200 whitespace-nowrap">
                            .{{ config('tenancy.central_domains')[0] }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">
                        {{ $isAr ? 'أحرف إنجليزية صغيرة وأرقام وشرطات فقط' : 'Lowercase letters, numbers and hyphens only' }}
                    </p>
                </div>

                <button type="submit"
                        class="w-full py-3 rounded-xl bg-blue-600 text-white font-semibold text-sm hover:bg-blue-700 transition-colors shadow-sm hover:shadow-md">
                    {{ $isAr ? 'أنشئ متجري' : 'Create My Store' }}
                    <span class="{{ $isAr ? 'me-1' : 'ms-1' }}">→</span>
                </button>
            </form>
        </div>

        <p class="text-center text-sm text-slate-500 mt-6">
            {{ $isAr ? 'لديك حساب بالفعل؟' : 'Already have an account?' }}
            <a href="{{ route('merchant.login') }}" class="text-blue-600 font-medium hover:underline">
                {{ $isAr ? 'تسجيل الدخول' : 'Sign In' }}
            </a>
        </p>
    </div>
</div>
@endcomponent
