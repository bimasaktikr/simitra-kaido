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
        return $user && ($user->hasRole('super_admin') || $user->hasRole('Pegawai'));


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
                Forms\Components\TextInput::make('rerata')
                    ->numeric()
                    ->label('rerata')
                    ->disabled()
                    ->reactive()
                    ->afterStateHydrated(function ($component, $state, $record, $get) {
                        // On edit, set rerata from record if available, otherwise calculate
                        if ($record) {
                            $component->state($record->rerata);
                        } else {
                            $total = 0;
                            $count = 0;
                            for ($i = 1; $i <= 10; $i++) {
                                $value = (int) $get('aspek' . $i);
                                if ($value) {
                                    $total += $value;
                                    $count++;
                                }
                            }
                            $component->state($count > 0 ? round($total / $count, 2) : 0);
                        }
                    })
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $total = 0;
                        $count = 0;
                        for ($i = 1; $i <= 10; $i++) {
                            $value = (int) $get('aspek' . $i);
                            if ($value) {
                                $total += $value;
                                $count++;
                            }
                        }
                        $set('rerata', $count > 0 ? round($total / $count, 2) : 0);
                    }),
                Forms\Components\Toggle::make('is_final')->label('isFinal'),
                // Update aspek fields to recalculate rerata
                Forms\Components\TextInput::make('aspek1')->integer()->required()->reactive()->live()->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $total = 0;
                    $count = 0;
                    for ($i = 1; $i <= 10; $i++) {
                        $value = (int) $get('aspek' . $i);
                        if ($value) {
                            $total += $value;
                            $count++;
                        }
                    }
                    $set('rerata', $count > 0 ? round($total / $count, 2) : 0);
                }),
                Forms\Components\TextInput::make('aspek2')->integer()->required()->reactive()->live()->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $total = 0;
                    $count = 0;
                    for ($i = 1; $i <= 10; $i++) {
                        $value = (int) $get('aspek' . $i);
                        if ($value) {
                            $total += $value;
                            $count++;
                        }
                    }
                    $set('rerata', $count > 0 ? round($total / $count, 2) : 0);
                }),
                Forms\Components\TextInput::make('aspek3')->integer()->required()->reactive()->live()->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $total = 0;
                    $count = 0;
                    for ($i = 1; $i <= 10; $i++) {
                        $value = (int) $get('aspek' . $i);
                        if ($value) {
                            $total += $value;
                            $count++;
                        }
                    }
                    $set('rerata', $count > 0 ? round($total / $count, 2) : 0);
                }),
                Forms\Components\TextInput::make('aspek4')->integer()->required()->reactive()->live()->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $total = 0;
                    $count = 0;
                    for ($i = 1; $i <= 10; $i++) {
                        $value = (int) $get('aspek' . $i);
                        if ($value) {
                            $total += $value;
                            $count++;
                        }
                    }
                    $set('rerata', $count > 0 ? round($total / $count, 2) : 0);
                }),
                Forms\Components\TextInput::make('aspek5')->integer()->required()->reactive()->live()->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $total = 0;
                    $count = 0;
                    for ($i = 1; $i <= 10; $i++) {
                        $value = (int) $get('aspek' . $i);
                        if ($value) {
                            $total += $value;
                            $count++;
                        }
                    }
                    $set('rerata', $count > 0 ? round($total / $count, 2) : 0);
                }),
                Forms\Components\TextInput::make('aspek6')->integer()->required()->reactive()->live()->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $total = 0;
                    $count = 0;
                    for ($i = 1; $i <= 10; $i++) {
                        $value = (int) $get('aspek' . $i);
                        if ($value) {
                            $total += $value;
                            $count++;
                        }
                    }
                    $set('rerata', $count > 0 ? round($total / $count, 2) : 0);
                }),
                Forms\Components\TextInput::make('aspek7')->integer()->required()->reactive()->live()->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $total = 0;
                    $count = 0;
                    for ($i = 1; $i <= 10; $i++) {
                        $value = (int) $get('aspek' . $i);
                        if ($value) {
                            $total += $value;
                            $count++;
                        }
                    }
                    $set('rerata', $count > 0 ? round($total / $count, 2) : 0);
                }),
                Forms\Components\TextInput::make('aspek8')->integer()->required()->reactive()->live()->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $total = 0;
                    $count = 0;
                    for ($i = 1; $i <= 10; $i++) {
                        $value = (int) $get('aspek' . $i);
                        if ($value) {
                            $total += $value;
                            $count++;
                        }
                    }
                    $set('rerata', $count > 0 ? round($total / $count, 2) : 0);
                }),
                Forms\Components\TextInput::make('aspek9')->integer()->required()->reactive()->live()->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $total = 0;
                    $count = 0;
                    for ($i = 1; $i <= 10; $i++) {
                        $value = (int) $get('aspek' . $i);
                        if ($value) {
                            $total += $value;
                            $count++;
                        }
                    }
                    $set('rerata', $count > 0 ? round($total / $count, 2) : 0);
                }),
                Forms\Components\TextInput::make('aspek10')->integer()->required()->reactive()->live()->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $total = 0;
                    $count = 0;
                    for ($i = 1; $i <= 10; $i++) {
                        $value = (int) $get('aspek' . $i);
                        if ($value) {
                            $total += $value;
                            $count++;
                        }
                    }
                    $set('rerata', $count > 0 ? round($total / $count, 2) : 0);
                }),
            ]);
    }

    public static function table(Table $table): Table
    {
        /** @var \App\Models\User|null $user */
        $user = \Filament\Facades\Filament::auth()?->user();
        $isSuperAdmin = $user && $user->hasRole('super_admin');

        $filters = [
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
        ];

        if ($isSuperAdmin) {
            $filters[] = Tables\Filters\SelectFilter::make('user_id')
                ->label('user')
                ->options(function () {
                    return \App\Models\User::whereHas('employee')->pluck('name', 'id')->toArray();
                });
            $filters[] = Tables\Filters\SelectFilter::make('mitra_teladan_id')
                ->label('mitraTeladan')
                ->options(fn () => \App\Models\MitraTeladan::with('mitra')->get()->pluck('mitra.name', 'id')->toArray());
        }

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
            ->filters($filters)
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

    /**
     * Restrict data to current user if not super_admin
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        /** @var \App\Models\User|null $user */
        $user = \Filament\Facades\Filament::auth()?->user();
        if ($user && !$user->hasRole('super_admin')) {
            $query->where('user_id', $user->id);
        }
        return $query;
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

    private static function updateRerata(callable $set, callable $get)
    {
        $total = 0;
        $count = 0;
        for ($i = 1; $i <= 10; $i++) {
            $value = (int) $get('aspek' . $i);
            if ($value) {
                $total += $value;
                $count++;
            }
        }
        $rerata = $count > 0 ? round($total / $count, 2) : 0;
        $set('rerata', $rerata);
    }
}
