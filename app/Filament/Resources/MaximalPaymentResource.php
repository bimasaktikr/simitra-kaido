<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaximalPaymentResource\Pages;
use App\Filament\Resources\MaximalPaymentResource\RelationManagers;
use App\Models\MaximalPayment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MaximalPaymentResource extends Resource
{
    protected static ?string $model = MaximalPayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Master Data';

    /** 🔒 Allow “Create” only when there is no row yet */
    public static function canCreate(): bool
    {
        return ! MaximalPayment::query()->exists();
    }

    /** 🔒 Don’t allow deleting the singleton row */
    public static function canDelete($record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('value')
                    ->label('Max per Month')
                    ->prefix('Rp')
                    ->numeric()
                    ->minValue(0)
                    ->required()
                    ->helperText('Nilai dalam Rupiah per bulan (tanpa titik pemisah).'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('value')
                    ->label('Value')
                    ->money('IDR', true) // pretty currency formatting
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated At')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

            ])
            ->bulkActions([
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
            'index' => Pages\ListMaximalPayments::route('/'),
            'create' => Pages\CreateMaximalPayment::route('/create'),
            'edit' => Pages\EditMaximalPayment::route('/{record}/edit'),
        ];
    }
}
