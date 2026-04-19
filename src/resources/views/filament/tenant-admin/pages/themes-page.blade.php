<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">ثيمات المتجر</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">اختر ثيم لمتجرك أو خصّص أحد الثيمات المتاحة</p>
            </div>
        </div>

        {{-- Themes Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($this->getThemes() as $theme)
                @php
                    $palette = $theme->resolvedPalette();
                    $isActive = $theme->id === $this->getActiveThemeId();
                @endphp

                <div @class([
                    'relative rounded-2xl border-2 overflow-hidden transition-all duration-200 group',
                    'border-primary-500 shadow-lg shadow-primary-100 dark:shadow-primary-900/30' => $isActive,
                    'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600 hover:shadow-md' => !$isActive,
                ])>
                    {{-- Active badge --}}
                    @if($isActive)
                        <div class="absolute top-3 right-3 z-10 flex items-center gap-1 bg-primary-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">
                            @icon('heroicon-s-check', 'w-3 h-3')
                            مفعّل
                        </div>
                    @endif

                    {{-- Color Preview --}}
                    <div class="h-32 relative overflow-hidden" style="background-color: {{ $palette['background'] }}">
                        {{-- Header strip --}}
                        <div class="h-9 flex items-center px-3 gap-2" style="background-color: {{ $palette['header'] }}; border-bottom: 1px solid {{ $palette['border_header'] }}">
                            <div class="w-3 h-3 rounded-full" style="background-color: {{ $palette['primary'] }}"></div>
                            <div class="h-2 rounded-full w-16" style="background-color: {{ $palette['on_header'] }}; opacity: 0.3"></div>
                            <div class="ms-auto flex gap-1">
                                <div class="w-5 h-5 rounded" style="background-color: {{ $palette['primary'] }}; opacity:0.7"></div>
                                <div class="w-5 h-5 rounded" style="background-color: {{ $palette['on_header'] }}; opacity:0.15"></div>
                            </div>
                        </div>
                        {{-- Content preview --}}
                        <div class="p-3 flex gap-2">
                            <div class="flex-1 space-y-2">
                                <div class="h-2 rounded-full w-3/4" style="background-color: {{ $palette['text'] }}; opacity:0.2"></div>
                                <div class="h-2 rounded-full w-1/2" style="background-color: {{ $palette['text'] }}; opacity:0.1"></div>
                                <div class="mt-3 inline-flex h-5 w-16 rounded-lg items-center justify-center" style="background-color: {{ $palette['primary'] }}">
                                    <div class="h-1.5 w-8 rounded-full bg-white opacity-70"></div>
                                </div>
                            </div>
                            <div class="w-20 h-16 rounded-xl" style="background-color: {{ $palette['card_bg'] }}; border: 1px solid {{ $palette['border_muted'] }}">
                                <div class="w-full h-9 rounded-t-xl" style="background-color: {{ $palette['surface_100'] }}"></div>
                                <div class="p-1.5 space-y-1">
                                    <div class="h-1.5 rounded w-full" style="background-color: {{ $palette['text'] }}; opacity:0.2"></div>
                                    <div class="h-1.5 rounded w-2/3" style="background-color: {{ $palette['accent'] }}; opacity:0.6"></div>
                                </div>
                            </div>
                        </div>
                        {{-- Color dots --}}
                        <div class="absolute bottom-2 left-3 flex gap-1.5">
                            @foreach(['primary','accent','success','warning'] as $key)
                                <div class="w-4 h-4 rounded-full border-2 border-white/50 shadow-sm" style="background-color: {{ $palette[$key] }}"></div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Info + actions --}}
                    <div class="p-4 bg-white dark:bg-gray-800 space-y-3">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white text-sm">{{ $theme->name }}</h3>
                            @if($theme->tenant_id === null)
                                <span class="text-xs text-gray-400">ثيم عام</span>
                            @else
                                <span class="text-xs text-primary-500">ثيمك المخصص</span>
                            @endif
                        </div>

                        <div class="flex gap-2">
                            {{-- Customize button --}}
                            <a href="{{ \App\Filament\TenantAdmin\Pages\ThemeEditorPage::getUrl() . '?themeId=' . $theme->id }}"
                               class="flex-1 flex items-center justify-center gap-1.5 text-xs font-medium px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                @icon('heroicon-o-paint-brush', 'w-3.5 h-3.5')
                                تخصيص
                            </a>

                            {{-- Activate button --}}
                            @if(!$isActive)
                                <button wire:click="setDefault('{{ $theme->id }}')"
                                        wire:loading.attr="disabled"
                                        class="flex-1 flex items-center justify-center gap-1.5 text-xs font-medium px-3 py-2 rounded-lg bg-primary-500 text-white hover:bg-primary-600 transition-colors disabled:opacity-50">
                                    @icon('heroicon-o-check', 'w-3.5 h-3.5')
                                    تفعيل
                                </button>
                            @else
                                <span class="flex-1 flex items-center justify-center gap-1.5 text-xs font-medium px-3 py-2 rounded-lg bg-primary-50 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400">
                                    @icon('heroicon-s-check', 'w-3.5 h-3.5') />
                                    مفعّل
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Create new theme card --}}
            <div class="rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-700 hover:border-primary-300 dark:hover:border-primary-600 transition-colors flex flex-col items-center justify-center gap-3 h-full min-h-[220px] group cursor-pointer">
                <div class="w-12 h-12 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center group-hover:bg-primary-50 dark:group-hover:bg-primary-900/30 transition-colors">
                   @icon('heroicon-o-plus', 'w-6 h-6 text-gray-400 group-hover:text-primary-500 transition-colors') />
                </div>
                <div class="text-center">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">ثيم جديد</p>
                    <p class="text-xs text-gray-400 dark:text-gray-500">أنشئ ثيماً مخصصاً</p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>