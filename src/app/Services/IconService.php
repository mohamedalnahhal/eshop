<?php

namespace App\Services;

use App\Models\Theme;
use BladeUI\Icons\Factory as IconFactory;

class IconService
{
    public function render(string $name, string $classes = ''): string
    {
        $pack = $this->activePack();
        $mappedName = config("icons.{$pack}.{$name}", $name);
        
        $prefixMap = [
            'heroicon_solid' => 'heroicon',
            'bi_solid'       => 'bi',
            'clarity_solid'  => 'clarity',
            'eos_solid'      => 'eos',
            'eva_solid'      => 'eva',
            'fwb_solid'      => 'flowbite',
            'gmdi_solid'     => 'gmdi',
            'tabler_solid'   => 'tabler',
            'tni_solid'      => 'tni',
        ];
        $prefix = $prefixMap[$pack] ?? $pack;

        $fullIconString = "{$prefix}-{$mappedName}";

        try {
            $svg = app(IconFactory::class)->svg($fullIconString, $classes);
            $html = $svg->toHtml();

            $transform = '';

            // handle clarity arrows pointing up ('clarity' and 'clarity-solid')
            if (str_starts_with($pack, 'clarity') && in_array($name, ['arrow-r', 'chevron-r'])) {
                $transform = 'rotate(90deg)';
            } 
            // handle unicons truck (points left, needs to point right)
            elseif ($pack === 'uni' && $name === 'truck') {
                $transform = 'scaleX(-1)';
            }

            if ($transform !== '') {
                $html = preg_replace(
                    '/(<svg[^>]*>)(.*?)(<\/svg>)/is',
                    '$1<g style="transform: ' . $transform . '; transform-origin: center;">$2</g>$3',
                    $html
                );
            }
        
            return $html;
        } catch (\Exception $e) {
            return $this->fallback($fullIconString, ['class' => $classes]);
        }
    }

    private function activePack(): string
    {
        try {
            return tenant()->resolvedTheme()->resolvedIconPack();
        } catch (\Throwable) {
            return Theme::defaultIconPack();
        }
    }

    private function fallback(string $name, array $attrs): string
    {
        $attrStr = collect($attrs)->map(fn($v, $k) => "{$k}=\"{$v}\"")->implode(' ');
        // red triangle
        return '<svg '. $attrStr .' xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#ff0000" aria-label="'. $name .'">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
        </svg>';
    }
}