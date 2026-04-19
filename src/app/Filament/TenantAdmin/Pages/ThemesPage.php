<?php

namespace App\Filament\TenantAdmin\Pages;

use App\Models\Theme;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Illuminate\Support\Collection;
use Filament\Notifications\Notification;

class ThemesPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-paint-brush';
    protected static ?string $navigationLabel = 'المظهر';
    protected static string|\UnitEnum|null $navigationGroup = 'إعدادات المتجر';
    // protected static ?string $navigationGroup = 'إعدادات المتجر';
    protected static ?int $navigationSort = 10;
    protected string $view = 'filament.tenant-admin.pages.themes-page';
    
    public function getThemes(): Collection
    {
        return Theme::where(function ($q) {
            $q->where('tenant_id', tenant()->id)
              ->orWhereNull('tenant_id');
        })->orderBy('is_default', 'desc')->orderBy('name')->get();
    }

    public function getActiveThemeId(): ?string
    {
        return tenant()->resolvedTheme()?->id;
    }

    public function setDefault(string $themeId): void
    {
        $theme = Theme::findOrFail($themeId);
        
        // Mark as default for this tenant
        Theme::where('tenant_id', tenant()->id)->update(['is_default' => false]);
        
        // If global theme, create a tenant copy or just set a tenant setting
        tenant()->update(['theme_id' => $themeId]);

        $this->dispatch('theme-changed');
        
        \Filament\Notifications\Notification::make()
            ->title('تم تفعيل الثيم')
            ->success()
            ->send();
    }
}