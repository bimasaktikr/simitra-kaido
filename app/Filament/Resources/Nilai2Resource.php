<?php

namespace App\Filament\Resources;

use App\Filament\Resources\Nilai2Resource\Pages;
use App\Filament\Resources\Nilai2Resource\RelationManagers;
use App\Models\Nilai2;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Facades\Filament;

class Nilai2Resource extends Resource
{
    protected static ?string $model = Nilai2::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Penilaian';

    public static ?string $title = 'Nilai Tahap 2';

    protected static ?string $navigationLabel = 'Nilai Tahap 2';

    public static function shouldRegisterNavigation(): bool
    {
        /** @var \App\Models\User|null $user */
        $user = \Filament\Facades\Filament::auth()?->user();
        return $user && $user->hasRole('super_admin');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('mitra_teladan_id')
                    ->relationship('mitraTeladan', 'id')
                    ->label('mitraTeladan')
                    ->getOptionLabelFromRecordUsing(fn($record) => $record->mitra?->name)
                    ->disabled(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->label('user')
                    ->required(),
                Forms\Components\TextInput::make('aspek1')->integer()->required(),
                Forms\Components\TextInput::make('aspek2')->integer()->required(),
                Forms\Components\TextInput::make('aspek3')->integer()->required(),
                Forms\Components\TextInput::make('aspek4')->integer()->required(),
                Forms\Components\TextInput::make('aspek5')->integer()->required(),
                Forms\Components\TextInput::make('aspek6')->integer()->required(),
                Forms\Components\TextInput::make('aspek7')->integer()->required(),
                Forms\Components\TextInput::make('aspek8')->integer()->required(),
                Forms\Components\TextInput::make('aspek9')->integer()->required(),
                Forms\Components\TextInput::make('aspek10')->integer()->required(),
                Forms\Components\TextInput::make('rerata')->numeric()->label('rerata')->disabled(),
                Forms\Components\Toggle::make('is_final')->label('isFinal'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('mitraTeladan.mitra.name')->label('mitraTeladan'),
                Tables\Columns\TextColumn::make('user.name')->label('user'),
                Tables\Columns\TextColumn::make('aspek1'),
                Tables\Columns\TextColumn::make('aspek2'),
                Tables\Columns\TextColumn::make('aspek3'),
                Tables\Columns\TextColumn::make('aspek4'),
                Tables\Columns\TextColumn::make('aspek5'),
                Tables\Columns\TextColumn::make('aspek6'),
                Tables\Columns\TextColumn::make('aspek7'),
                Tables\Columns\TextColumn::make('aspek8'),
                Tables\Columns\TextColumn::make('aspek9'),
                Tables\Columns\TextColumn::make('aspek10'),
                Tables\Columns\TextColumn::make('rerata'),
                Tables\Columns\IconColumn::make('is_final')->boolean()->label('isFinal'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('year')
                    ->label('year')
                    ->options(fn () => \App\Models\MitraTeladan::query()->distinct()->pluck('year', 'year')->toArray())
                    ->query(function ($query, $state) {
                        if ($state) {
                            $query->whereHas('mitraTeladan', fn($q) => $q->where('year', $state));
                        }
                    }),
                Tables\Filters\SelectFilter::make('quarter')
                    ->label('quarter')
                    ->options(fn () => \App\Models\MitraTeladan::query()->distinct()->pluck('quarter', 'quarter')->toArray())
                    ->query(function ($query, $state) {
                        if ($state) {
                            $query->whereHas('mitraTeladan', fn($q) => $q->where('quarter', $state));
                        }
                    }),
                Tables\Filters\SelectFilter::make('user_id')
                    ->label('user')
                    ->options(function () {
                        /** @var \App\Models\User|null $user */
                        $user = \Filament\Facades\Filament::auth()?->user();
                        $employeeUsers = \App\Models\User::whereHas('employee')->pluck('name', 'id')->toArray();
                        if ($user && $user->hasRole('super_admin')) {
                            return $employeeUsers;
                        }
                        return ($user && isset($employeeUsers[$user->id])) ? [$user->id => $user->name] : [];
                    }),
                Tables\Filters\SelectFilter::make('mitra_teladan_id')
                    ->label('mitraTeladan')
                    ->options(fn () => \App\Models\MitraTeladan::with('mitra')->get()->pluck('mitra.name', 'id')->toArray()),
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
            'index' => Pages\ListNilai2s::route('/'),
            'create' => Pages\CreateNilai2::route('/create'),
            'edit' => Pages\EditNilai2::route('/{record}/edit'),
        ];
    }
}
