<?php

namespace App\Filament\TenantAdmin\Resources\Customers;

use App\Filament\TenantAdmin\Resources\Customers\Pages\ManageCustomers;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select; 
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Enums\UserRole;

class CustomerResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

   public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('tenants', function ($query) {
                $query->where('tenant_users.tenant_id', tenant('id'))
                      ->where('tenant_users.role', UserRole:: CUSTOMER); 
            });
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(100), 

                TextInput::make('username')
                    ->required()
                    ->maxLength(50)
                    ->unique(ignoreRecord: true), 

                TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true), 

                TextInput::make('phone')
                    ->tel() 
                    ->maxLength(32),

                Select::make('gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                    ])
                    ->required(),

                TextInput::make('password')
                    ->password() 
                    ->revealable() 
                    ->required(fn (string $operation): bool => $operation === 'create') 
                    ->dehydrated(fn (?string $state) => filled($state)), 
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('username'),
                TextEntry::make('email'),
                TextEntry::make('phone'),
                TextEntry::make('gender')->badge(), 
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                
                TextColumn::make('username')
                    ->searchable(),

                TextColumn::make('email')
                    ->searchable(),

                TextColumn::make('phone')
                    ->searchable(),
                
                TextColumn::make('gender')
                    ->badge() 
                    ->colors([
                        'primary' => 'male',
                        'danger' => 'female',
                    ]),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCustomers::route('/'),
        ];
    }
}