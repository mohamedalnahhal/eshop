<?php

namespace App\Filament\TenantAdmin\Resources\TenantUsers;

use App\Enums\TenantUserRole;
use App\Filament\TenantAdmin\Resources\TenantUsers\Pages\CreateTenantUser;
use App\Filament\TenantAdmin\Resources\TenantUsers\Pages\EditTenantUser;
use App\Filament\TenantAdmin\Resources\TenantUsers\Pages\ListTenantUsers;
use App\Filament\TenantAdmin\Resources\TenantUsers\Schemas\TenantUserForm;
use App\Filament\TenantAdmin\Resources\TenantUsers\Tables\TenantUsersTable;
use App\Models\TenantUser;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TenantUserResource extends Resource
{
    protected static ?string $model = TenantUser::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 1;
    protected static string|\UnitEnum|null $navigationGroup = 'Staff';
    protected static ?string $navigationLabel = 'Dashboard Users';
    protected static ?string $modelLabel = 'Dashboard User';
    protected static ?string $pluralModelLabel = 'Dashboard Users';
    protected static ?string $recordTitleAttribute = 'user.name';

    public static function form(Schema $schema): Schema
    {
        return TenantUserForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TenantUsersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTenantUsers::route('/'),
            'create' => CreateTenantUser::route('/create'),
            'edit' => EditTenantUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        // eager load user and permissions
        return parent::getEloquentQuery()->with(['user', 'permissions']);
    }
}