<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MitraTeladanResource\Pages;
use App\Filament\Resources\MitraTeladanResource\RelationManagers;
use App\Models\MitraTeladan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MitraTeladanResource extends Resource
{
    protected static ?string $model = MitraTeladan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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
   Tables\Actions\DeleteAction::make(),

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
            // 'index' => Pages\ListMitraTeladans::route('/'),
            'index' => Pages\SelectMitraTeladan::route('/'),
            'create' => Pages\CreateMitraTeladan::route('/create'),
            'edit' => Pages\EditMitraTeladan::route('/{record}/edit'),

        ];
    }
}
