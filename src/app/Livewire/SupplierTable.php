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

    public bool $collapsed = false;

    public function makeFilamentTranslatableContentDriver(): ?\Filament\Support\Contracts\TranslatableContentDriver
    {
        return null;
    }

    public function toggleCollapse(): void
    {
        $this->collapsed = !$this->collapsed;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Supplier::query()->latest())
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('info')
                    ->label(__('Info'))
                    ->limit(50)
                    ->default('-'),
            ])
            ->headerActions([
                Action::make('create_supplier')
                    ->label(__('New Supplier'))
                    ->icon('heroicon-o-building-storefront')
                    ->color('primary')
                    ->form([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required(),
                        Textarea::make('info')
                            ->label(__('Info'))
                            ->nullable(),
                    ])
                    ->action(function (array $data) {
                        Supplier::create([
                            'tenant_id' => auth()->user()->tenants()->first()->id,
                            'name'      => $data['name'],
                            'info'      => $data['info'] ?? null,
                        ]);

                        Notification::make()
                            ->title(__('Supplier Created Successfully'))
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
