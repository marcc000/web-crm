<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Category;
use App\Models\Customer;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\CustomerResource\Pages;

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
            Forms\Components\Select::make('price_list')
                ->options(Category::getPriceLists()->pluck('description'))
            ->selectablePlaceholder(false),
            Forms\Components\Select::make('product_category')
                ->options(Category::getProductCategories()->pluck('description'))
            ->selectablePlaceholder(false),
            Forms\Components\Select::make('sales_category')
                ->options(Category::getSalesCategories()->pluck('description'))
            ->selectablePlaceholder(false),
            Forms\Components\Select::make('channel')
                ->options(Category::getChannels()->pluck('description'))
            ->selectablePlaceholder(false),
            Forms\Components\Select::make('seasonality')
                ->options(Category::getSeasonalities()->pluck('description'))
            ->selectablePlaceholder(false),
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
