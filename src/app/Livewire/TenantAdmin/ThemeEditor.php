<?php

namespace App\Livewire\TenantAdmin; 

use App\Models\Theme;
use Livewire\Component;
use Filament\Notifications\Notification;

class ThemeEditor extends Component
{
    public string $themeId;
    public string $activeTab = 'palette';

    // ── live state (not yet persisted) ──────────────────────────────────────
    public array $palette   = [];
    public array $font      = [];
    public array $buttons   = [];
    public array $inputs    = [];
    public array $header    = [];
    public array $m_header  = [];
    public array $glows     = [];
    public array $corners   = [];
    public array $footer    = [];
    public string $icon_pack = '';

    // ── internal ─────────────────────────────────────────────────────────────
    protected Theme $theme;

    public function mount(string $themeId): void
    {
        $this->themeId = $themeId;

        $this->theme = Theme::where('id', $themeId)
            ->where(function ($q) {
                $q->where('tenant_id', tenant()->id)
                  ->orWhereNull('tenant_id');
            })->firstOrFail();

        $this->palette   = $this->theme->resolvedPalette();
        $this->font      = $this->theme->resolvedFont();
        $this->buttons   = $this->theme->resolvedButtons();
        $this->inputs    = $this->theme->resolvedInputs();
        $this->header    = $this->theme->resolvedHeader();
        $this->m_header  = $this->theme->resolvedMobileHeader();
        $this->glows     = $this->theme->resolvedGlows();
        $this->corners   = $this->theme->resolvedCorners();
        $this->footer    = $this->theme->resolvedFooter();
        $this->icon_pack = $this->theme->resolvedIconPack();
    }

    // ── compute live CSS to push to preview ──────────────────────────────────
    public function getLiveCssProperty(): string
    {
        $p  = array_merge(Theme::defaultPalette(),  $this->palette);
        $f  = array_merge(Theme::defaultFont(),     $this->font);
        $b  = array_merge(Theme::defaultButtons(),  $this->buttons);
        $i  = array_merge(Theme::defaultInputs(),   $this->inputs);
        $h  = array_merge(Theme::defaultHeader(),   $this->header);
        $mh = array_merge(Theme::defaultMobileHeader(), $this->m_header);
        $g  = array_merge(Theme::defaultGlows(),    $this->glows);
        $c  = array_merge(Theme::defaultCorners(),  $this->corners);
        $fo = array_merge(Theme::defaultFooter(),   $this->footer);

        // Reuse the model's method by temporarily overwriting
        $fake = new Theme([
            'palette'  => $p,
            'font'     => $f,
            'buttons'  => $b,
            'inputs'   => $i,
            'header'   => $h,
            'm_header' => $mh,
            'glows'    => $g,
            'corners'  => $c,
            'footer'   => $fo,
        ]);

        return $fake->toCssVars();
    }

    // ── save to DB ────────────────────────────────────────────────────────────
    public function save(): void
    {
        $this->theme = Theme::findOrFail($this->themeId);

        // If global theme, clone it for this tenant first
        if ($this->theme->tenant_id === null) {
            $this->theme = $this->theme->replicate();
            $this->theme->tenant_id = tenant()->id;
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
            'icon_pack' => $this->icon_pack,
        ]);

        $this->themeId = $this->theme->id;

        Notification::make()
            ->title('تم حفظ الثيم بنجاح')
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
        $this->icon_pack = Theme::defaultIconPack();
    }

    public function getPreviewUrlProperty(): string
    {
        return route('shop.index');
    }

    public function render()
    {
        return view('components.theme-editor');        // return view('livewire.tenant-admin.theme-editor');
    }
}