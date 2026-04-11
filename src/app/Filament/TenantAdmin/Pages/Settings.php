<?php

namespace App\Filament\TenantAdmin\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use App\Models\TenantSetting;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'General';
    protected static ?int $navigationSort = 99;
    protected static ?string $title = 'General Shop Settings';
    protected static string|\UnitEnum|null $navigationGroup = 'Shop Settings';
    
    protected string $view = 'filament.tenant-admin.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = TenantSetting::firstOrCreate(
            ['tenant_id' => tenant('id')]
        );

        $this->form->fill($settings->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Shop Identity')
                    ->icon('heroicon-o-building-storefront')
                    ->columns(2)
                    ->schema([
                        TextInput::make('shop_name')
                            ->label('Shop Name')
                            ->required()
                            ->maxLength(100)
                            ->columnSpanFull(),

                        TextInput::make('contact_email')
                            ->label('Contact Email')
                            ->email()
                            ->required()
                            ->maxLength(255),

                        TextInput::make('contact_phone')
                            ->label('Contact Phone')
                            ->tel()
                            ->maxLength(30),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        TenantSetting::updateOrCreate(
            ['tenant_id' => tenant('id')],
            $data,
        );

        Notification::make()
            ->title('Settings saved')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->action('save'),
        ];
    }

    public function saveAction(): Action
    {
        return Action::make('save')
            ->label('Save Settings')
            ->action('save')
            ->color('primary');
    }
}
