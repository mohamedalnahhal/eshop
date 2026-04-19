<?php

namespace App\Filament\TenantAdmin\Pages;

use App\Models\Theme;
use Filament\Pages\Page;

class ThemeEditorPage extends Page
{
    // protected static ?string $navigationIcon = null;

    protected static string|\BackedEnum|null $navigationIcon = null;

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $slug = 'theme-editor';

    protected string $view = 'filament.tenant-admin.pages.theme-editor-page';

    public string $themeId = '';

    public ?Theme $theme = null;
    
    public function mount(): void
    {
        $this->themeId = request()->query('themeId', '');
        abort_if(empty($this->themeId), 404);
        
        Theme::where('id', $this->themeId)
            ->where(fn($q) => $q->where('tenant_id', tenant()->id)->orWhereNull('tenant_id'))
            ->firstOrFail();
    }
    // public function mount(string $themeId): void
    // {
    //     $this->themeId = $theme;
    //     // verify theme belongs to tenant or is global
    //     $this->theme = Theme::where('id', $themeId)
    //         ->where(function ($q) {
    //             $q->where('tenant_id', tenant()->id)
    //               ->orWhereNull('tenant_id');
    //         })->firstOrFail();
    // }

    // public static function getUrl(array $parameters = [], bool $isAbsolute = true, ?string $panel = null, ?\Illuminate\Database\Eloquent\Model $tenant = null): string
    // {
    //     return route('filament.tenant.pages.theme-editor', $parameters);
    // }
}