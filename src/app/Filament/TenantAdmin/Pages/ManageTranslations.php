<?php

namespace App\Filament\TenantAdmin\Pages;

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
use App\Models\LanguageLine;

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
                                return collect(\ResourceBundle::getLocales(''))
                                    ->mapWithKeys(fn($locale) => [
                                        $locale => \Locale::getDisplayName($locale, 'en')
                                    ])
                                    ->sort()
                                    ->toArray();
                            })
                            ->searchable()
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
                        return collect($locales)->map(fn($locale) =>
                            TextInput::make("text.{$locale}")
                                ->label(strtoupper($locale))
                                ->default($record->text[$locale] ?? '')
                        )->toArray();
                    })
                    ->action(function ($record, array $data) {
                        $record->text = $data['text'];
                        $record->save();

                        Notification::make()
                            ->title('Translation updated!')
                            ->success()
                            ->send();
                    }),
                DeleteAction::make(),
            ]);
    }
}