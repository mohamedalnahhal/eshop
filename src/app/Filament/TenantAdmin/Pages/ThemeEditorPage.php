<?php

namespace App\Filament\TenantAdmin\Pages;

use App\Enums\TenantPermission;
use App\Models\Theme;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

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

    public static function canAccess(): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user->tenantUserFor(tenant('id'))
            ?->can(TenantPermission::MANAGE_SETTINGS) ?? false;
    }
}