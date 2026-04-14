<div
    x-data="{
        css: @entangle('liveCss').live,
        previewDevice: 'desktop',
        iframeLoaded: false,

        init() {
            this.$watch('css', (val) => this.pushCss(val));
            this.$nextTick(() => {
                const iframe = this.$refs.preview;
                iframe.addEventListener('load', () => {
                    this.iframeLoaded = true;
                    this.pushCss(this.css);
                });
            });
        },

        pushCss(css) {
            const iframe = this.$refs.preview;
            if (!iframe || !iframe.contentDocument) return;
            let style = iframe.contentDocument.getElementById('__theme_live__');
            if (!style) {
                style = iframe.contentDocument.createElement('style');
                style.id = '__theme_live__';
                iframe.contentDocument.head.appendChild(style);
            }
            style.textContent = css;
        }
    }"
    class="flex flex-col h-[calc(100vh-4rem)]"
>
    {{-- ═══════════════════════════════════════════════════
         TOP BAR
    ════════════════════════════════════════════════════ --}}
    <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 flex-shrink-0">
        {{-- Back + Title --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('filament.tenant.pages.themes') }}"
               class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-900 dark:hover:text-white transition-colors">
                <x-heroicon-o-arrow-right class="w-4 h-4" />
                الثيمات
            </a>
            <span class="text-gray-300 dark:text-gray-600">/</span>
            <span class="text-sm font-semibold text-gray-900 dark:text-white">تخصيص الثيم</span>
        </div>

        {{-- Device toggle --}}
        <div class="flex items-center gap-1 bg-gray-100 dark:bg-gray-800 rounded-lg p-1">
            <button @click="previewDevice = 'desktop'"
                    :class="previewDevice === 'desktop' ? 'bg-white dark:bg-gray-700 shadow-sm text-gray-900 dark:text-white' : 'text-gray-500 hover:text-gray-700'"
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-medium transition-all">
                <x-heroicon-o-computer-desktop class="w-4 h-4" />
                سطح المكتب
            </button>
            <button @click="previewDevice = 'tablet'"
                    :class="previewDevice === 'tablet' ? 'bg-white dark:bg-gray-700 shadow-sm text-gray-900 dark:text-white' : 'text-gray-500 hover:text-gray-700'"
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-medium transition-all">
                <x-heroicon-o-device-tablet class="w-4 h-4" />
                تابلت
            </button>
            <button @click="previewDevice = 'mobile'"
                    :class="previewDevice === 'mobile' ? 'bg-white dark:bg-gray-700 shadow-sm text-gray-900 dark:text-white' : 'text-gray-500 hover:text-gray-700'"
                    class="flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-medium transition-all">
                <x-heroicon-o-device-phone-mobile class="w-4 h-4" />
                موبايل
            </button>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-2">
            <button wire:click="resetToDefaults"
                    wire:confirm="هل أنت متأكد؟ سيتم إعادة تعيين جميع القيم للافتراضي"
                    class="flex items-center gap-1.5 px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                <x-heroicon-o-arrow-path class="w-4 h-4" />
                إعادة تعيين
            </button>
            <button wire:click="save"
                    wire:loading.attr="disabled"
                    class="flex items-center gap-1.5 px-4 py-2 text-sm font-semibold bg-primary-600 text-white rounded-lg hover:bg-primary-700 disabled:opacity-50 transition-colors shadow-sm">
                <span wire:loading.remove wire:target="save">
                    <x-heroicon-o-cloud-arrow-up class="w-4 h-4 inline -mt-0.5" />
                    حفظ التغييرات
                </span>
                <span wire:loading wire:target="save" class="flex items-center gap-1.5">
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 12 0 12 12h4z"></path>
                    </svg>
                    جاري الحفظ...
                </span>
            </button>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════
         MAIN LAYOUT: PANEL + PREVIEW
    ════════════════════════════════════════════════════ --}}
    <div class="flex flex-1 overflow-hidden">

        {{-- ─────────────────────────────────────────────
             LEFT PANEL: Editor
        ──────────────────────────────────────────────── --}}
        <div class="w-80 flex-shrink-0 bg-white dark:bg-gray-900 border-e border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">

            {{-- Tabs --}}
            <div class="flex overflow-x-auto border-b border-gray-200 dark:border-gray-700 px-1 pt-1 gap-0.5 flex-shrink-0 scrollbar-hide">
                @foreach([
                    ['palette', 'heroicon-o-swatch', 'الألوان'],
                    ['font', 'heroicon-o-text-cursor', 'الخط'],
                    ['layout', 'heroicon-o-squares-2x2', 'التخطيط'],
                    ['corners', 'heroicon-o-stop', 'الزوايا'],
                    ['shadows', 'heroicon-o-sparkles', 'الظلال'],
                    ['icons', 'heroicon-o-squares-plus', 'الأيقونات'],
                ] as [$tab, $icon, $label])
                    <button wire:click="$set('activeTab', '{{ $tab }}')"
                            @class([
                                'flex items-center gap-1.5 px-3 py-2.5 text-xs font-medium rounded-t-lg whitespace-nowrap transition-colors border-b-2',
                                'border-primary-500 text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20' => $activeTab === $tab,
                                'border-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' => $activeTab !== $tab,
                            ])>
                        <x-dynamic-component :component="$icon" class="w-3.5 h-3.5" />
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            {{-- Tab content --}}
            <div class="flex-1 overflow-y-auto p-4 space-y-5">

                {{-- ══ PALETTE TAB ══ --}}
                @if($activeTab === 'palette')
                    {{-- Brand --}}
                    <x-theme-editor-section title="ألوان العلامة التجارية">
                        <x-theme-color-field wire:model.live="palette.primary"    label="الأساسي" />
                        <x-theme-color-field wire:model.live="palette.secondary"  label="الثانوي" />
                        <x-theme-color-field wire:model.live="palette.accent"     label="المميز" />
                        <x-theme-color-field wire:model.live="palette.on_primary"   label="على الأساسي" />
                        <x-theme-color-field wire:model.live="palette.on_secondary" label="على الثانوي" />
                        <x-theme-color-field wire:model.live="palette.on_accent"    label="على المميز" />
                    </x-theme-editor-section>

                    {{-- Background --}}
                    <x-theme-editor-section title="الخلفيات">
                        <x-theme-color-field wire:model.live="palette.background"   label="الخلفية" />
                        <x-theme-color-field wire:model.live="palette.card_bg"      label="خلفية الكارد" />
                        <x-theme-color-field wire:model.live="palette.surface_100"  label="Surface 100" />
                        <x-theme-color-field wire:model.live="palette.surface_200"  label="Surface 200" />
                        <x-theme-color-field wire:model.live="palette.surface_300"  label="Surface 300" />
                    </x-theme-editor-section>

                    {{-- Text --}}
                    <x-theme-editor-section title="النصوص">
                        <x-theme-color-field wire:model.live="palette.text"       label="النص" />
                        <x-theme-color-field wire:model.live="palette.text_muted" label="نص باهت" />
                    </x-theme-editor-section>

                    {{-- Header --}}
                    <x-theme-editor-section title="الهيدر">
                        <x-theme-color-field wire:model.live="palette.header"     label="خلفية الهيدر" />
                        <x-theme-color-field wire:model.live="palette.on_header"  label="على الهيدر" />
                        <x-theme-color-field wire:model.live="palette.m_header"    label="هيدر الموبايل" />
                        <x-theme-color-field wire:model.live="palette.on_m_header" label="على هيدر الموبايل" />
                    </x-theme-editor-section>

                    {{-- Footer --}}
                    <x-theme-editor-section title="الفوتر">
                        <x-theme-color-field wire:model.live="palette.footer"    label="خلفية الفوتر" />
                        <x-theme-color-field wire:model.live="palette.on_footer" label="على الفوتر" />
                    </x-theme-editor-section>

                    {{-- Status --}}
                    <x-theme-editor-section title="ألوان الحالة">
                        <x-theme-color-field wire:model.live="palette.success" label="نجاح" />
                        <x-theme-color-field wire:model.live="palette.warning" label="تحذير" />
                        <x-theme-color-field wire:model.live="palette.danger"  label="خطر" />
                        <x-theme-color-field wire:model.live="palette.info"    label="معلومة" />
                    </x-theme-editor-section>

                    {{-- Gold --}}
                    <x-theme-editor-section title="الذهبي">
                        <x-theme-color-field wire:model.live="palette.gold"         label="ذهبي" />
                        <x-theme-color-field wire:model.live="palette.gold_surface" label="سطح ذهبي" />
                        <x-theme-color-field wire:model.live="palette.on_gold"      label="على الذهبي" />
                    </x-theme-editor-section>

                    {{-- Borders --}}
                    <x-theme-editor-section title="الحدود">
                        <x-theme-color-field wire:model.live="palette.border"       label="حد" />
                        <x-theme-color-field wire:model.live="palette.border_muted" label="حد باهت" />
                        <x-theme-color-field wire:model.live="palette.border_input" label="حد المدخل" />
                    </x-theme-editor-section>
                @endif

                {{-- ══ FONT TAB ══ --}}
                @if($activeTab === 'font')
                    <x-theme-editor-section title="العائلة">
                        <x-theme-text-field wire:model.live="font.primary_family"   label="الخط الأساسي" placeholder="Tajawal, sans-serif" />
                        <x-theme-text-field wire:model.live="font.secondary_family" label="خط العناوين"  placeholder="Tajawal, sans-serif" />
                    </x-theme-editor-section>

                    <x-theme-editor-section title="الوزن والمسافات">
                        <x-theme-select-field wire:model.live="font.base_weight" label="وزن الجسم" :options="['300'=>'Light 300','400'=>'Regular 400','500'=>'Medium 500','600'=>'SemiBold 600','700'=>'Bold 700']" />
                        <x-theme-select-field wire:model.live="font.heading_weight" label="وزن العناوين" :options="['400'=>'Regular 400','500'=>'Medium 500','600'=>'SemiBold 600','700'=>'Bold 700','800'=>'ExtraBold 800','900'=>'Black 900']" />
                        <x-theme-text-field wire:model.live="font.line_height"    label="ارتفاع السطر" placeholder="1.6" />
                        <x-theme-text-field wire:model.live="font.letter_spacing" label="مسافة الأحرف" placeholder="normal" />
                    </x-theme-editor-section>

                    <x-theme-editor-section title="أحجام الخط">
                        @foreach(['xs'=>'XS','sm'=>'SM','base'=>'Base','lg'=>'LG','xl'=>'XL','2xl'=>'2XL','3xl'=>'3XL','4xl'=>'4XL'] as $key => $label)
                            <x-theme-text-field wire:model.live="font.{{ $key }}" label="{{ $label }}" />
                        @endforeach
                    </x-theme-editor-section>
                @endif

                {{-- ══ LAYOUT TAB ══ --}}
                @if($activeTab === 'layout')
                    <x-theme-editor-section title="الأزرار">
                        <x-theme-text-field wire:model.live="buttons.padding_x"   label="حشوة أفقية" placeholder="1.25rem" />
                        <x-theme-text-field wire:model.live="buttons.padding_y"   label="حشوة رأسية" placeholder="0.625rem" />
                        <x-theme-select-field wire:model.live="buttons.font_weight" label="وزن الخط" :options="['400'=>'400','500'=>'500','600'=>'600','700'=>'700','800'=>'800']" />
                        <div class="flex items-center justify-between py-1">
                            <span class="text-xs text-gray-600 dark:text-gray-400">أحرف كبيرة</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="buttons.uppercase" class="sr-only peer">
                                <div class="w-9 h-5 bg-gray-200 peer-focus:ring-2 peer-focus:ring-primary-300 dark:bg-gray-700 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-primary-600 after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all"></div>
                            </label>
                        </div>
                    </x-theme-editor-section>

                    <x-theme-editor-section title="حقول الإدخال">
                        <x-theme-text-field wire:model.live="inputs.padding_x"   label="حشوة أفقية" placeholder="0.75rem" />
                        <x-theme-text-field wire:model.live="inputs.padding_y"   label="حشوة رأسية" placeholder="0.5rem" />
                        <x-theme-select-field wire:model.live="inputs.font_weight" label="وزن الخط" :options="['400'=>'400','500'=>'500','600'=>'600','700'=>'700']" />
                    </x-theme-editor-section>

                    <x-theme-editor-section title="الهيدر (ديسكتوب)">
                        <x-theme-text-field wire:model.live="header.padding_t" label="حشوة علوية" />
                        <x-theme-text-field wire:model.live="header.padding_b" label="حشوة سفلية" />
                        <x-theme-text-field wire:model.live="header.margin_b"  label="مسافة سفلية" />
                        <x-theme-text-field wire:model.live="header.gap"       label="المسافة بين العناصر" />
                        <x-theme-text-field wire:model.live="header.logo_width"  label="عرض الشعار" />
                        <x-theme-text-field wire:model.live="header.logo_hight"  label="ارتفاع الشعار" />
                        <x-theme-select-field wire:model.live="header.position" label="الموضع" :options="['static'=>'Static','sticky'=>'Sticky','fixed'=>'Fixed']" />
                        <x-theme-text-field wire:model.live="header.bg_opacity"    label="شفافية الخلفية (0-1)" />
                        <x-theme-text-field wire:model.live="header.backdrop_blur" label="ضبابية الخلفية" />
                    </x-theme-editor-section>

                    <x-theme-editor-section title="الفوتر">
                        <x-theme-text-field wire:model.live="footer.padding_t" label="حشوة علوية" />
                        <x-theme-text-field wire:model.live="footer.padding_b" label="حشوة سفلية" />
                        <x-theme-text-field wire:model.live="footer.margin_t"  label="مسافة علوية" />
                        <x-theme-select-field wire:model.live="footer.columns" label="الأعمدة" :options="[1=>'1 عمود',2=>'2 أعمدة',3=>'3 أعمدة']" />
                    </x-theme-editor-section>
                @endif

                {{-- ══ CORNERS TAB ══ --}}
                @if($activeTab === 'corners')
                    <x-theme-editor-section title="عناصر المتجر">
                        <x-theme-text-field wire:model.live="corners.btn"        label="الأزرار" />
                        <x-theme-text-field wire:model.live="corners.cta"        label="CTA" />
                        <x-theme-text-field wire:model.live="corners.input"      label="حقول الإدخال" />
                        <x-theme-text-field wire:model.live="corners.card"       label="الكاردز" />
                        <x-theme-text-field wire:model.live="corners.badge"      label="الشارات" />
                        <x-theme-text-field wire:model.live="corners.icon"       label="الأيقونات" />
                        <x-theme-text-field wire:model.live="corners.model"      label="المودال" />
                        <x-theme-text-field wire:model.live="corners.header"     label="الهيدر" />
                    </x-theme-editor-section>

                    <x-theme-editor-section title="أحجام الزوايا">
                        @foreach(['sm','md','lg','xl','2xl','3xl','4xl'] as $size)
                            <x-theme-text-field wire:model.live="corners.{{ $size }}" label="{{ $size }}" />
                        @endforeach
                    </x-theme-editor-section>
                @endif

                {{-- ══ SHADOWS TAB ══ --}}
                @if($activeTab === 'shadows')
                    <x-theme-editor-section title="الظلال">
                        <x-theme-text-field wire:model.live="glows.card_shadow"      label="كارد" />
                        <x-theme-text-field wire:model.live="glows.button_shadow"    label="زر" />
                        <x-theme-text-field wire:model.live="glows.input_shadow"     label="مدخل" />
                        <x-theme-text-field wire:model.live="glows.header_shadow"    label="هيدر" />
                        <x-theme-text-field wire:model.live="glows.modal_shadow"     label="مودال" />
                        <x-theme-text-field wire:model.live="glows.glow_shadow"      label="توهج" />
                    </x-theme-editor-section>
                @endif

                {{-- ══ ICONS TAB ══ --}}
                @if($activeTab === 'icons')
                    <x-theme-editor-section title="حزمة الأيقونات">
                        <div class="grid grid-cols-2 gap-2">
                            @foreach(array_keys(config('icons')) as $pack)
                                <button wire:click="$set('icon_pack', '{{ $pack }}')"
                                        @class([
                                            'flex items-center gap-2 px-3 py-2.5 rounded-xl border-2 text-xs font-medium transition-all text-start',
                                            'border-primary-500 bg-primary-50 dark:bg-primary-900/20 text-primary-700 dark:text-primary-300' => $icon_pack === $pack,
                                            'border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-400 hover:border-gray-300' => $icon_pack !== $pack,
                                        ])>
                                    <div class="w-6 h-6 rounded-lg flex items-center justify-center text-xs"
                                         style="{{ $icon_pack === $pack ? 'background-color: var(--color-primary); color: white' : 'background-color: #f3f4f6' }}">
                                        ●
                                    </div>
                                    {{ $pack }}
                                </button>
                            @endforeach
                        </div>
                    </x-theme-editor-section>
                @endif

            </div>
        </div>

        {{-- ─────────────────────────────────────────────
             RIGHT PANEL: Preview
        ──────────────────────────────────────────────── --}}
        <div class="flex-1 bg-gray-100 dark:bg-gray-950 flex flex-col overflow-hidden">
            {{-- Preview toolbar --}}
            <div class="flex items-center gap-3 px-4 py-2 bg-gray-200 dark:bg-gray-900 border-b border-gray-300 dark:border-gray-700 flex-shrink-0">
                <div class="flex gap-1.5">
                    <div class="w-3 h-3 rounded-full bg-red-400"></div>
                    <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                    <div class="w-3 h-3 rounded-full bg-green-400"></div>
                </div>
                <div class="flex-1 bg-white dark:bg-gray-800 rounded-md px-3 py-1 text-xs text-gray-400 font-mono">
                    {{ $this->previewUrl }}
                </div>
                <a href="{{ $this->previewUrl }}" target="_blank"
                   class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                    <x-heroicon-o-arrow-top-right-on-square class="w-4 h-4" />
                </a>
            </div>

            {{-- iframe wrapper --}}
            <div class="flex-1 flex items-start justify-center overflow-auto p-4">
                <div
                    :class="{
                        'w-full': previewDevice === 'desktop',
                        'w-[768px]': previewDevice === 'tablet',
                        'w-[390px]': previewDevice === 'mobile',
                    }"
                    class="transition-all duration-300 h-full relative"
                >
                    {{-- Loading overlay --}}
                    <div x-show="!iframeLoaded"
                         class="absolute inset-0 flex items-center justify-center bg-white dark:bg-gray-900 rounded-xl z-10">
                        <div class="flex flex-col items-center gap-3 text-gray-400">
                            <svg class="animate-spin w-8 h-8" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 12 0 12 12h4z"></path>
                            </svg>
                            <span class="text-sm">جاري تحميل المعاينة...</span>
                        </div>
                    </div>

                    <iframe
                        x-ref="preview"
                        src="{{ $this->previewUrl }}"
                        class="w-full h-full rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700"
                        style="min-height: calc(100vh - 10rem)"
                    ></iframe>
                </div>
            </div>
        </div>
    </div>
</div>