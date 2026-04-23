@component('layouts.guest')
    @slot('title')
        {{ app()->getLocale() === 'ar' ? 'eShop — أنشئ متجرك الآن' : 'eShop — Launch Your Shop Today' }}
    @endslot
@php $isAr = app()->getLocale() === 'ar'; @endphp

{{-- ═══════════════════════ NAVBAR ═══════════════════════ --}}
<header
    x-data="{ open: false, scrolled: false }"
    @scroll.window="scrolled = window.scrollY > 24"
    :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-sm' : 'bg-transparent'"
    class="fixed top-0 inset-x-0 z-50 transition-all duration-300"
>
<nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16 lg:h-20">

       {{-- ── Logo ── --}}
        <a href="/" class="flex items-center gap-2 group">
            <img src="{{ asset('images/logo.svg') }}" alt="eShop" class="h-8 w-auto" />
            <span class="text-xl font-bold text-slate-900">eShop</span>
        </a>
        {{-- ── Desktop nav links ── --}}
        @php
            $links = [
                ['#features', $isAr ? 'المميزات' : 'Features'],
                // ['#demo',     $isAr ? 'معاينة'   : 'Demo'],
                ['#pricing',  $isAr ? 'الأسعار'  : 'Pricing'],
            ];
        @endphp
        <div class="hidden lg:flex items-center gap-8">
            @foreach($links as [$href,$label])
                <a href="{{ $href }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">{{ $label }}</a>
            @endforeach
        </div>

        {{-- ── Actions ── --}}
        <div class="flex items-center gap-3">
            {{-- Language switch --}}
            <a href="{{ route('lang.switch', $isAr ? 'en' : 'ar') }}"
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 1 0 20 14.5 14.5 0 0 1 0-20"/><path d="M2 12h20"/></svg>
                {{ $isAr ? 'EN' : 'عربي' }}
            </a>

            <x-button type="ghost" size="sm" href="/login" class="hidden! sm:inline-flex!">
                {{ $isAr ? 'تسجيل الدخول' : 'Sign In' }}
            </x-button>

            <x-button type="primary" size="sm" href="/register" class="hidden! sm:inline-flex!">
                {{ $isAr ? 'أنشئ متجرك مجاناً' : 'Start for Free' }}
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="rtl:rotate-180"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </x-button>

            {{-- Mobile hamburger --}}
            <button @click="open=!open" class="lg:hidden p-2 rounded-xl text-slate-600 hover:bg-slate-100 transition-colors">
                <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="open"  xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="lg:hidden border-t border-slate-100 py-4 space-y-1">
        @foreach($links as [$href,$label])
            <a href="{{ $href }}" class="block px-3 py-2.5 rounded-xl text-sm text-slate-600 hover:bg-slate-100 transition-colors">{{ $label }}</a>
        @endforeach
        <div class="pt-3 border-t border-slate-100 flex flex-col gap-2 mt-2">
            <x-button type="secondary" href="/login" :full="true">{{ $isAr ? 'تسجيل الدخول' : 'Sign In' }}</x-button>
            <x-button type="primary"   href="/register" :full="true">{{ $isAr ? 'أنشئ متجرك مجاناً' : 'Start for Free' }}</x-button>
        </div>
    </div>
</nav>
</header>


{{-- ═══════════════════════ HERO ═══════════════════════ --}}
<section class="relative min-h-screen flex items-center overflow-hidden pt-20">
    {{-- Background blobs --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden" aria-hidden="true">
        <div class="absolute -top-24 -inset-s-24 w-140 h-140 rounded-full bg-blue-100/50" style="filter:blur(120px)"></div>
        <div class="absolute bottom-0 -inset-e-24 w-110 h-110 rounded-full bg-indigo-100/40" style="filter:blur(100px)"></div>
    </div>
    {{-- Dot grid --}}
    <div class="absolute inset-0 opacity-[.025] pointer-events-none"
         style="background-image:radial-gradient(circle,#334155 1px,transparent 1px);background-size:30px 30px"
         aria-hidden="true"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 lg:py-26 w-full">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">

            {{-- ── Text ── --}}
            <div class="flex flex-col gap-7">
                {{-- Badge --}}
                <div class="afu">
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-50 border border-blue-100 text-blue-700 text-xs font-semibold">
                        <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                        {{ $isAr ? ' أكثر من ' . $stats['tenants'] . ' متاجر نشطة على المنصة' : $stats['tenants'] . '+ active shops on the platform' }}
                    </span>
                </div>

                {{-- Headline --}}
                <div class="afu d1">
                    <h1 class="font-display font-extrabold text-[2.75rem] sm:text-5xl lg:text-6xl leading-[1.1] text-slate-900">
                        @if($isAr)
                            ابنِ متجرك
                            <span class="relative inline-block">
                                <span class="relative z-10 text-blue-600">الاحترافي</span>
                                <span class="absolute -bottom-1 inset-s-0 inset-e-0 h-3 bg-blue-100 rounded-full z-0"></span>
                            </span>
                            <br>في دقائق
                        @else
                            Build your
                            <span class="relative inline-block">
                                <span class="relative z-10 text-blue-600">professional</span>
                                <span class="absolute -bottom-1 inset-s-0 inset-e-0 h-3 bg-blue-100 rounded-full z-0"></span>
                            </span>
                            <br>shop in minutes
                        @endif
                    </h1>
                </div>

                {{-- Subtext --}}
                <div class="afu d2">
                    <p class="text-lg text-slate-500 leading-relaxed max-w-lg">
                        {{ $isAr
                            ? 'منصة eShop تمنح كل تاجر متجره الخاص بثيمات احترافية، دفع متعدد، شحن مرن، وتعدد لغات — كل شيء جاهز من اليوم الأول.'
                            : 'eShop gives every merchant their own shop with professional themes, multi-payment, flexible shipping, and multilingual support — everything ready from day one.'
                        }}
                    </p>
                </div>

                {{-- CTAs --}}
                <div class="afu d3 flex flex-wrap gap-3">
                    <x-button type="primary" size="lg" href="/register">
                        {{ $isAr ? 'أنشئ متجرك مجاناً' : 'Create Your Shop Free' }}
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="rtl:rotate-180"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </x-button>
                    <x-button type="secondary" size="lg" href="/login">
                        {{ $isAr ? 'تسجيل الدخول' : 'Sign In' }}
                    </x-button>
                </div>

                {{-- Stats chips --}}
                <div class="afu d4 flex flex-wrap gap-4 pt-1">
                    @php
                        $chips = $isAr
                            ? [[$stats['tenants'].'+ متجر نشط'],[$stats['themes'].' ثيم جاهز'],[$stats['payments'].' بوابة دفع']]
                            : [[$stats['tenants'].'+ Active Shops'],[$stats['themes'].' Ready Themes'],[$stats['payments'].' Payment Gateways']];
                    @endphp
                    @foreach($chips as [$text])
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-50 border border-slate-200 text-xs font-medium text-slate-700">
                            {{ $text }}
                        </span>
                    @endforeach
                </div>
            </div>

            {{-- ── Dashboard Mockup ── --}}
            <div class="afu d3 relative">
                <div class="absolute inset-4 bg-blue-200/25 rounded-4xl" style="filter:blur(28px)"></div>

                <div class="relative bg-white rounded-4xl shadow-2xl border border-slate-100 overflow-hidden">
                    {{-- Browser chrome --}}
                    <div class="flex items-center gap-2 px-4 py-3 bg-slate-50 border-b border-slate-100">
                        <span class="w-3 h-3 rounded-full bg-red-400"></span>
                        <span class="w-3 h-3 rounded-full bg-amber-400"></span>
                        <span class="w-3 h-3 rounded-full bg-green-400"></span>
                        <div class="flex-1 mx-3 h-6 rounded-md bg-white border border-slate-200 flex items-center justify-center">
                            <span class="text-[11px] text-slate-400">souq.eshop.com / admin</span>
                        </div>
                    </div>

                    {{-- eShop dashboard preview --}}
                    <img src="{{asset('/images/dashboard-preview.png')}}" alt="">
                </div>

                {{-- Floating: Tenant badge --}}
                <div class="float absolute -top-5 -inset-e-5 bg-white rounded-2xl shadow-xl border border-slate-100 px-4 py-3 flex items-center gap-3 z-10">
                    <div class="w-9 h-9 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-bag" viewBox="0 0 16 16">
                            <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1m3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-800">{{ $isAr ? $stats['tenants'].' متاجر' : $stats['tenants'].' Shops' }}</div>
                        <div class="text-[10px] text-slate-400">{{ $isAr ? 'مسجلة في النظام' : 'Registered in system' }}</div>
                    </div>
                </div>

                {{-- Floating: Theme badge --}}
                <div class="float2 absolute -bottom-5 -inset-s-5 bg-white rounded-2xl shadow-xl border border-slate-100 px-4 py-3 flex items-center gap-3 z-10">
                    <div class="w-9 h-9 rounded-xl bg-violet-100 text-violet-700 flex items-center justify-center text-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="13.5" cy="6.5" r=".5" fill="currentColor"></circle><circle cx="17.5" cy="10.5" r=".5" fill="currentColor"></circle><circle cx="8.5" cy="7.5" r=".5" fill="currentColor"></circle><circle cx="6.5" cy="12.5" r=".5" fill="currentColor"></circle><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10c.926 0 1.648-.746 1.648-1.688 0-.437-.18-.835-.437-1.125-.29-.289-.438-.652-.438-1.125a1.64 1.64 0 0 1 1.668-1.668h1.996c3.051 0 5.555-2.503 5.555-5.554C21.965 6.012 17.461 2 12 2z"></path></svg>
                    </div>
                    <div>
                        <div class="text-xs font-bold text-slate-800">{{ $isAr ? $stats['themes'].' ثيم' : $stats['themes'].' Themes' }}</div>
                        <div class="text-[10px] text-slate-400">{{ $isAr ? 'جاهزة للاستخدام' : 'Ready to use' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ═══════════════════════ TRUST BAR ═══════════════════════ --}}
<section class="py-10 border-y border-slate-100 bg-slate-50/60">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="text-center text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-7">
            {{ $isAr ? 'بوابات الدفع المدعومة' : 'Supported Payment Gateways' }}
        </p>
        <div class="flex flex-wrap justify-center items-center gap-8 lg:gap-14">
            @foreach(['Visa','Mastercard','PayPal','Stripe','Apple Pay','Google Pay','STC Pay','Mada'] as $gw)
                <span class="font-bold text-sm text-slate-400 tracking-tight">{{ $gw }}</span>
            @endforeach
        </div>
    </div>
</section>


{{-- ═══════════════════════ FEATURES (Bento) ═══════════════════════ --}}
<section id="features" class="py-24 lg:py-32">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto text-center mb-16">
        <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-blue-700 text-xs font-semibold mb-4">
            ✦ {{ $isAr ? 'مميزات المنصة' : 'Platform Features' }}
        </span>
        <h2 class="font-display font-extrabold text-4xl lg:text-5xl text-slate-900 mb-4">
            {{ $isAr ? 'كل ما تحتاجه لمتجر ناجح' : 'Everything you need to succeed' }}
        </h2>
        <p class="text-lg text-slate-500">
            {{ $isAr
                ? 'بنينا eShop بعناية ليكون تركيزك الوحيد على بيع منتجاتك وتنمية عملك.'
                : 'We built eShop carefully so your only focus is selling products and growing your business.' }}
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-5">
        @foreach($features as $f)
            <x-feature-card
                :icon="$f['icon']"
                :title="$f['title']"
                :description="$f['description']"
                :badge="$f['badge']"
                :bg="$f['bg']"
                :accent="$f['accent']"
                :badge_bg="$f['badge_bg']"
                :size="$f['size']"
            />
        @endforeach
    </div>
</div>
</section>


{{-- ═══════════════════════ DEMO SCREENSHOTS ═══════════════════════ --}}
{{-- <section id="demo" class="py-24 bg-slate-50/70">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-14">
        <h2 class="font-display font-extrabold text-4xl text-slate-900 mb-4">
            {{ $isAr ? 'نظرة من الداخل' : 'A look inside' }}
        </h2>
        <p class="text-lg text-slate-500">
            {{ $isAr
                ? 'لوحة تحكم قوية لإدارة جميع المتاجر، وواجهة متجر جميلة لكل tenant.'
                : 'A powerful system-admin dashboard for all stores, and a beautiful storefront for each tenant.' }}
        </p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
        @php
            $demos = $isAr ? [
                ['لوحة التحكم الرئيسية', 'إحصائيات كاملة عن المتاجر، المشتركين، والمبيعات في مكان واحد.','#0f172a'],
                ['إدارة المتاجر (Tenants)', 'أضف وأدر جميع المتاجر المسجلة في النظام بسهولة.','#1d4ed8'],
                ['محرر الثيمات', 'كل متجر يخصص ثيمه بشكل كامل — ألوان، خطوط، وتخطيطات.','#7c3aed'],
                ['إدارة المدفوعات', 'جميع بوابات الدفع جاهزة out-of-the-box بلا إعداد مسبق.','#059669'],
                ['إدارة الشحن', 'كل متجر يتحكم بمناطق الشحن والرسوم بشكل مستقل.','#d97706'],
                ['الترجمة المتعددة', 'كل متجر يدعم لغات غير محدودة ويصل لأي سوق عالمي.','#e11d48'],
            ] : [
                ['System Dashboard', 'Full statistics on stores, subscribers, and revenue in one place.','#0f172a'],
                ['Tenant Management', 'Add and manage all registered stores in the system easily.','#1d4ed8'],
                ['Theme Editor', 'Each store fully customizes its theme — colors, fonts, and layouts.','#7c3aed'],
                ['Payment Management', 'All payment gateways ready out-of-the-box with zero configuration.','#059669'],
                ['Shipping Control', 'Each store independently controls shipping zones and fees.','#d97706'],
                ['Multilingual', 'Each store supports unlimited languages and reaches any global market.','#e11d48'],
            ];
        @endphp
        @foreach($demos as [$title,$desc,$emoji,$bg])
        @endforeach
    </div>
</div>
</section> --}}


{{-- ═══════════════════════ PRICING ═══════════════════════ --}}
<section id="pricing" class="py-24 lg:py-32">
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-14">
        <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs font-semibold mb-4">
            {{ $isAr ? 'الأسعار' : 'Pricing' }}
        </span>
        <h2 class="font-display font-extrabold text-4xl text-slate-900 mb-4">
            {{ $isAr ? 'ابدأ مجاناً، انمُ بثقة' : 'Start free, grow with confidence' }}
        </h2>
        <p class="text-lg text-slate-500">{{ $isAr ? 'لا رسوم خفية. ألغِ في أي وقت.' : 'No hidden fees. Cancel anytime.' }}</p>
    </div>

    @if($plans->isEmpty())
        <p class="text-center text-slate-400 py-12">
            {{ $isAr ? 'سيتم الإعلان عن الباقات قريباً.' : 'Plans coming soon.' }}
        </p>
    @else
    @php
        $plansCount  = $plans->count();
        $popularIndex = (int) floor(($plansCount - 1) / 2);
        $cols = match(true) {
            $plansCount === 1 => 'md:grid-cols-1 max-w-sm',
            $plansCount === 2 => 'md:grid-cols-2 max-w-2xl',
            default           => 'md:grid-cols-3 max-w-4xl',
        };
    @endphp
    <div class="grid {{ $cols }} gap-5 mx-auto items-start">
        @foreach($plans as $i => $plan)
        @php
            $hot = ($i === $popularIndex);

            $priceDisplay = $plan->price === 0
                ? ($isAr ? 'مجاني' : 'Free')
                : '$' . number_format($plan->price / 100, 2);

            $period = match(true) {
                $plan->duration_days >= 360 => $isAr ? '/سنوياً'  : '/year',
                $plan->duration_days >= 28  => $isAr ? '/شهرياً' : '/month',
                default                     => '/' . $plan->duration_days . ($isAr ? ' يوم' : ' days'),
            };

            $productsFeature = $plan->max_products === 0
                ? ($isAr ? 'منتجات غير محدودة' : 'Unlimited products')
                : ($isAr
                    ? 'حتى ' . $plan->max_products . ' منتج'
                    : 'Up to ' . $plan->max_products . ' products');

            $features = array_merge([$productsFeature], $plan->features ?? []);

            $cta = $isAr ? 'ابدأ الآن' : 'Get Started';
        @endphp
            <div class="relative flex flex-col rounded-[2rem] p-8 border transition-all duration-300 hover:-translate-y-1
                {{ $hot ? 'bg-slate-900 border-slate-900 text-white shadow-2xl scale-105' : 'bg-white border-slate-200' }}">

                @if($hot)
                    <span class="absolute -top-3 inset-s-1/2 -translate-x-1/2 rtl:translate-x-1/2 inline-flex items-center px-4 py-1 rounded-full bg-blue-500 text-white text-xs font-bold shadow whitespace-nowrap">
                        ✦ {{ $isAr ? 'الأكثر شعبية' : 'Most Popular' }}
                    </span>
                @endif

                <div class="mb-6">
                    <h3 class="font-bold text-base mb-1 {{ $hot ? 'text-blue-300' : 'text-slate-500' }}">
                        {{ $plan->name }}
                    </h3>
                    <div class="flex items-end gap-1 mb-1.5">
                        <span class="font-display font-extrabold text-4xl">{{ $priceDisplay }}</span>
                        @if($plan->price > 0)
                            <span class="text-sm mb-1 text-slate-400">{{ $period }}</span>
                        @endif
                    </div>
                    <p class="text-sm {{ $hot ? 'text-slate-400' : 'text-slate-500' }}">
                        {{ $plan->duration_days }} {{ $isAr ? 'يوم' : 'days' }}
                    </p>
                </div>

                <ul class="flex flex-col gap-2.5 mb-8 flex-1">
                    @foreach($features as $f)
                        <li class="flex items-center gap-2 text-sm {{ $hot ? 'text-slate-300' : 'text-slate-600' }}">
                            <svg class="{{ $hot ? 'text-blue-400' : 'text-emerald-500' }} shrink-0"
                                 xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2.5"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6 9 17l-5-5"/>
                            </svg>
                            {{ $f }}
                        </li>
                    @endforeach
                </ul>

                <x-button type="primary" href="/register" :full="true"
                    class="{{ $hot ? 'bg-white text-slate-900! hover:bg-blue-50 hover:text-white!' : '' }}">
                    {{ $cta }}
                </x-button>
            </div>
        @endforeach
    </div>
    @endif
</div>
</section>


{{-- ═══════════════════════ CTA ═══════════════════════ --}}
<section class="py-24 relative overflow-hidden" style="background:#0f172a">
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 inset-s-[10%] w-60 h-60 rounded-full" style="background:#2563eb;opacity:.15;filter:blur(80px)"></div>
        <div class="absolute bottom-0 inset-e-[10%] w-60 h-60 rounded-full" style="background:#4f46e5;opacity:.12;filter:blur(80px)"></div>
    </div>
    <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="font-display font-extrabold text-4xl lg:text-5xl text-white mb-6 leading-tight">
            {{ $isAr ? 'متجرك ينتظرك' : 'Your shop is waiting' }}
        </h2>
        <p class="text-lg text-slate-400 mb-10">
            {{ $isAr
                ? 'انضم للتجار الذين يبنون أعمالهم مع eShop. لا حاجة لبطاقة ائتمانية.'
                : 'Join merchants building their businesses with eShop. No credit card required.' }}
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <x-button type="primary" size="lg" href="/register" class="bg-white text-slate-900! hover:bg-blue-50 hover:text-white!">
                {{ $isAr ? 'أنشئ متجرك الآن — مجاناً' : 'Create Your Shop Now — Free' }}
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="rtl:rotate-180"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </x-button>
            {{-- <x-button type="ghost" size="lg" href="#demo" class="text-slate-300 hover:text-white hover:bg-white/10">
                {{ $isAr ? 'شاهد كيف يعمل' : 'See how it works' }}
            </x-button> --}}
        </div>
    </div>
</section>


{{-- ═══════════════════════ FOOTER ═══════════════════════ --}}
<footer class="py-10 border-t border-slate-100">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col md:flex-row justify-between items-center gap-5">
        <div class="flex flex-wrap justify-center gap-6">
            @foreach([
                ['#features', $isAr ? 'المميزات' : 'Features'],
                ['#pricing',  $isAr ? 'الأسعار'  : 'Pricing'],
                // ['#',         $isAr ? 'الشروط'   : 'Terms'],
                // ['#',         $isAr ? 'الخصوصية' : 'Privacy'],
            ] as [$href,$lbl])
                <a href="{{ $href }}" class="text-sm text-slate-500 hover:text-slate-800 transition-colors">{{ $lbl }}</a>
            @endforeach
        </div>
        <p class="text-sm text-slate-400">&copy; {{ date('Y') }} eShop. {{ $isAr ? 'جميع الحقوق محفوظة.' : 'All rights reserved.' }}</p>
    </div>
</div>
</footer>

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endcomponent