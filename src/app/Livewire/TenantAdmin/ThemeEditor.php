<?php

namespace App\Livewire\TenantAdmin;

use App\Models\Theme;
use Livewire\Component;
use Filament\Notifications\Notification;

class ThemeEditor extends Component
{
    public string $themeId;
    public string $activeTab = 'palette';

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

    // ── Live CSS - يُعاد حسابه مع كل تغيير ──────────────────────────────
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

        return $fake->toCssVars();
    }

    // ── Save ─────────────────────────────────────────────────────────────
    public function save(): void
    {
        $this->theme = Theme::findOrFail($this->themeId);

        // إذا ثيم عام، نعمل نسخة للتاجر
        if ($this->theme->tenant_id === null) {
            $this->theme = $this->theme->replicate();
            $this->theme->tenant_id  = tenant()->id;
            $this->theme->is_default = false;
        }

        $this->theme->update([
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

        $this->themeId = $this->theme->id;

        Notification::make()
            ->title('تم حفظ الثيم بنجاح ✓')
            ->success()
            ->send();
    }

    // ── Reset ─────────────────────────────────────────────────────────────
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

    public function render()
    {
        return view('components.theme-editor');
    }
}