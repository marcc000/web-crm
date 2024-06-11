<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('partner.business_name')
                    ->required(),
                Forms\Components\TextInput::make('partner.vat_number'),
                Forms\Components\TextInput::make('partner.tax_id'),
                Forms\Components\TextInput::make('agent'),
                Forms\Components\TextInput::make('partner.default_address'),
                Forms\Components\TextInput::make('default_contact'),
                Forms\Components\Select::make('price_list'),
                Forms\Components\TextInput::make('product_category'),
                Forms\Components\TextInput::make('sales_category'),
                Forms\Components\TextInput::make('channel'),
                Forms\Components\TextInput::make('seasonality'),
                Forms\Components\TextInput::make('payment_method'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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
            //
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
