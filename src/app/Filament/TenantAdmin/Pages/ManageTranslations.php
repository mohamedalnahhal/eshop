<?php

namespace App\Filament\TenantAdmin\Pages;

use App\Enums\TenantPermission;
use App\Models\LanguageLine;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class ManageTranslations extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-language';
    protected static ?string $navigationLabel = 'Translations';
    protected static ?string $title = 'Manage Translations';
    protected static ?int $navigationSort = 3;

    protected string $view = 'filament.tenant-admin.pages.manage-translations';

    public function table(Table $table): Table
    {
        return $table
            ->query(LanguageLine::query())
            ->columns([
                Tables\Columns\TextColumn::make('group')
                    ->label('Group')
                    ->searchable(),
                Tables\Columns\TextColumn::make('key')
                    ->label('Key')
                    ->searchable(),
                Tables\Columns\TextColumn::make('text')
                    ->label('Translations')
                    ->formatStateUsing(function ($record) {
                        return collect($record->text)
                            ->map(fn($v, $k) => strtoupper($k) . ': ' . $v)
                            ->implode(' | ');
                    }),
            ])
            ->headerActions([
                Action::make('add_translation')
                    ->label('Add Translation')
                    ->color('primary')
                    ->schema([
                        Select::make('locale')
                            ->label('Language')
                            ->options(function () {
                                $supported = tenant()->settings?->supported_languages ?? [];

                                return collect($supported)
                                    ->mapWithKeys(fn($locale) => [
                                        $locale => \Locale::getDisplayName($locale, 'en') . ' (' . $locale . ')',
                                    ])
                                    ->sort()
                                    ->toArray();
                            })
                            ->required(),
                        TextInput::make('key')
                            ->label('Key (e.g. Add to cart)')
                            ->required(),
                        TextInput::make('value')
                            ->label('Translation')
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $line = LanguageLine::firstOrNew([
                            'group' => '*',
                            'key'   => $data['key'],
                        ]);

                        $text = $line->text ?? [];
                        $text[$data['locale']] = $data['value'];
                        $line->text = $text;
                        $line->save();

                        Notification::make()
                            ->title('Translation saved!')
                            ->success()
                            ->send();
                    }),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label('Edit')
                    ->schema(function ($record) {
                        $locales = array_keys($record->text ?? []);

                        $keyField = TextInput::make('key')
                            ->label('Key')
                            ->default($record->key)
                            ->required();

                        $translationFields = collect($locales)->map(fn($locale) =>
                            TextInput::make("text.{$locale}")
                                ->label(\Locale::getDisplayName($locale, 'en') . ' (' . strtoupper($locale) . ')')
                                ->default($record->text[$locale] ?? '')
                        )->toArray();

                        return [$keyField, ...$translationFields];
                    })
                    ->action(function ($record, array $data) {
                        $record->key  = $data['key'];
                        $record->text = array_filter($data['text'], fn($v) => $v !== null && $v !== '');
                        $record->save();

                        Notification::make()
                            ->title('Translation updated!')
                            ->success()
                            ->send();
                    }),
                DeleteAction::make(),
            ]);
    }

    public static function canAccess(): bool
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return $user->tenantUserFor(tenant('id'))
            ?->can(TenantPermission::MANAGE_SETTINGS) ?? false;
    }
}