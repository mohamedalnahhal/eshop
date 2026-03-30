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
        'inputs'    => 'array',
        'glows'      => 'array',
        'corners'    => 'array',
    ];
 
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
            'navbar'      => '#ffffff',
            'footer'      => '#1f2937',
            'border'      => '#d1d5dc',
            'border_muted'=> '#f6f3f4',
            'border_input'=> '#dadbdd',
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
            'base_size'        => '16px',
            'base_weight'      => '400',
            'heading_weight'   => '700',
            'line_height'      => '1.6',
            'letter_spacing'   => 'normal',
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
 
    public static function defaultGlows()
    {
        return [
            'glow_shadow'   => '0 20px 25px -5px color-mix(in srgb, var(--color-primary) 10%, transparent), 0 8px 10px -6px color-mix(in srgb, var(--color-primary) 10%, transparent)',
            'card_shadow'   => 'var(--shadow-sm)',
            'button_shadow' => '0 1px 2px rgba(0,0,0,0.08)',
            'input_shadow'  => 'var(--shadow-sm)',
            'navbar_shadow' => '0 1px 4px rgba(0,0,0,0.06)',
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
        return 'heroicons';
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
            '--color-navbar'     => $p['navbar'],
            '--color-footer'     => $p['footer'],
            '--color-border'     => $p['border'],
            '--color-border-muted'  => $p['border_muted'],
            '--color-border-input'  => $p['border_input'],
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
 
            // buttons
            '--btn-px'          => $b['padding_x'],
            '--btn-py'          => $b['padding_y'],
            '--btn-font-weight' => $b['font_weight'],
            '--btn-uppercase'   => $b['uppercase'] ? 'uppercase' : 'none',

            // inputs
            '--input-px'          => $i['padding_x'],
            '--input-py'          => $i['padding_y'],
            '--input-font-weight' => $i['font_weight'],
 
            // glows / shadows
            '--shadow-glow'   => $g['glow_shadow'],
            '--shadow-card'   => $g['card_shadow'],
            '--shadow-btn'    => $g['button_shadow'],
            '--shadow-input'  => $g['input_shadow'],
            '--shadow-navbar' => $g['navbar_shadow'],
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

            // raidus, overwrite default tailwindcss classes
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
     * Format a money amount using this theme's currency config.
     */
    public function formatMoney(float $amount)
    {
        $c = $this->resolvedCurrency();
        $formatted = number_format($amount, $c['decimals']);
 
        return $c['position'] === 'before'
            ? $c['symbol'] . $formatted
            : $formatted . ' ' . $c['symbol'];
    }
 
    public function tenantSettings()
    {
        return $this->hasMany(TenantSetting::class);
    }
}