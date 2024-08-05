<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Address;
use App\Models\AgentZone;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('erp_id')
                            ->label('Codice')
                            ->readOnly(),
                        Forms\Components\TextInput::make('business_name')
                            ->label('Ragione Sociale'),
                        Forms\Components\TextInput::make('vat_number')
                            ->label('P.IVA'),
                        Forms\Components\Select::make('zone')
                            ->label('Agente')
                            ->relationship('zone')
                            ->searchable()
                            ->preload()
                            ->getOptionLabelFromRecordUsing(fn (AgentZone $record) => "{$record->zone_id} - {$record->agent->name}"),
                        Forms\Components\Checkbox::make('exported')
                            ->label('Exp su SFA'),
                    ]),
                Forms\Components\Section::make()
                    ->columns(3)
                    ->schema([
                        Forms\Components\Select::make('price_list')
                            ->label('Listino')
                            ->relationship(
                                'priceList',
                                'description',
                                fn (Builder $query) => $query->where('category_scope', '30')->whereIn('key', ['1', '2', '10']),
                            )
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('product_category')
                            ->label('Categoria Merceologica')
                            ->relationship(
                                'productCategory',
                                'description',
                                fn (Builder $query) => $query->where('category_scope', '31'),
                            )
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('sales_category')
                            ->label('Categoria di vendita')
                            ->relationship(
                                'salesCategory',
                                'description',
                                fn (Builder $query) => $query->where('category_scope', '32'),
                            )
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('channel')
                            ->label('Canale')
                            ->relationship(
                                'channel',
                                'description',
                                fn (Builder $query) => $query->where('category_scope', '33'),
                            )
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('seasonality')
                            ->label('Stagionalità')
                            ->relationship(
                                'seasonality',
                                'description',
                                fn (Builder $query) => $query->where('category_scope', '34'),
                            )
                            ->searchable()
                            ->preload(),
                    ]),
                Forms\Components\Section::make()
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('default_address')
                            ->label('Indirizzo di fatturazione')
                            ->relationship(
                                'addresses',
                                'description',
                                fn (Builder $query, ?Customer $record) => $query->where('customer_id', $record->erp_id ?? ''), )
                            ->getOptionLabelFromRecordUsing(fn (Address $record) => "{$record->erp_id} - {$record->description} : [{$record->signboard}] {$record->address} {$record->city}")
                            ->createOptionForm([
                                Forms\Components\Section::make()
                                    ->columns(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('address')
                                            ->label('Indirizzo')
                                            ->required(),
                                        Forms\Components\TextInput::make('description')
                                            ->label('Descrizione')
                                            ->required(),
                                        Forms\Components\TextInput::make('signboard')
                                            ->label('Insegna locale')
                                            ->required(),
                                        Forms\Components\Select::make('country')
                                            ->label('Paese')
                                            ->relationship(
                                                'country',
                                                'name',
                                            )
                                            ->searchable()
                                            ->preload(),
                                        Forms\Components\Select::make('province')
                                            ->label('Provincia')
                                            ->relationship(
                                                'province',
                                                'name',
                                            )
                                            ->searchable()
                                            ->preload(),
                                        Forms\Components\Select::make('cap')
                                            ->label('CAP')
                                            ->relationship(
                                                'cap',
                                                'code',
                                            )
                                            ->searchable()
                                            ->preload(),
                                    ]),
                            ]),
                        Forms\Components\Select::make('default_delivery_address')
                            ->label('Destinazione merce principale')
                            ->relationship(
                                'addresses',
                                'description',
                                fn (Builder $query, ?Customer $record) => $query->where('customer_id', $record->erp_id ?? ''), )
                            ->getOptionLabelFromRecordUsing(fn (Address $record) => "{$record->erp_id} - {$record->description} : {$record->address} {$record->city}")
                            ->createOptionForm([
                                Forms\Components\TextInput::make('id')
                                    ->required(),
                                Forms\Components\TextInput::make('description')
                                    ->required(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('erp_id')->searchable(),
                Tables\Columns\TextColumn::make('business_name')->searchable(),
                Tables\Columns\TextColumn::make('vat_number')->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\AddressesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
