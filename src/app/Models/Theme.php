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
        'glows'      => 'array',
        'corners'    => 'array',
    ];
 
    // -------------------------------------------------------------------------
    // Defaults
    // -------------------------------------------------------------------------
 
    public static function defaultCurrency()
    {
        return [
            'code'     => 'USD',
            'symbol'   => '$',
            'position' => 'before',   // 'before' | 'after'
            'decimals' => 2,
        ];
    }
 
    public static function defaultPalette()
    {
        return [
            'primary'     => '#4f46e5',
            'secondary'   => '#7c3aed',
            'accent'      => '#f59e0b',
            'background'  => '#ffffff',
            'surface'     => '#f9fafb',
            'text'        => '#111827',
            'text_muted'  => '#6b7280',
            'navbar'      => '#ffffff',
            'footer'      => '#1f2937',
            'border'      => '#e5e7eb',
            'success'     => '#10b981',
            'warning'     => '#f59e0b',
            'danger'      => '#ef4444',
            'info'        => '#3b82f6',
        ];
    }
 
    public static function defaultFont()
    {
        return [
            'primary_family'   => 'Tajawal, sans-serif',
            'secondary_family' => 'Tajawal, sans-serif',
            'base_size'        => '16px',
            'h1_size'          => '2.25rem',
            'base_weight'      => '400',
            'heading_weight'   => '700',
            'line_height'      => '1.6',
            'letter_spacing'   => 'normal',
        ];
    }
 
    public static function defaultButtons()
    {
        return [
            'radius'      => '0.5rem',
            'padding_x'   => '1.25rem',
            'padding_y'   => '0.625rem',
            'font_weight' => '600',
            'uppercase'   => false,
            'shadow'      => 'sm',        // 'none'|'sm'|'md'|'lg'
        ];
    }
 
    public static function defaultGlows()
    {
        return [
            'card_shadow'   => '0 1px 3px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.06)',
            'button_shadow' => '0 1px 2px rgba(0,0,0,0.08)',
            'input_shadow'  => 'none',
            'navbar_shadow' => '0 1px 4px rgba(0,0,0,0.06)',
            'modal_shadow'  => '0 20px 60px rgba(0,0,0,0.15)',
        ];
    }
 
    public static function defaultCorners()
    {
        return [
            'sm'   => '0.25rem',
            'md'   => '0.375rem',
            'lg'   => '0.5rem',
            'xl'   => '0.75rem',
            'full' => '9999px',
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
        $g  = $this->resolvedGlows();
        $c  = $this->resolvedCorners();
 
        $btnShadowMap = [
            'none' => 'none',
            'sm'   => '0 1px 2px rgba(0,0,0,.08)',
            'md'   => '0 4px 6px rgba(0,0,0,.1)',
            'lg'   => '0 10px 15px rgba(0,0,0,.12)',
        ];
 
        $vars = [
            // Palette
            '--color-primary'    => $p['primary'],
            '--color-secondary'  => $p['secondary'],
            '--color-accent'     => $p['accent'],
            '--color-bg'         => $p['background'],
            '--color-surface'    => $p['surface'],
            '--color-text'       => $p['text'],
            '--color-text-muted' => $p['text_muted'],
            '--color-navbar'     => $p['navbar'],
            '--color-footer'     => $p['footer'],
            '--color-border'     => $p['border'],
            '--color-success'    => $p['success'],
            '--color-warning'    => $p['warning'],
            '--color-danger'     => $p['danger'],
            '--color-info'       => $p['info'],
 
            // Font
            '--font-primary'        => $f['primary_family'],
            '--font-secondary'      => $f['secondary_family'],
            '--font-size-base'      => $f['base_size'],
            '--font-weight-base'    => $f['base_weight'],
            '--font-weight-heading' => $f['heading_weight'],
            '--line-height'         => $f['line_height'],
            '--letter-spacing'      => $f['letter_spacing'],
 
            // Buttons
            '--btn-radius'      => $b['radius'],
            '--btn-px'          => $b['padding_x'],
            '--btn-py'          => $b['padding_y'],
            '--btn-font-weight' => $b['font_weight'],
            '--btn-shadow'      => $btnShadowMap[$b['shadow']] ?? 'none',
            '--btn-uppercase'   => $b['uppercase'] ? 'uppercase' : 'none',
 
            // Glows / shadows
            '--shadow-card'   => $g['card_shadow'],
            '--shadow-btn'    => $g['button_shadow'],
            '--shadow-input'  => $g['input_shadow'],
            '--shadow-navbar' => $g['navbar_shadow'],
            '--shadow-modal'  => $g['modal_shadow'],
 
            // Corners / radius
            '--radius-sm'   => $c['sm'],
            '--radius-md'   => $c['md'],
            '--radius-lg'   => $c['lg'],
            '--radius-xl'   => $c['xl'],
            '--radius-full' => $c['full'],
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