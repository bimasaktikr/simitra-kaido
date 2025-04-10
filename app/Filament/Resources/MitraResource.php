<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MitraResource\Pages;
use App\Filament\Resources\MitraResource\RelationManagers;
use App\Models\Mitra;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MitraResource extends Resource
{
    protected static ?string $model = Mitra::class;

    protected static ?string $title = 'Mitra';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('sobat_id')
                ->label('Sobat ID')
                // ->numeric()
                ->required()
                ->unique(ignoreRecord: true),

            TextInput::make('name')
                ->label('Name')
                ->required()
                ->maxLength(200),

            TextInput::make('email')
                ->label('Email')
                ->email()
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(200),

            TextInput::make('pendidikan')
                ->label('Education')
                ->required()
                ->maxLength(50),

            Select::make('jenis_kelamin')
                ->label('Gender')
                ->options([
                    'Laki-laki' => 'Laki-laki',
                    'Perempuan' => 'Perempuan',
                ])
                ->required(),

            DatePicker::make('tanggal_lahir')
                ->label('Date of Birth')
                ->required(),

            FileUpload::make('photo')
                ->label('Photo')
                ->image()
                ->directory('mitra-photos')
                ->imagePreviewHeight('150')
                ->preserveFilenames()
                ->columnSpanFull(),

            // Related user_id (optional to display as dropdown)
            // Select::make('user_id')
            //     ->relationship('user', 'name')
            //     ->searchable()
            //     ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                ->label('Photo')
                ->extraImgAttributes(['class' => 'rounded-md'])
                ->size(50),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('sobat_id')
                    ->label('Sobat ID')
                    ->sortable(),

                TextColumn::make('jenis_kelamin')
                    ->label('Gender')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Laki-laki' => 'primary',
                        'Perempuan' => 'pink',
                        default => 'gray',
                    }),

                TextColumn::make('pendidikan')
                    ->label('Education')
                    ->searchable(),

                TextColumn::make('tanggal_lahir')
                    ->label('DOB')
                    ->date()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Registered')
                    ->since()
                    ->sortable(),
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
            'index' => Pages\ListMitras::route('/'),
            'create' => Pages\CreateMitra::route('/create'),
            'edit' => Pages\EditMitra::route('/{record}/edit'),
        ];
    }
}
