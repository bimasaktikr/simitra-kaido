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

    protected static ?string $navigationGroup = 'Mitra';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('mitra_id')
                    ->label('Mitra')
                    ->relationship('mitra', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('team_id')
                    ->label('Team')
                    ->relationship('team', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('year')
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('quarter')
                    ->options([
                        1 => 'Q1',
                        2 => 'Q2',
                        3 => 'Q3',
                        4 => 'Q4',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('avg_rating_1')
                    ->label('Rerata Rating 1')
                    ->numeric(),
                Forms\Components\TextInput::make('avg_rating_2')
                    ->label('Rerata Rating 2')
                    ->numeric(),
                Forms\Components\Toggle::make('status_phase_2')
                    ->label('Status Phase 2'),
                Forms\Components\TextInput::make('surveys_count')
                    ->label('Jumlah Survey')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('mitra.name')->label('Mitra')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('team.name')->label('Team')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('year')->sortable(),
                Tables\Columns\TextColumn::make('quarter')->sortable(),
                Tables\Columns\TextColumn::make('avg_rating_1')->label('Rerata Rating 1')->sortable(),
                Tables\Columns\TextColumn::make('avg_rating_2')->label('Rerata Rating 2')->sortable(),
                Tables\Columns\IconColumn::make('status_phase_2')->boolean()->label('Status Phase 2'),
                Tables\Columns\TextColumn::make('surveys_count')->label('Jumlah Survey')->sortable(),
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
            'index' => Pages\ListMitraTeladans::route('/'),
            // 'index' => Pages\IndexMitraTeladan::route('/'),
            'create' => Pages\CreateMitraTeladan::route('/create'),
            'edit' => Pages\EditMitraTeladan::route('/{record}/edit'),

        ];
    }
}
