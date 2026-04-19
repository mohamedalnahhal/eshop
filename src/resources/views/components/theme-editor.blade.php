<div
    x-data="{ previewDevice: 'desktop' }"
    class="flex flex-col h-[calc(100vh-4rem)]"
    dir="rtl"
>
    {{-- TOP BAR --}}
    <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 flex-shrink-0">
        <div class="flex items-center gap-3">
            <a href="{{ route('filament.tenant_admin.pages.themes-page') }}"
               class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-900 transition-colors">
                @icon('arrow-r', 'w-4 h-4 rotate-180')
                الثيمات
            </a>
            <span class="text-gray-300">/</span>
            <span class="text-sm font-semibold text-gray-900 dark:text-white">تخصيص الثيم</span>
        </div>

        <div class="flex items-center gap-1 bg-gray-100 dark:bg-gray-800 rounded-lg p-1">
            @foreach([['desktop','computer-desktop','سطح المكتب'],['tablet','device-tablet','تابلت'],['mobile','device-phone-mobile','موبايل']] as [$d,$ic,$lb])
                <button @click="previewDevice = '{{ $d }}'"
                        :class="previewDevice === '{{ $d }}' ? 'bg-white dark:bg-gray-700 shadow-sm text-gray-900 dark:text-white' : 'text-gray-500 hover:text-gray-700'"
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-medium transition-all">
                    @icon($ic, 'w-4 h-4') {{ $lb }}
                </button>
            @endforeach
        </div>

        <div class="flex items-center gap-2">
            <button wire:click="resetToDefaults"
                    wire:confirm="هل أنت متأكد؟ سيتم إعادة تعيين جميع القيم للافتراضي"
                    class="flex items-center gap-1.5 px-3 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                @icon('arrow-path', 'w-4 h-4') إعادة تعيين
            </button>
            <button wire:click="save" wire:loading.attr="disabled"
                    class="flex items-center gap-1.5 px-4 py-2 text-sm font-semibold bg-primary-600 text-white rounded-lg hover:bg-primary-700 disabled:opacity-50 transition-colors shadow-sm">
                <span wire:loading.remove wire:target="save" class="flex items-center gap-1.5">
                    @icon('cloud-arrow-up', 'w-4 h-4') حفظ التغييرات
                </span>
                <span wire:loading wire:target="save" class="flex items-center gap-1.5">
                    <x-spinner class="w-4 h-4" /> جاري الحفظ...
                </span>
            </button>
        </div>
    </div>

    {{-- MAIN --}}
    <div class="flex flex-1 overflow-hidden">

        {{-- LEFT: Editor --}}
        <div class="w-80 flex-shrink-0 bg-white dark:bg-gray-900 border-e border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">
            <div class="flex overflow-x-auto border-b border-gray-200 dark:border-gray-700 px-1 pt-1 gap-0.5 flex-shrink-0 scrollbar-hide">
                @foreach([['palette','swatch','الألوان'],['font','text-cursor','الخط'],['layout','squares-2x2','التخطيط'],['corners','stop','الزوايا'],['shadows','sparkles','الظلال'],['icons','squares-plus','الأيقونات']] as [$tab,$icon,$label])
                    <button wire:click="$set('activeTab', '{{ $tab }}')"
                            @class(['flex items-center gap-1.5 px-3 py-2.5 text-xs font-medium rounded-t-lg whitespace-nowrap transition-colors border-b-2',
                                'border-primary-500 text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20' => $activeTab === $tab,
                                'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' => $activeTab !== $tab])>
                        @icon($icon, 'w-3.5 h-3.5') {{ $label }}
                    </button>
                @endforeach
            </div>

            <div class="flex-1 overflow-y-auto p-4 space-y-5">
                @if($activeTab === 'palette')
                    <x-theme-editor-section title="ألوان العلامة التجارية">
                        <x-theme-color-field wire:model.live="palette.primary"      label="الأساسي" />
                        <x-theme-color-field wire:model.live="palette.on_primary"   label="على الأساسي" />
                        <x-theme-color-field wire:model.live="palette.secondary"    label="الثانوي" />
                        <x-theme-color-field wire:model.live="palette.on_secondary" label="على الثانوي" />
                        <x-theme-color-field wire:model.live="palette.accent"       label="المميز" />
                        <x-theme-color-field wire:model.live="palette.on_accent"    label="على المميز" />
                    </x-theme-editor-section>
                    <x-theme-editor-section title="الخلفيات">
                        <x-theme-color-field wire:model.live="palette.background"  label="الخلفية" />
                        <x-theme-color-field wire:model.live="palette.card_bg"     label="خلفية الكارد" />
                        <x-theme-color-field wire:model.live="palette.surface_100" label="Surface 100" />
                        <x-theme-color-field wire:model.live="palette.surface_200" label="Surface 200" />
                        <x-theme-color-field wire:model.live="palette.surface_300" label="Surface 300" />
                    </x-theme-editor-section>
                    <x-theme-editor-section title="النصوص">
                        <x-theme-color-field wire:model.live="palette.text"       label="النص" />
                        <x-theme-color-field wire:model.live="palette.text_muted" label="نص باهت" />
                    </x-theme-editor-section>
                    <x-theme-editor-section title="الهيدر">
                        <x-theme-color-field wire:model.live="palette.header"      label="خلفية الهيدر" />
                        <x-theme-color-field wire:model.live="palette.on_header"   label="على الهيدر" />
                        <x-theme-color-field wire:model.live="palette.m_header"    label="هيدر الموبايل" />
                        <x-theme-color-field wire:model.live="palette.on_m_header" label="على هيدر الموبايل" />
                    </x-theme-editor-section>
                    <x-theme-editor-section title="الفوتر">
                        <x-theme-color-field wire:model.live="palette.footer"    label="خلفية الفوتر" />
                        <x-theme-color-field wire:model.live="palette.on_footer" label="على الفوتر" />
                    </x-theme-editor-section>
                    <x-theme-editor-section title="ألوان الحالة">
                        <x-theme-color-field wire:model.live="palette.success" label="نجاح" />
                        <x-theme-color-field wire:model.live="palette.warning" label="تحذير" />
                        <x-theme-color-field wire:model.live="palette.danger"  label="خطر" />
                        <x-theme-color-field wire:model.live="palette.info"    label="معلومة" />
                    </x-theme-editor-section>
                    <x-theme-editor-section title="الذهبي">
                        <x-theme-color-field wire:model.live="palette.gold"         label="ذهبي" />
                        <x-theme-color-field wire:model.live="palette.gold_surface" label="سطح ذهبي" />
                        <x-theme-color-field wire:model.live="palette.on_gold"      label="على الذهبي" />
                    </x-theme-editor-section>
                    <x-theme-editor-section title="الحدود">
                        <x-theme-color-field wire:model.live="palette.border"       label="حد" />
                        <x-theme-color-field wire:model.live="palette.border_muted" label="حد باهت" />
                        <x-theme-color-field wire:model.live="palette.border_input" label="حد المدخل" />
                    </x-theme-editor-section>
                @endif

                @if($activeTab === 'font')
                    <x-theme-editor-section title="العائلة">
                        <x-theme-text-field wire:model.live="font.primary_family"   label="الخط الأساسي"  placeholder="Tajawal, sans-serif" />
                        <x-theme-text-field wire:model.live="font.secondary_family" label="خط العناوين"   placeholder="Tajawal, sans-serif" />
                    </x-theme-editor-section>
                    <x-theme-editor-section title="الوزن والمسافات">
                        <x-theme-select-field wire:model.live="font.base_weight"    label="وزن الجسم"    :options="['300'=>'Light 300','400'=>'Regular 400','500'=>'Medium 500','600'=>'SemiBold 600','700'=>'Bold 700']" />
                        <x-theme-select-field wire:model.live="font.heading_weight" label="وزن العناوين" :options="['400'=>'Regular 400','500'=>'Medium 500','600'=>'SemiBold 600','700'=>'Bold 700','800'=>'ExtraBold 800','900'=>'Black 900']" />
                        <x-theme-text-field   wire:model.live="font.line_height"    label="ارتفاع السطر" placeholder="1.6" />
                        <x-theme-text-field   wire:model.live="font.letter_spacing" label="مسافة الأحرف" placeholder="normal" />
                    </x-theme-editor-section>
                    <x-theme-editor-section title="أحجام الخط">
                        @foreach(['xs'=>'XS','sm'=>'SM','base'=>'Base','lg'=>'LG','xl'=>'XL','2xl'=>'2XL','3xl'=>'3XL','4xl'=>'4XL'] as $k => $lb)
                            <x-theme-text-field wire:model.live="font.{{ $k }}" label="{{ $lb }}" />
                        @endforeach
                    </x-theme-editor-section>
                @endif

                @if($activeTab === 'layout')
                    <x-theme-editor-section title="الأزرار">
                        <x-theme-text-field   wire:model.live="buttons.padding_x"   label="حشوة أفقية"  placeholder="1.25rem" />
                        <x-theme-text-field   wire:model.live="buttons.padding_y"   label="حشوة رأسية"  placeholder="0.625rem" />
                        <x-theme-select-field wire:model.live="buttons.font_weight" label="وزن الخط"    :options="['400'=>'400','500'=>'500','600'=>'600','700'=>'700','800'=>'800']" />
                        <div class="flex items-center justify-between py-1">
                            <span class="text-xs text-gray-600 dark:text-gray-400">أحرف كبيرة</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="buttons.uppercase" class="sr-only peer">
                                <div class="w-9 h-5 bg-gray-200 peer-focus:ring-2 peer-focus:ring-primary-300 dark:bg-gray-700 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-primary-600 after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all"></div>
                            </label>
                        </div>
                    </x-theme-editor-section>
                    <x-theme-editor-section title="حقول الإدخال">
                        <x-theme-text-field   wire:model.live="inputs.padding_x"   label="حشوة أفقية" placeholder="0.75rem" />
                        <x-theme-text-field   wire:model.live="inputs.padding_y"   label="حشوة رأسية" placeholder="0.5rem" />
                        <x-theme-select-field wire:model.live="inputs.font_weight" label="وزن الخط"   :options="['400'=>'400','500'=>'500','600'=>'600','700'=>'700']" />
                    </x-theme-editor-section>
                    <x-theme-editor-section title="الهيدر">
                        <x-theme-text-field   wire:model.live="header.padding_t"     label="حشوة علوية" />
                        <x-theme-text-field   wire:model.live="header.padding_b"     label="حشوة سفلية" />
                        <x-theme-text-field   wire:model.live="header.margin_b"      label="مسافة سفلية" />
                        <x-theme-text-field   wire:model.live="header.gap"           label="المسافة بين العناصر" />
                        <x-theme-text-field   wire:model.live="header.logo_width"    label="عرض الشعار" />
                        <x-theme-text-field   wire:model.live="header.logo_hight"    label="ارتفاع الشعار" />
                        <x-theme-select-field wire:model.live="header.position"      label="الموضع"     :options="['static'=>'Static','sticky'=>'Sticky','fixed'=>'Fixed']" />
                        <x-theme-text-field   wire:model.live="header.bg_opacity"    label="شفافية الخلفية" />
                        <x-theme-text-field   wire:model.live="header.backdrop_blur" label="ضبابية الخلفية" />
                    </x-theme-editor-section>
                    <x-theme-editor-section title="الفوتر">
                        <x-theme-text-field   wire:model.live="footer.padding_t" label="حشوة علوية" />
                        <x-theme-text-field   wire:model.live="footer.padding_b" label="حشوة سفلية" />
                        <x-theme-text-field   wire:model.live="footer.margin_t"  label="مسافة علوية" />
                        <x-theme-select-field wire:model.live="footer.columns"   label="الأعمدة"    :options="[1=>'1 عمود',2=>'2 أعمدة',3=>'3 أعمدة']" />
                    </x-theme-editor-section>
                @endif

                @if($activeTab === 'corners')
                    <x-theme-editor-section title="عناصر المتجر">
                        <x-theme-text-field wire:model.live="corners.btn"    label="الأزرار" />
                        <x-theme-text-field wire:model.live="corners.cta"    label="CTA" />
                        <x-theme-text-field wire:model.live="corners.input"  label="حقول الإدخال" />
                        <x-theme-text-field wire:model.live="corners.card"   label="الكاردز" />
                        <x-theme-text-field wire:model.live="corners.badge"  label="الشارات" />
                        <x-theme-text-field wire:model.live="corners.icon"   label="الأيقونات" />
                        <x-theme-text-field wire:model.live="corners.model"  label="المودال" />
                        <x-theme-text-field wire:model.live="corners.header" label="الهيدر" />
                    </x-theme-editor-section>
                    <x-theme-editor-section title="أحجام الزوايا">
                        @foreach(['sm','md','lg','xl','2xl','3xl','4xl'] as $size)
                            <x-theme-text-field wire:model.live="corners.{{ $size }}" label="{{ $size }}" />
                        @endforeach
                    </x-theme-editor-section>
                @endif

                @if($activeTab === 'shadows')
                    <x-theme-editor-section title="الظلال">
                        <x-theme-text-field wire:model.live="glows.card_shadow"   label="كارد" />
                        <x-theme-text-field wire:model.live="glows.button_shadow" label="زر" />
                        <x-theme-text-field wire:model.live="glows.input_shadow"  label="مدخل" />
                        <x-theme-text-field wire:model.live="glows.header_shadow" label="هيدر" />
                        <x-theme-text-field wire:model.live="glows.modal_shadow"  label="مودال" />
                        <x-theme-text-field wire:model.live="glows.glow_shadow"   label="توهج" />
                    </x-theme-editor-section>
                @endif

                @if($activeTab === 'icons')
                    <x-theme-editor-section title="حزمة الأيقونات">
                        <div class="grid grid-cols-2 gap-2">
                            @foreach(array_keys(config('icons')) as $pack)
                                <button wire:click="$set('icon_pack', '{{ $pack }}')"
                                        @class(['flex items-center gap-2 px-3 py-2.5 rounded-xl border-2 text-xs font-medium transition-all text-start',
                                            'border-primary-500 bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-300' => $icon_pack === $pack,
                                            'border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 hover:border-gray-300' => $icon_pack !== $pack])>
                                    <div class="w-6 h-6 rounded-lg flex items-center justify-center text-xs"
                                         style="{{ $icon_pack === $pack ? 'background-color: var(--color-primary); color: white' : 'background-color: #f3f4f6' }}">●</div>
                                    {{ $pack }}
                                </button>
                            @endforeach
                        </div>
                    </x-theme-editor-section>
                @endif
            </div>
        </div>

        {{-- RIGHT: Live Preview --}}
        <div class="flex-1 bg-gray-100 dark:bg-gray-950 flex flex-col overflow-hidden">
            <div class="flex items-center gap-3 px-4 py-2 bg-gray-200 dark:bg-gray-900 border-b border-gray-300 dark:border-gray-700 flex-shrink-0">
                <div class="flex gap-1.5">
                    <div class="w-3 h-3 rounded-full bg-red-400"></div>
                    <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                    <div class="w-3 h-3 rounded-full bg-green-400"></div>
                </div>
                <div class="flex-1 bg-white dark:bg-gray-800 rounded-md px-3 py-1 text-xs text-gray-400 font-mono">
                    {{ tenant('name') }} — Live Preview
                </div>
            </div>

            <div class="flex-1 overflow-auto p-4 flex justify-center items-start">
                <div :class="{ 'w-full': previewDevice === 'desktop', 'w-[768px]': previewDevice === 'tablet', 'w-[390px]': previewDevice === 'mobile' }"
                     class="transition-all duration-300 bg-white rounded-xl shadow-2xl overflow-hidden"
                     style="min-height: 600px">

                    {{-- ✅ Live CSS - يتحدث مع كل تغيير Livewire --}}
                    <style>{!! $this->liveCss !!}</style>

                    <div style="font-family: var(--font-primary); color: var(--color-text); background-color: var(--color-bg);">

                        <x-theme-preview.header :header="$header" :palette="$palette" />

                        <div class="px-4 py-8 space-y-12" style="max-width: var(--container, 1280px); margin: 0 auto;">

                            @php
                                $heroSection = collect($this->homepage['sections'] ?? [])->firstWhere('key', 'hero') ?? [];
                                $newArrivals = collect($this->homepage['sections'] ?? [])->firstWhere('key', 'new_arrivals') ?? [];
                                $topRated    = collect($this->homepage['sections'] ?? [])->firstWhere('key', 'top_rated') ?? [];
                            @endphp

                            @if($heroSection['enabled'] ?? true)
                                <x-theme-preview.hero :section="$heroSection" />
                            @endif

                            @if($newArrivals['enabled'] ?? true)
                                <section>
                                    <div class="flex items-center justify-between mb-6">
                                        <div class="flex items-center gap-3">
                                            <h2 class="text-theme-2xl font-bold text-theme">{{ $newArrivals['title'] ?? 'وصل حديثاً' }}</h2>
                                            @if($newArrivals['show_badge'] ?? true)
                                                <span class="badge bg-primary text-on-primary">{{ $newArrivals['badge_label'] ?? 'جديد' }}</span>
                                            @endif
                                        </div>
                                        <span class="text-theme-sm font-semibold text-primary cursor-pointer hover:opacity-75 flex items-center gap-1">
                                            عرض الكل @icon('chevron-r', 'w-4 h-4 rtl:rotate-180')
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                                        @foreach([['منتج أول','149.00','إلكترونيات'],['منتج ثاني','89.00','ملابس'],['منتج ثالث','220.00','أثاث'],['منتج رابع','55.00','إكسسوار']] as [$n,$pr,$cat])
                                            <x-theme-preview.product-card :name="$n" :price="$pr" :category="$cat" :badge="$newArrivals['badge_label'] ?? 'جديد'" />
                                        @endforeach
                                    </div>
                                </section>
                            @endif

                            @if($topRated['enabled'] ?? true)
                                <section>
                                    <div class="flex items-center justify-between mb-6">
                                        <div class="flex items-center gap-3">
                                            <h2 class="text-theme-2xl font-bold text-theme">{{ $topRated['title'] ?? 'الأعلى تقييماً' }}</h2>
                                            @if($topRated['show_badge'] ?? true)
                                                <span class="badge bg-gold-surface text-on-gold border border-gold">{{ $topRated['badge_label'] ?? '★ مميز' }}</span>
                                            @endif
                                        </div>
                                        <span class="text-theme-sm font-semibold text-primary cursor-pointer hover:opacity-75 flex items-center gap-1">
                                            عرض الكل @icon('chevron-r', 'w-4 h-4 rtl:rotate-180')
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                                        @foreach([['الأعلى تقييماً','199.00','تقنية'],['مفضل الزبائن','120.00','موضة'],['الأكثر مبيعاً','75.00','منزل'],['اختيار المحررين','340.00','فاخر']] as [$n,$pr,$cat])
                                            <x-theme-preview.product-card :name="$n" :price="$pr" :category="$cat" />
                                        @endforeach
                                    </div>
                                </section>
                            @endif

                        </div>

                        <x-theme-preview.footer :footer="$footer" />
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>