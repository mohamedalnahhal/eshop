<?php
namespace App\Filament\TenantAdmin\Pages;
use App\Models\Theme;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Collection;
use Filament\Notifications\Notification;

class ThemesPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-paint-brush';
    protected static ?string $navigationLabel = 'themes';
    protected static string|\UnitEnum|null $navigationGroup = 'setting sotre';
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

        // 1. تحديث الإعدادات في قاعدة البيانات
        tenant()->settings()->updateOrCreate(
            ['tenant_id' => tenant()->id],
            ['theme_id'  => $themeId]
        );

        // 2. تحديث كائن التينانت في الذاكرة لمسح البيانات القديمة
        // هذا السطر يضمن أن دالة resolvedTheme() ستجلب الثيم الجديد
        tenant()->refresh(); 

        // 3. إظهار إشعار النجاح
        Notification::make()
            ->title('theme activated: ' . $theme->name)
            ->success()
            ->send();

        // 4. إجبار واجهة Livewire على إعادة التحديث فورا لتنتقل علامة Active
        $this->dispatch('$refresh');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('createTheme')
                ->label('new theme')
                ->icon('heroicon-o-plus')
                ->color('primary')
                ->form([
                    TextInput::make('name')
                        ->label('theme name')
                        ->placeholder('example: eshop theme')
                        ->required()
                        ->maxLength(100),
                ])
                ->action(function (array $data) {
                    $theme = Theme::create([
                        'tenant_id'  => tenant()->id,
                        'name'       => $data['name'],
                        'is_default' => false,
                        'icon_pack'  => Theme::defaultIconPack(),
                        'currency'   => Theme::defaultCurrency(),
                        'palette'    => Theme::defaultPalette(),
                        'font'       => Theme::defaultFont(),
                        'buttons'    => Theme::defaultButtons(),
                        'inputs'     => Theme::defaultInputs(),
                        'header'     => Theme::defaultHeader(),
                        'm_header'   => Theme::defaultMobileHeader(),
                        'glows'      => Theme::defaultGlows(),
                        'corners'    => Theme::defaultCorners(),
                        'footer'     => Theme::defaultFooter(),
                        'homepage'   => Theme::defaultHomepage(),
                    ]);

                    Notification::make()
                        ->title('theme created: ' . $theme->name)
                        ->success()
                        ->send();

                    return redirect()->to(
                        ThemeEditorPage::getUrl() . '?themeId=' . $theme->id
                    );
                }),
        ];
    }
}