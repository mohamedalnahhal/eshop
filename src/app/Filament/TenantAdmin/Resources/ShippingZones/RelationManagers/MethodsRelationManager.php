<?php

namespace App\Filament\TenantAdmin\Resources\ShippingZones\RelationManagers;

use App\Filament\TenantAdmin\Resources\ShippingZones\Schemas\MethodsForm;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Actions;
use Filament\Tables;

class MethodsRelationManager extends RelationManager
{
    protected static string $relationship = 'methods';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $title = 'Shipping Methods';

    public function form(Schema $schema): Schema
    {
        return MethodsForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Method Name')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('estimated_delivery')
                    ->label('Estimated Delivery'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
            ])
            ->headerActions([
                Actions\CreateAction::make()->slideOver(),
            ])
            ->recordActions([
                Actions\EditAction::make()->slideOver(),
                Actions\DeleteAction::make(),
            ])
            ->groupedBulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}