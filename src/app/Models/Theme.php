<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Theme extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'is_default',
        'currency',
        'palette',
        'font',
        'buttons',
        'inputs',
        'header',
        'm_header',
        'glows',
        'corners',
        'icon_pack',
    ];
 
    protected $casts = [
        'is_default' => 'boolean',
        'currency'   => 'array',
        'palette'    => 'array',
        'font'       => 'array',
        'buttons'    => 'array',
        'inputs'     => 'array',
        'header'     => 'array',
        'm_header'   => 'array',
        'glows'      => 'array',
        'corners'    => 'array',
    ];
 
    public static function getSymbol(string $currencyCode)
    {
        $formatter = new \NumberFormatter("en_US@currency={$currencyCode}", \NumberFormatter::CURRENCY);
        return $formatter->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);
    }

    // -------------------------------------------------------------------------
    // Defaults
    // -------------------------------------------------------------------------
 
    public static function defaultCurrency()
    {
        return [
            'position' => 'before',   // 'before' | 'after'
            'decimals' => 2,
        ];
    }
 
    public static function defaultPalette()
    {
        return [
            'primary'     => '#155dfc',
            'secondary'   => '#f3f4f6',
            'accent'      => '#00a63e',
            'on_primary'  => '#ffffff',
            'on_secondary'=> '#1e2939',
            'on_accent'   => '#ffffff',
            'background'  => '#ffffff',
            'card_bg'     => '#ffffff',
            'surface_100' => '#f3f4f6',
            'surface_200' => '#e5e7eb',
            'surface_300' => '#d1d5dc',
            'text'        => '#1e2939',
            'text_muted'  => '#6a7282',
            'header'      => '#ffffff',
            'on_header'   => '#1e2939',
            'm_header'    => '#ffffff',
            'on_m_header' => '#1e2939',
            'footer'      => '#1f2937',
            'on_footer'   => '#ffffff',
            'border'      => '#d1d5dc',
            'border_muted'=> '#f6f3f4',
            'border_input'=> '#dadbdd',
            'border_header'=> '#d1d5dc',
            'border_m_header'=> '#d1d5dc',
            'border_input_header'=> '#dadbdd',
            'border_input_m_header'=> '#dadbdd',
            'gold'        => '#fdc700',
            'gold_surface'=> '#fff2c1',
            'on_gold'     => '#c55100',
            'success'     => '#00b420',
            'warning'     => '#ee510d',
            'danger'      => '#e7000b',
            'info'        => '#3b82f6',
        ];
    }
 
    public static function defaultFont()
    {
        return [
            'primary_family'   => 'Tajawal, sans-serif',
            'secondary_family' => 'Tajawal, sans-serif',
            'base_weight'      => '400',
            'heading_weight'   => '700',
            'line_height'      => '1.6',
            'letter_spacing'   => 'normal',
            'xs'               => '0.75rem',
            'sm'               => '0.875rem',
            'base'             => '1rem',
            'lg'               => '1.125rem',
            'xl'               => '1.25rem',
            '2xl'              => '1.5rem',
            '3xl'              => '1.875rem',
            '4xl'              => '2.25rem',
            '5xl'              => '3rem',
            '6xl'              => '3.75rem',
            '7xl'              => '4.5rem',
            'lh-loose'         => 1.5,
            'lh-normal'        => 1.4,
            'lh-tight'         => 1.2,
            'lh-none'          => 1,
        ];
    }
 
    public static function defaultButtons()
    {
        return [
            'padding_x'   => '1.25rem',
            'padding_y'   => '0.625rem',
            'font_weight' => '700',
            'uppercase'   => false,
        ];
    }

    public static function defaultInputs()
    {
        return [
            'padding_x'   => '0.75rem',
            'padding_y'   => '0.5rem',
            'font_weight' => '700',
        ];
    }

    public static function defaultHeader()
    {
        return [
            'width'         => '100%',
            'content_width' => 'var(--container)',
            'padding_t'     => '2.5rem',
            'padding_b'     => '2rem',
            'margin_t'      => '0px',
            'margin_b'      => '1.5rem',
            'sticky_t'      => '0px',
            'content_padding_r' => 'var(--container-px)',
            'content_padding_l' => 'var(--container-px)',
            'content_margin_r'  => 'auto',
            'content_margin_l'  => 'auto',
            // TODO: rename to input_px and input_py
            'search_px'     => '1.25rem',
            'search_py'     => '0.75rem',
            'gap'           => '2rem',
            'position'      => 'static',
            'bg_opacity'    => '1',
            'backdrop_blur' => '0px',
            'title_weight'  => '800',
            'title_size'    => '2.25rem',
            'logo_width'    => '2.5rem',
            'logo_hight'    => '2.5rem',
            'icons_size'    => '2rem',
            'border_t'      => 'none',
            'border_b'      => '1px',
            'border_l'      => 'none',
            'border_r'      => 'none',
        ];
    }

    public static function defaultMobileHeader()
    {
        return [
            'width'         => '100%',
            'content_width' => 'var(--container)',
            'padding_t'     => '2rem',
            'padding_b'     => '1.5rem',
            'margin_t'      => '0px',
            'margin_b'      => '1.5rem',
            'sticky_t'      => '0px',
            'content_padding_r' => 'var(--container-px)',
            'content_padding_l' => 'var(--container-px)',
            'content_margin_r'  => 'auto',
            'content_margin_l'  => 'auto',
            // TODO: rename to input_px and input_py
            'search_px'     => '1.25rem',
            'search_py'     => '0.75rem',
            'gap'           => '2rem',
            'position'      => 'static',
            'bg_opacity'    => '1',
            'backdrop_blur' => '0px',
            'title_weight'  => '800',
            'title_size'    => '2.25rem',
            'logo_width'    => '2.5rem',
            'logo_hight'    => '2.5rem',
            'icons_size'    => '2rem',
            'border_t'      => 'none',
            'border_b'      => '1px',
            'border_l'      => 'none',
            'border_r'      => 'none',
        ];
    }
 
    public static function defaultGlows()
    {
        return [
            'glow_shadow'   => '0 20px 25px -5px color-mix(in srgb, var(--color-primary) 10%, transparent), 0 8px 10px -6px color-mix(in srgb, var(--color-primary) 10%, transparent)',
            'card_shadow'   => 'var(--shadow-sm)',
            'button_shadow' => '0 1px 2px rgba(0,0,0,0.08)',
            'input_shadow'  => 'var(--shadow-sm)',
            'header_shadow' => '0 1px 4px rgba(0,0,0,0.06)',
            'm_header_shadow' => '0 1px 4px rgba(0,0,0,0.06)',
            'modal_shadow'  => '0 20px 60px rgba(0,0,0,0.15)',
        ];
    }
 
    public static function defaultCorners()
    {
        return [
            'badge'         => '9999px',
            'model'         => '0.5rem',
            'btn'           => '0.75rem',
            'cta'           => '9999px',
            'input'         => '0.75rem',
            'input-full'    => '9999px',
            'card'          => '0.75rem',
            'icon'          => '0.5rem',
            'header'        => '0px',
            'm_header'      => '0px',

            'sm'            => '0.25rem',
            'md'            => '0.375rem',
            'lg'            => '0.5rem',
            'xl'            => '0.75rem',
            '2xl'           => '1rem',
            '3xl'           => '1.5rem',
            '4xl'           => '2rem',
            'full'          => '9999px',
        ];
    }
 
    public static function defaultIconPack()
    {
        return 'heroicon';
    }
 
    public function resolvedCurrency()
    {
        return array_merge(static::defaultCurrency(), $this->currency ?? []);
    }
 
    public function resolvedPalette()
    {
        return array_merge(static::defaultPalette(), $this->palette ?? []);
    }
 
    public function resolvedFont()
    {
        return array_merge(static::defaultFont(), $this->font ?? []);
    }
 
    public function resolvedButtons()
    {
        return array_merge(static::defaultButtons(), $this->buttons ?? []);
    }

    public function resolvedInputs()
    {
        return array_merge(static::defaultInputs(), $this->inputs ?? []);
    }

    public function resolvedHeader()
    {
        return array_merge(static::defaultHeader(), $this->header ?? []);
    }

    public function resolvedMobileHeader()
    {
        return array_merge(static::defaultMobileHeader(), $this->m_header ?? []);
    }
 
    public function resolvedGlows()
    {
        return array_merge(static::defaultGlows(), $this->glows ?? []);
    }
 
    public function resolvedCorners()
    {
        return array_merge(static::defaultCorners(), $this->corners ?? []);
    }
 
    public function resolvedIconPack()
    {
        return $this->icon_pack ?? static::defaultIconPack();
    }
 
    public function toCssVars()
    {
        $p  = $this->resolvedPalette();
        $f  = $this->resolvedFont();
        $b  = $this->resolvedButtons();
        $i  = $this->resolvedInputs();
        $h  = $this->resolvedHeader();
        $mh = $this->resolvedMobileHeader();
        $g  = $this->resolvedGlows();
        $c  = $this->resolvedCorners();

 
        $vars = [
            // palette
            '--color-primary'    => $p['primary'],
            '--color-secondary'  => $p['secondary'],
            '--color-accent'     => $p['accent'],
            '--color-on-primary'  => $p['on_primary'],
            '--color-on-secondary'=> $p['on_secondary'],
            '--color-on-accent'   => $p['on_accent'],
            '--color-bg'          => $p['background'],
            '--color-card-bg'     => $p['card_bg'],
            '--color-surface-100'  => $p['surface_100'],
            '--color-surface-200'  => $p['surface_200'],
            '--color-surface-300'  => $p['surface_300'],
            '--color-text'       => $p['text'],
            '--color-text-muted' => $p['text_muted'],
            '--color-header'     => $p['header'],
            '--color-on-header'  => $p['on_header'],
            '--color-m-header'    => $p['m_header'],
            '--color-on-m-header' => $p['on_m_header'],
            '--color-footer'     => $p['footer'],
            '--color-on-footer'  => $p['on_footer'],
            '--color-border'     => $p['border'],
            '--color-border-muted'  => $p['border_muted'],
            '--color-border-input'  => $p['border_input'],
            '--color-border-header' => $p['border_header'],
            '--color-border-m-header' => $p['border_m_header'],
            '--color-border-input-header' => $p['border_input_header'],
            '--color-border-input-m-header' => $p['border_input_m_header'],
            '--color-gold'          => $p['gold'],
            '--color-gold-surface'  => $p['gold_surface'],
            '--color-on-gold'       => $p['on_gold'],
            '--color-success'    => $p['success'],
            '--color-warning'    => $p['warning'],
            '--color-danger'     => $p['danger'],
            '--color-info'       => $p['info'],
 
            // font
            '--font-primary'        => $f['primary_family'],
            '--font-secondary'      => $f['secondary_family'],
            '--font-size-base'      => $f['base_size'],
            '--font-weight-base'    => $f['base_weight'],
            '--font-weight-heading' => $f['heading_weight'],
            '--line-height'         => $f['line_height'],
            '--letter-spacing'      => $f['letter_spacing'],
            '--theme-text-xs'       => $f['xs'],
            '--theme-text-sm'       => $f['sm'],
            '--theme-text-base'     => $f['base'],
            '--theme-text-lg'       => $f['lg'],
            '--theme-text-xl'       => $f['xl'],
            '--theme-text-2xl'      => $f['2xl'],
            '--theme-text-3xl'      => $f['3xl'],
            '--theme-text-4xl'      => $f['4xl'],
            '--theme-text-5xl'      => $f['5xl'],
            '--theme-text-6xl'      => $f['6xl'],
            '--theme-text-7xl'      => $f['7xl'],
            '--theme-text-lh-loose' => $f['lh-loose'],
            '--theme-text-lh-normal'=> $f['lh-normal'],
            '--theme-text-lh-tight' => $f['lh-tight'],
            '--theme-text-lh-none'  => $f['lh-none'],
 
            // buttons
            '--btn-px'          => $b['padding_x'],
            '--btn-py'          => $b['padding_y'],
            '--btn-font-weight' => $b['font_weight'],
            '--btn-uppercase'   => $b['uppercase'] ? 'uppercase' : 'none',

            // inputs
            '--input-px'          => $i['padding_x'],
            '--input-py'          => $i['padding_y'],
            '--input-font-weight' => $i['font_weight'],

            // header
            '--header-width'          => $h['width'],
            '--header-content-width'  => $h['content_width'],
            '--header-pt'             => $h['padding_t'],
            '--header-pb'             => $h['padding_b'],
            '--header-mt'             => $h['margin_t'],
            '--header-mb'             => $h['margin_b'],
            '--header-st'             => $h['sticky_t'],
            '--header-content-pr'     => $h['content_padding_r'],
            '--header-content-pl'     => $h['content_padding_l'],
            '--header-content-mr'     => $h['content_margin_r'],
            '--header-content-ml'     => $h['content_margin_l'],
            '--header-search-px'      => $h['search_px'],
            '--header-search-py'      => $h['search_py'],
            '--header-gap'            => $h['gap'],
            '--header-position'       => $h['position'],
            '--header-bg-opacity'     => $h['bg_opacity'],
            '--header-backdrop-blur'  => $h['backdrop_blur'],
            '--header-title-weight'   => $h['title_weight'],
            '--header-title-size'     => $h['title_size'],
            '--header-logo-width'     => $h['logo_width'],
            '--header-logo-hight'     => $h['logo_hight'],
            '--header-icons-size'     => $h['icons_size'],
            '--header-border-t'       => $mh['border_t'],
            '--header-border-b'       => $mh['border_b'],
            '--header-border-l'       => $mh['border_l'],
            '--header-border-r'       => $mh['border_r'],

            // mobile header
            '--m-header-width'          => $mh['width'],
            '--m-header-content-width'  => $mh['content_width'],
            '--m-header-pt'             => $mh['padding_t'],
            '--m-header-pb'             => $mh['padding_b'],
            '--m-header-mt'             => $mh['margin_t'],
            '--m-header-mb'             => $mh['margin_b'],
            '--m-header-st'             => $mh['sticky_t'],
            '--m-header-content-pr'     => $mh['content_padding_r'],
            '--m-header-content-pl'     => $mh['content_padding_l'],
            '--m-header-content-mr'     => $mh['content_margin_r'],
            '--m-header-content-ml'     => $mh['content_margin_l'],
            '--m-header-search-px'      => $mh['search_px'],
            '--m-header-search-py'      => $mh['search_py'],
            '--m-header-gap'            => $mh['gap'],
            '--m-header-position'       => $mh['position'],
            '--m-header-bg-opacity'     => $mh['bg_opacity'],
            '--m-header-backdrop-blur'  => $mh['backdrop_blur'],
            '--m-header-title-weight'   => $mh['title_weight'],
            '--m-header-title-size'     => $mh['title_size'],
            '--m-header-logo-width'     => $mh['logo_width'],
            '--m-header-logo-hight'     => $mh['logo_hight'],
            '--m-header-icons-size'     => $mh['icons_size'],
            '--m-header-border-t'       => $mh['border_t'],
            '--m-header-border-b'       => $mh['border_b'],
            '--m-header-border-l'       => $mh['border_l'],
            '--m-header-border-r'       => $mh['border_r'],
 
            // glows / shadows
            '--shadow-glow'   => $g['glow_shadow'],
            '--shadow-card'   => $g['card_shadow'],
            '--shadow-btn'    => $g['button_shadow'],
            '--shadow-input'  => $g['input_shadow'],
            '--shadow-header' => $g['header_shadow'],
            '--shadow-m-header' => $g['m_header_shadow'],
            '--shadow-modal'  => $g['modal_shadow'],
 
            // corners / radius
            '--radius-badge'        => $c['badge'],
            '--radius-model'        => $c['model'],
            '--radius-btn'          => $c['btn'],
            '--radius-cta'          => $c['cta'], // call to action buttons / links
            '--radius-input'        => $c['input'],
            '--radius-input-full'   => $c['input-full'],
            '--radius-card'         => $c['card'],
            '--radius-icon'         => $c['icon'],
            '--radius-header'       => $c['header'],
            '--radius-m-header'     => $c['m_header'], // mobile header

            // raidus, overwrite default tailwindcss classes with theme- prefix
            '--radius-sm'           => $c['sm'],
            '--radius-md'           => $c['md'],
            '--radius-lg'           => $c['lg'],
            '--radius-xl'           => $c['xl'],
            '--radius-2xl'          => $c['2xl'],
            '--radius-3xl'          => $c['3xl'],
            '--radius-4xl'          => $c['4xl'],
            '--radius-full'         => $c['full'],
        ];
 
        $lines = [':root {'];
        foreach ($vars as $key => $value) {
            $lines[] = "    {$key}: {$value};";
        }
        $lines[] = '}';
 
        return implode("\n", $lines);
    }
 
    /**
     * Format a price amount using this theme's currency config.
     */
    public function formatPrice(float $amount)
    {
        $c = $this->resolvedCurrency();
        $code = $this->tenantSettings?->currency ?? 'USD';
        $symbol = static::getSymbol($code);
        $decimals = $c['decimals'] ?? 2;
        $formatted = number_format($amount, $decimals);

        return $c['position'] === 'before'
            ? $symbol . $formatted
            : $formatted . ' ' . $symbol;
    }
 
    public function tenantSettings()
    {
        return $this->belongsTo(TenantSetting::class);
    }
}