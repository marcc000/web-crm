<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
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

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Fieldset::make('Identity')
                    ->schema([
                        Forms\Components\TextInput::make('erp_id')
                            ->disabled(),
                        Forms\Components\TextInput::make('business_name'),
                        Forms\Components\TextInput::make('vat_number'),
                        Forms\Components\TextInput::make('agent'),
                    ]),
                Forms\Components\Fieldset::make('Categories')
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
                Forms\Components\Select::make('default_address')
                    ->relationship(name: 'addresses', titleAttribute: 'description')
                    ->createOptionForm([
                        Forms\Components\TextInput::make('id')
                            ->required(),
                        Forms\Components\TextInput::make('description')
                            ->required(),
                    ]),
                Forms\Components\TextInput::make('default_contact'),

                Forms\Components\Select::make('payment_method'),
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
