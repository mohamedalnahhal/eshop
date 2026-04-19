<?php

namespace App\Livewire;

use App\Models\Supplier;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class SupplierTable extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(Supplier::query()->latest())
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name'),
                Tables\Columns\TextColumn::make('info')
                    ->label('Info')
                    ->limit(50)
                    ->default('-'),
            ])
            ->headerActions([
                Action::make('create_supplier')
                    ->label('New Supplier')
                    ->color('primary')
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required(),
                        Textarea::make('info')
                            ->label(__('Info'))
                            ->nullable(),
                    ])
                    ->action(function (array $data) {
                        Supplier::create([
                            'name'      => $data['name'],
                            'info'      => $data['info'] ?? null,
                        ]);

                        Notification::make()
                            ->title('Supplier Created Successfully')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public function render()
    {
        return view('livewire.supplier-table');
    }
}
