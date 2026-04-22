<div
    x-data="{
        previewDevice: 'desktop',
        iframeLoaded: false,

        init() {
            Livewire.hook('morph.updated', () => {
                this.pushCss();
            });
        },

        onIframeLoad() {
            this.iframeLoaded = true;
            this.pushCss();
        },

        pushCss() {
            const iframe = this.$refs.preview;
            const cssData = document.getElementById('theme-css-data');
            
            if (!iframe || !iframe.contentDocument || !cssData) return;
            
            let style = iframe.contentDocument.getElementById('__theme_live__');
            if (!style) {
                style = iframe.contentDocument.createElement('style');
                style.id = '__theme_live__';
                iframe.contentDocument.head.appendChild(style);
            }
            
            style.textContent = cssData.textContent;
        }
    }"
    class="flex flex-col h-[calc(100vh-4rem)]"
    dir="rtl"
>
    {{-- The hidden element that carries the Livewire-generated CSS --}}
    <div id="theme-css-data" style="display: none;">
        {!! $this->liveCss !!}
    </div>

    {{-- TOP BAR --}}
    <div class="flex items-center justify-between px-4 py-3 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 flex-shrink-0">
        <div class="flex items-center gap-3">
            <a href="{{ route('filament.tenant_admin.pages.themes-page') }}"
               class="flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-900 transition-colors">
                {{-- Use x-filament::icon for the back button--}}
                <x-filament::icon icon="heroicon-o-arrow-right" class="w-4 h-4 rotate-180" />
                themes
            </a>
            <span class="text-gray-300">/</span>
            <span class="text-sm font-semibold text-gray-900 dark:text-white">Theme customization</span>
        </div>

        <div class="flex items-center gap-1 bg-gray-100 dark:bg-gray-800 rounded-lg p-1">
            @foreach([['desktop','computer-desktop','desktop'],['tablet','device-tablet','tablet'],['mobile','device-phone-mobile','mobile']] as [$d,$ic,$lb])
                <button @click="previewDevice = '{{ $d }}'"
                        :class="previewDevice === '{{ $d }}' ? 'bg-white dark:bg-gray-700 shadow-sm text-gray-900 dark:text-white' : 'text-gray-500 hover:text-gray-700'"
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-md text-xs font-medium transition-all">
                    {{-- Use x-filament::icon for the device buttons --}}
                    <x-filament::icon :icon="'heroicon-o-' . $ic" class="w-4 h-4" /> {{ $lb }}
                </button>
            @endforeach
        </div>

        <div class="flex items-center gap-2">
            <button wire:click="resetToDefaults"
                    wire:confirm="Are you sure you want to reset all changes? This action cannot be undone."
                    class="flex items-center gap-1.5 px-3 py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                {{-- reset button --}}
                <x-filament::icon icon="heroicon-o-arrow-path" class="w-4 h-4" /> reset
            </button>
            <button wire:click="save" wire:loading.attr="disabled"
                    class="flex items-center gap-1.5 px-4 py-2 text-sm font-semibold bg-primary-600 text-white rounded-lg hover:bg-primary-700 disabled:opacity-50 transition-colors shadow-sm">
                <span wire:loading.remove wire:target="save" class="flex items-center gap-1.5">
                    {{-- save button --}}
                    <x-filament::icon icon="heroicon-o-cloud-arrow-up" class="w-4 h-4" /> save
                </span>
                <span wire:loading wire:target="save" class="flex items-center gap-1.5">
                    <x-spinner class="w-4 h-4" /> Saving...
                </span>
            </button>
        </div>
    </div>

    {{-- MAIN --}}
    <div class="flex flex-1 overflow-hidden">

       {{-- LEFT: Editor --}}
        <div class="w-80 flex-shrink-0 bg-white dark:bg-gray-900 border-e border-gray-200 dark:border-gray-700 flex flex-col overflow-hidden">
            
            {{--  name theme field --}}
            <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 flex-shrink-0">
                <label for="themeName" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Theme Name
                </label>
                <input 
                    type="text" 
                    id="themeName" 
                    wire:model="themeName" 
                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:text-white transition-colors"
                    placeholder="Enter theme name..."
                >
                @error('themeName') 
                    <span class="text-xs text-red-600 dark:text-red-400 mt-1.5 block font-medium">{{ $message }}</span> 
                @enderror
            </div>
            {{-- end field--}}

            {{-- Tabs --}}
            <div class="flex overflow-x-auto border-b border-gray-200 dark:border-gray-700 px-1 pt-1 gap-0.5 flex-shrink-0 scrollbar-hide">
                @foreach([['palette','swatch','Colors'],['font','document-text','Font'],['layout','squares-2x2','Layout'],['corners','stop','Corners'],['shadows','sparkles','Shadows'],['icons','squares-plus','Icons']] as [$tab,$icon,$label])
                    <button wire:click="$set('activeTab', '{{ $tab }}')"
                            @class([
                                'flex items-center gap-1.5 px-3 py-2 border-b-2 text-xs font-medium transition-colors whitespace-nowrap',
                                'border-primary-500 text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20' => $activeTab === $tab,
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' => $activeTab !== $tab,
                            ])>
                        {{-- Use x-filament::icon for the tab icons --}}
                        <x-filament::icon :icon="'heroicon-o-' . $icon" class="w-4 h-4" /> {{ $label }}
                    </button>
                @endforeach
            </div>
            <div class="flex-1 overflow-y-auto p-4 space-y-5">
                @if($activeTab === 'palette')
                    <x-theme-editor-section title="colors">
                        <x-theme-color-field wire:model.live="palette.primary"      label="Primary" />
                        <x-theme-color-field wire:model.live="palette.on_primary"   label="On Primary" />
                        <x-theme-color-field wire:model.live="palette.secondary"    label="Secondary" />
                        <x-theme-color-field wire:model.live="palette.on_secondary" label="On Secondary" />
                        <x-theme-color-field wire:model.live="palette.accent"       label="Accent" />
                        <x-theme-color-field wire:model.live="palette.on_accent"    label="On Accent" />
                    </x-theme-editor-section>
                    <x-theme-editor-section title="backgrounds">
                        <x-theme-color-field wire:model.live="palette.background"  label="Background" />
                        <x-theme-color-field wire:model.live="palette.card_bg"     label="Card Background" />
                        <x-theme-color-field wire:model.live="palette.surface_100" label="Surface 100" />
                        <x-theme-color-field wire:model.live="palette.surface_200" label="Surface 200" />
                        <x-theme-color-field wire:model.live="palette.surface_300" label="Surface 300" />
                    </x-theme-editor-section>
                    <x-theme-editor-section title="Texts">
                        <x-theme-color-field wire:model.live="palette.text"       label="Text" />
                        <x-theme-color-field wire:model.live="palette.text_muted" label="Muted Text" />
                    </x-theme-editor-section>
                    <x-theme-editor-section title="Header">
                        <x-theme-color-field wire:model.live="palette.header"      label="Header Background" />
                        <x-theme-color-field wire:model.live="palette.on_header"   label="On Header" />
                        <x-theme-color-field wire:model.live="palette.m_header"    label="Mobile Header" />
                        <x-theme-color-field wire:model.live="palette.on_m_header" label="On Mobile Header" />
                    </x-theme-editor-section>
                    <x-theme-editor-section title="Footer">
                        <x-theme-color-field wire:model.live="palette.footer"    label="Footer Background" />
                        <x-theme-color-field wire:model.live="palette.on_footer" label="On Footer" />
                    </x-theme-editor-section>
                    <x-theme-editor-section title="Status Colors">
                        <x-theme-color-field wire:model.live="palette.success" label="Success" />
                        <x-theme-color-field wire:model.live="palette.warning" label="Warning" />
                        <x-theme-color-field wire:model.live="palette.danger"  label="Danger" />
                        <x-theme-color-field wire:model.live="palette.info"    label="Info" />
                    </x-theme-editor-section>
                    <x-theme-editor-section title="Gold">
                        <x-theme-color-field wire:model.live="palette.gold"         label="Gold" />
                        <x-theme-color-field wire:model.live="palette.gold_surface" label="Gold Surface" />
                        <x-theme-color-field wire:model.live="palette.on_gold"      label="On Gold" />
                    </x-theme-editor-section>
                    <x-theme-editor-section title="Borders">
                        <x-theme-color-field wire:model.live="palette.border"       label="Border" />
                        <x-theme-color-field wire:model.live="palette.border_muted" label="Muted Border" />
                        <x-theme-color-field wire:model.live="palette.border_input" label="Input Border" />
                    </x-theme-editor-section>
                @endif

                @if($activeTab === 'font')
                    <x-theme-editor-section title="Font Families">
                        <x-theme-text-field wire:model.live="font.primary_family"   label="Primary Font"  placeholder="Tajawal, sans-serif" />
                        <x-theme-text-field wire:model.live="font.secondary_family" label="Heading Font"   placeholder="Tajawal, sans-serif" />
                    </x-theme-editor-section>
                    <x-theme-editor-section title="Font Weights and Spacing">
                        <x-theme-select-field wire:model.live="font.base_weight"    label="Base Weight"    :options="['300'=>'Light 300','400'=>'Regular 400','500'=>'Medium 500','600'=>'SemiBold 600','700'=>'Bold 700']" />
                        <x-theme-select-field wire:model.live="font.heading_weight" label="Heading Weight" :options="['400'=>'Regular 400','500'=>'Medium 500','600'=>'SemiBold 600','700'=>'Bold 700','800'=>'ExtraBold 800','900'=>'Black 900']" />
                        <x-theme-text-field   wire:model.live="font.line_height"    label="Line Height" placeholder="1.6" />
                        <x-theme-text-field   wire:model.live="font.letter_spacing" label="Letter Spacing" placeholder="normal" />
                    </x-theme-editor-section>
                    <x-theme-editor-section title="Font Sizes">
                        @foreach(['xs'=>'XS','sm'=>'SM','base'=>'Base','lg'=>'LG','xl'=>'XL','2xl'=>'2XL','3xl'=>'3XL','4xl'=>'4XL'] as $k => $lb)
                            <x-theme-text-field wire:model.live="font.{{ $k }}" label="{{ $lb }}" />
                        @endforeach
                    </x-theme-editor-section>
                @endif

                @if($activeTab === 'layout')
                    <x-theme-editor-section title="Buttons">
                        <x-theme-text-field   wire:model.live="buttons.padding_x"   label="Horizontal Padding"  placeholder="1.25rem" />
                        <x-theme-text-field   wire:model.live="buttons.padding_y"   label="Vertical Padding"  placeholder="0.625rem" />
                        <x-theme-select-field wire:model.live="buttons.font_weight" label="Font Weight"    :options="['400'=>'400','500'=>'500','600'=>'600','700'=>'700','800'=>'800']" />
                        <div class="flex items-center justify-between py-1">
                            <span class="text-xs text-gray-600 dark:text-gray-400">Uppercase Letters</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" wire:model.live="buttons.uppercase" class="sr-only peer">
                                <div class="w-9 h-5 bg-gray-200 peer-focus:ring-2 peer-focus:ring-primary-300 dark:bg-gray-700 rounded-full peer peer-checked:after:translate-x-full peer-checked:bg-primary-600 after:content-[''] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all"></div>
                            </label>
                        </div>
                    </x-theme-editor-section>
                    <x-theme-editor-section title="Input Fields">
                        <x-theme-text-field   wire:model.live="inputs.padding_x"   label="Horizontal Padding" placeholder="0.75rem" />
                        <x-theme-text-field   wire:model.live="inputs.padding_y"   label="Vertical Padding" placeholder="0.5rem" />
                        <x-theme-select-field wire:model.live="inputs.font_weight" label="Font Weight"   :options="['400'=>'400','500'=>'500','600'=>'600','700'=>'700']" />
                    </x-theme-editor-section>
                    <x-theme-editor-section title="Header">
                        <x-theme-text-field   wire:model.live="header.padding_t"     label="Top Padding" />
                        <x-theme-text-field   wire:model.live="header.padding_b"     label="Bottom Padding" />
                        <x-theme-text-field   wire:model.live="header.margin_b"      label="Bottom Margin" />
                        <x-theme-text-field   wire:model.live="header.gap"           label="Gap Between Elements" />
                        <x-theme-text-field   wire:model.live="header.logo_width"    label="Logo Width" />
                        <x-theme-text-field   wire:model.live="header.logo_hight"    label="Logo Height" />
                        <x-theme-select-field wire:model.live="header.position"      label="Position"     :options="['static'=>'Static','sticky'=>'Sticky','fixed'=>'Fixed']" />
                        <x-theme-text-field   wire:model.live="header.bg_opacity"    label="Background Opacity" />
                        <x-theme-text-field   wire:model.live="header.backdrop_blur" label="Backdrop Blur" />
                    </x-theme-editor-section>
                    <x-theme-editor-section title="Footer">
                        <x-theme-text-field   wire:model.live="footer.padding_t" label="Top Padding" />
                        <x-theme-text-field   wire:model.live="footer.padding_b" label="Bottom Padding" />
                        <x-theme-text-field   wire:model.live="footer.margin_t"  label="Top Margin" />
                        <x-theme-select-field wire:model.live="footer.columns"   label="Columns"    :options="[1=>'1 Column',2=>'2 Columns',3=>'3 Columns']" />
                    </x-theme-editor-section>
                @endif

                @if($activeTab === 'corners')
                    <x-theme-editor-section title="Store Items">
                        <x-theme-text-field wire:model.live="corners.btn"    label="Buttons" />
                        <x-theme-text-field wire:model.live="corners.cta"    label="CTA" />
                        <x-theme-text-field wire:model.live="corners.input"  label="Input Fields" />
                        <x-theme-text-field wire:model.live="corners.card"   label="Cards" />
                        <x-theme-text-field wire:model.live="corners.badge"  label="Badges" />
                        <x-theme-text-field wire:model.live="corners.icon"   label="Icons" />
                        <x-theme-text-field wire:model.live="corners.model"  label="Modal" />
                        <x-theme-text-field wire:model.live="corners.header" label="Header" />
                    </x-theme-editor-section>
                    <x-theme-editor-section title="Corner Sizes">
                        @foreach(['sm','md','lg','xl','2xl','3xl','4xl'] as $size)
                            <x-theme-text-field wire:model.live="corners.{{ $size }}" label="{{ $size }}" />
                        @endforeach
                    </x-theme-editor-section>
                @endif

                @if($activeTab === 'shadows')
                    <x-theme-editor-section title="shadows">
                        <x-theme-text-field wire:model.live="glows.card_shadow"   label="card" />
                        <x-theme-text-field wire:model.live="glows.button_shadow" label="button" />
                        <x-theme-text-field wire:model.live="glows.input_shadow"  label="input" />
                        <x-theme-text-field wire:model.live="glows.header_shadow" label="header" />
                        <x-theme-text-field wire:model.live="glows.modal_shadow"  label="modal" />
                        <x-theme-text-field wire:model.live="glows.glow_shadow"   label="glow" />
                    </x-theme-editor-section>
                @endif

                @if($activeTab === 'icons')
                    <x-theme-editor-section title="Icon Pack">
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

        {{-- RIGHT: iframe Preview --}}
        <div class="flex-1 bg-gray-100 dark:bg-gray-950 flex flex-col overflow-hidden">

            {{-- Preview toolbar --}}
            <div class="flex items-center gap-3 px-4 py-2 bg-gray-200 dark:bg-gray-900 border-b border-gray-300 dark:border-gray-700 flex-shrink-0">
                <div class="flex gap-1.5">
                    <div class="w-3 h-3 rounded-full bg-red-400"></div>
                    <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                    <div class="w-3 h-3 rounded-full bg-green-400"></div>
                </div>
                <div class="flex-1 bg-white dark:bg-gray-800 rounded-md px-3 py-1 text-xs text-gray-400 font-mono truncate">
                    {{ $this->previewUrl }}
                </div>
                <a href="{{ $this->previewUrl }}" target="_blank"
                   class="text-gray-400 hover:text-gray-600 transition-colors shrink-0">
                    {{-- Open in new tab --}}
                    <x-filament::icon icon="heroicon-o-arrow-top-right-on-square" class="w-4 h-4" />
                </a>
            </div>

            {{-- iframe wrapper --}}
            <div class="flex-1 flex items-start justify-center overflow-auto p-4">
                <div
                    :class="{
                        'w-full':     previewDevice === 'desktop',
                        'w-[768px]':  previewDevice === 'tablet',
                        'w-[390px]':  previewDevice === 'mobile',
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
                            <span class="text-sm">Loading preview...</span>
                        </div>
                    </div>

                    <iframe
                        x-ref="preview"
                        src="{{ $this->previewUrl }}"
                        @load="onIframeLoad()"
                        class="w-full h-full rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700"
                        style="min-height: calc(100vh - 10rem)"
                    ></iframe>
                </div>
            </div>
            
        </div>

    </div>
</div>