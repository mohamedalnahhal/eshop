<?php

namespace App\Filament\TenantAdmin\Pages;

use App\Models\Theme;
use Filament\Pages\Page;

class ThemeEditorPage extends Page
{
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
}

// this is comment for how Theme Editor works, not for the page itself, so it should be in the Livewire component, but I put it here to avoid confusion with the main code of the component