<?php

namespace App\Filament\SuperAdmin\Resources\Tenants\Tables;

use App\Filament\SuperAdmin\Resources\Tenants\TenantResource;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\TrashedFilter;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tenant;

class TenantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('domain.domain')
                    ->label('Domain')
                    ->searchable()
                    ->getStateUsing(function ($record) {
                        return $record->domain->domain;
                    }),
                TextColumn::make('owner_email')
                    ->label('Owner Email')
                    ->getStateUsing(function ($record) {
                        return $record->owner->first()?->email;
                    }),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),  
            ])
            ->recordActions([
                Action::make('details')
                    ->label('details')
                    ->icon('heroicon-m-chart-bar')
                    ->color('success')
                    ->url(fn (Tenant $record): string => TenantResource::getUrl('details', [
                        'record' => $record->getKey(), 
                    ])),
                EditAction::make()
                ->mutateRecordDataUsing(function (Model $record, array $data): array {
                    $domain = $record->domain?->domain ?? '';
                    $centralDomain = '.' . config('tenancy.central_domains')[0];
                    
                    $data['subdomain'] = str_replace($centralDomain, '', $domain);
                    $data['owner_email'] = $record->owner->first()?->email;

                    return $data;
                })
                ->using(function (Model $record, array $data): Model {
                    return DB::transaction(function () use ($record, $data) {
                        $tenantData = collect($data)->except(['subdomain', 'owner_email'])->toArray();
                        $record->update($tenantData);
                    
                        $record->domain()->updateOrCreate(
                            ['tenant_id' => $record->id],
                            ['domain' => $data['subdomain'] . '.' . config('tenancy.central_domains')[0]]
                        );
                    
                        return $record;
                    });
                }),
                 ActionGroup::make([
                    DeleteAction::make(),
                    RestoreAction::make(),
                    ForceDeleteAction::make(),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ])
            ]);
    }
}
