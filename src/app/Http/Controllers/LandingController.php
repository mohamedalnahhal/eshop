<?php

namespace App\Http\Controllers;

use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\TenantSubscription;
use App\Models\Theme;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        $locale = $request->cookie('locale', config('app.locale', 'ar'));
        app()->setLocale($locale);

        $features = $this->getFeatures($locale);
        $stats    = $this->getStats();
        $plans    = Subscription::orderBy('price')->get();

        return view('pages.landing.index', compact('features', 'locale', 'stats', 'plans'));
    }

    public function switchLang(string $lang)
    {
        $supported = ['ar', 'en'];
        if (! in_array($lang, $supported)) {
            $lang = 'ar';
        }
        return redirect()->back()->withCookie(cookie()->forever('locale', $lang));
    }

    private function getStats(): array
    {
        return [
            'tenants' => Tenant::all()->count(),
            'subscribers' => TenantSubscription::where('status', SubscriptionStatus::ACTIVE)->count(),
            'themes' => Theme::where('tenant_id', null)->count(),
            'payments' => 8,
        ];
    }

    private function getFeatures(string $locale): array
    {
        $isAr = $locale === 'ar';

        return [
            [
                'icon'        => 'palette',
                'title'       => $isAr ? 'ثيمات احترافية قابلة للتخصيص' : 'Professional Customizable Themes',
                'description' => $isAr
                    ? 'اختر من مكتبة ثيمات جاهزة أو صمّم هويتك البصرية بالكامل — الألوان، الخطوط، وتخطيط الصفحات.'
                    : 'Pick a ready-made theme or craft your own identity — colors, fonts, and page layouts.',
                'badge'    => $isAr ? 'تخصيص كامل' : 'Full Control',
                'bg'       => 'bg-violet-50',
                'accent'   => 'text-violet-600',
                'badge_bg' => 'bg-violet-100 text-violet-700',
                'size'     => 'large',
            ],
            [
                'icon'        => 'credit-card',
                'title'       => $isAr ? 'جميع طرق الدفع جاهزة' : 'All Payment Methods Ready',
                'description' => $isAr
                    ? 'بطاقات، محافظ إلكترونية، دفع عند الاستلام، وأكثر. كل شيء جاهز out-of-the-box بلا أي إعداد.'
                    : 'Cards, wallets, COD, and more — all set up out of the box with zero configuration.',
                'badge'    => $isAr ? 'بلا إعداد مسبق' : 'Zero Setup',
                'bg'       => 'bg-emerald-50',
                'accent'   => 'text-emerald-600',
                'badge_bg' => 'bg-emerald-100 text-emerald-700',
                'size'     => 'normal',
            ],
            [
                'icon'        => 'truck',
                'title'       => $isAr ? 'تحكم كامل بقواعد الشحن' : 'Full Shipping Rules Control',
                'description' => $isAr
                    ? 'حدّد مناطق الشحن والرسوم والشركات التي تعمل معها بمرونة تامة.'
                    : 'Define shipping zones, fees, and carriers with total flexibility.',
                'badge'    => $isAr ? 'مرونة تامة' : 'Flexible',
                'bg'       => 'bg-sky-50',
                'accent'   => 'text-sky-600',
                'badge_bg' => 'bg-sky-100 text-sky-700',
                'size'     => 'normal',
            ],
            [
                'icon'        => 'globe',
                'title'       => $isAr ? 'دعم لا محدود للغات العالم' : 'Unlimited World Languages',
                'description' => $isAr
                    ? 'أضف ترجمات غير محدودة وابيع لأي سوق حول العالم بلغة زبائنك.'
                    : 'Add unlimited translations and sell to any market in your customers\' language.',
                'badge'    => $isAr ? 'بلا حدود' : 'Unlimited',
                'bg'       => 'bg-amber-50',
                'accent'   => 'text-amber-600',
                'badge_bg' => 'bg-amber-100 text-amber-700',
                'size'     => 'large',
            ],
            [
                'icon'        => 'zap',
                'title'       => $isAr ? 'أنشئ متجرك بضغطة زر' : 'Launch Your Store in One Click',
                'description' => $isAr
                    ? 'من التسجيل إلى أول بيعة في دقائق. لا تقنية مطلوبة، لا تعقيد.'
                    : 'From sign-up to first sale in minutes. No tech skills required.',
                'badge'    => $isAr ? 'فوري' : 'Instant',
                'bg'       => 'bg-rose-50',
                'accent'   => 'text-rose-600',
                'badge_bg' => 'bg-rose-100 text-rose-700',
                'size'     => 'large',
            ],
            [
                'icon'        => 'tag',
                'title'       => $isAr ? 'عروض وباقات ميسّرة' : 'Affordable Plans & Offers',
                'description' => $isAr
                    ? 'ابدأ مجاناً وانمُ بخطط تتناسب مع حجم عملك. لا رسوم خفية أبداً.'
                    : 'Start free and scale with plans that match your growth. No hidden fees ever.',
                'badge'    => $isAr ? 'ابدأ مجاناً' : 'Free Start',
                'bg'       => 'bg-indigo-50',
                'accent'   => 'text-indigo-600',
                'badge_bg' => 'bg-indigo-100 text-indigo-700',
                'size'     => 'normal',
            ],
        ];
    }
}