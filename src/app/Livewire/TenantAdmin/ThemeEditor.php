<?php

namespace App\Livewire\TenantAdmin;

use App\Models\Theme;
use Livewire\Component;
use Filament\Notifications\Notification;
use Illuminate\Validation\Rule;

class ThemeEditor extends Component
{
    public string $themeId  = '';
    public string $activeTab = 'palette';
    public string $themeName = '';

    public array  $palette   = [];
    public array  $font      = [];
    public array  $buttons   = [];
    public array  $inputs    = [];
    public array  $header    = [];
    public array  $m_header  = [];
    public array  $glows     = [];
    public array  $corners   = [];
    public array  $footer    = [];
    public array  $homepage  = [];
    public string $icon_pack = '';

    protected Theme $theme;

    public function mount(string $themeId): void
    {
        $this->themeId = $themeId;

        $this->theme = Theme::where('id', $themeId)
            ->where(fn($q) => $q->where('tenant_id', tenant()->id)->orWhereNull('tenant_id'))
            ->firstOrFail();
            $this->themeName = $this->theme->name;


        $this->palette   = $this->theme->resolvedPalette();
        $this->font      = $this->theme->resolvedFont();
        $this->buttons   = $this->theme->resolvedButtons();
        $this->inputs    = $this->theme->resolvedInputs();
        $this->header    = $this->theme->resolvedHeader();
        $this->m_header  = $this->theme->resolvedMobileHeader();
        $this->glows     = $this->theme->resolvedGlows();
        $this->corners   = $this->theme->resolvedCorners();
        $this->footer    = $this->theme->resolvedFooter();
        $this->homepage  = $this->theme->resolvedHomepage();
        $this->icon_pack = $this->theme->resolvedIconPack();
    }

    public function getLiveCssProperty(): string
    {
        $fake = new Theme([
            'palette'   => array_merge(Theme::defaultPalette(),      $this->palette),
            'font'      => array_merge(Theme::defaultFont(),         $this->font),
            'buttons'   => array_merge(Theme::defaultButtons(),      $this->buttons),
            'inputs'    => array_merge(Theme::defaultInputs(),       $this->inputs),
            'header'    => array_merge(Theme::defaultHeader(),       $this->header),
            'm_header'  => array_merge(Theme::defaultMobileHeader(), $this->m_header),
            'glows'     => array_merge(Theme::defaultGlows(),        $this->glows),
            'corners'   => array_merge(Theme::defaultCorners(),      $this->corners),
            'footer'    => array_merge(Theme::defaultFooter(),       $this->footer),
        ]);

        return $fake->toCssVars(); // :root { ... }
    }

   public function save(): void
    {   
        $theme = Theme::where('id', $this->themeId)
            ->where(fn($q) => $q->where('tenant_id', tenant()->id)->orWhereNull('tenant_id'))
            ->firstOrFail();

        $this->validate([
            'themeName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('themes', 'name')
                    ->where('tenant_id', tenant()->id)
                    ->ignore($theme->tenant_id !== null ? $this->themeId : null)
            ],
        ], [
            'themeName.unique' => 'You already have a theme with this name. Please choose a different name.',
            'themeName.required' => 'Theme name is required.',
        ]);

        if ($theme->tenant_id === null) {
            $newTheme = $theme->replicate();
            $newTheme->tenant_id  = tenant()->id;
            $newTheme->name       = $this->themeName;
            $newTheme->is_default = false;
            $newTheme->palette    = $this->palette;
            $newTheme->font       = $this->font;
            $newTheme->buttons    = $this->buttons;
            $newTheme->inputs     = $this->inputs;
            $newTheme->header     = $this->header;
            $newTheme->m_header   = $this->m_header;
            $newTheme->glows      = $this->glows;
            $newTheme->corners    = $this->corners;
            $newTheme->footer     = $this->footer;
            $newTheme->homepage   = $this->homepage;
            $newTheme->icon_pack  = $this->icon_pack;
            $newTheme->save();
            $this->themeId = (string) $newTheme->id;
           $savedThemeId  = (string) $newTheme->id;
        } else {
            $theme->update([
                'name'      => $this->themeName,
                'palette'   => $this->palette,
                'font'      => $this->font,
                'buttons'   => $this->buttons,
                'inputs'    => $this->inputs,
                'header'    => $this->header,
                'm_header'  => $this->m_header,
                'glows'     => $this->glows,
                'corners'   => $this->corners,
                'footer'    => $this->footer,
                'homepage'  => $this->homepage,
                'icon_pack' => $this->icon_pack,
            ]);
            $savedThemeId = $this->themeId;
        }

        tenant()->settings()->updateOrCreate(
            ['tenant_id' => tenant()->id],
            ['theme_id'  => $savedThemeId]
        );
        tenant()->refresh();

        Notification::make()
            ->title('Theme saved and activated successfully ✓')
            ->success()
            ->send();
    }

    public function resetToDefaults(): void
    {
        $this->palette   = Theme::defaultPalette();
        $this->font      = Theme::defaultFont();
        $this->buttons   = Theme::defaultButtons();
        $this->inputs    = Theme::defaultInputs();
        $this->header    = Theme::defaultHeader();
        $this->m_header  = Theme::defaultMobileHeader();
        $this->glows     = Theme::defaultGlows();
        $this->corners   = Theme::defaultCorners();
        $this->footer    = Theme::defaultFooter();
        $this->homepage  = Theme::defaultHomepage();
        $this->icon_pack = Theme::defaultIconPack();
    }

    public function getPreviewUrlProperty(): string
    {
        return route('shop.index', [
            'locale' => app(\App\Services\TenantLocaleService::class)->getDefaultLocale()
        ]);
    }

    public function render()
    {
        return view('components.theme-editor');
    }
    protected function rules()
{
    return [
        'themeName' => [
            'required',
            'string',
            'max:255',
            Rule::unique('themes', 'name')->ignore($this->themeId),
        ],
    ];
    }
}