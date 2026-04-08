<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\StockAdjustment;
use App\Models\Supplier;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class StockAdjustmentTable extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function makeFilamentTranslatableContentDriver(): ?\Filament\Support\Contracts\TranslatableContentDriver
    {
        return null;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(StockAdjustment::query()->latest())
            ->columns([
                Tables\Columns\TextColumn::make('product.name')
                    ->label(__('Product')),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('Type'))
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'purchase'   => 'success',
                        'production' => 'info',
                        'damaged'    => 'danger',
                    }),
                Tables\Columns\TextColumn::make('updated_value')
                    ->label(__('Qty'))
                    ->badge(),
                Tables\Columns\TextColumn::make('supplier.name')
                    ->label(__('Supplier'))
                    ->default('-'),
                Tables\Columns\SelectColumn::make('status')
                    ->label(__('Status'))
                    ->options([
                        'issued'  => '📋 Issued',
                        'waiting' => '⏳ Waiting',
                        'done'    => '✅ Done',
                    ])
                    ->afterStateUpdated(function ($record, $state) {
                        if ($state === 'done') {
                            $record->product->increment('stock', $record->updated_value);
                            Notification::make()
                                ->title(__('Stock Updated'))
                                ->success()
                                ->send();
                        }
                    }),
            ])
            ->headerActions([
                Action::make('create_adjustment')
                    ->label(__('New Request'))
                    ->form([
                        Select::make('product_id')
                            ->label(__('Product'))
                            ->options(Product::pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        Select::make('type')
                            ->label(__('Type'))
                            ->options([
                                'purchase'   => '🛒 Purchase',
                                'production' => '🏭 Production',
                                'damaged'    => '💔 Damaged',
                            ])
                            ->required()
                            ->live(),
                        Select::make('supplier_id')
                            ->label(__('Supplier'))
                            ->options(Supplier::pluck('name', 'id'))
                            ->searchable()
                            ->nullable()
                            ->visible(fn ($get) => $get('type') === 'purchase'),
                        TextInput::make('updated_value')
                            ->label(__('Quantity'))
                            ->numeric()
                            ->required()
                            ->minValue(1),
                        Select::make('status')
                            ->label(__('Status'))
                            ->options([
                                'issued'  => '📋 Issued',
                                'waiting' => '⏳ Waiting',
                                'done'    => '✅ Done',
                            ])
                            ->default('issued')
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        StockAdjustment::create([
                            'tenant_id'     => auth()->user()->tenant_id,
                            'product_id'    => $data['product_id'],
                            'type'          => $data['type'],
                            'supplier_id'   => $data['supplier_id'] ?? null,
                            'updated_value' => $data['updated_value'],
                            'status'        => $data['status'],
                        ]);

                        Notification::make()
                            ->title(__('Request Created Successfully'))
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public function render()
    {
        return view('livewire.stock-adjustment-table');
    }
}