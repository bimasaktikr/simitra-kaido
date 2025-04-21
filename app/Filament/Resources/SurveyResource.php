<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyResource\Pages;
use App\Filament\Resources\SurveyResource\RelationManagers;
use App\Models\Survey;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Ketua Tim';


    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'unfinalize', // <-- Add this custom permission
        ];
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            TextInput::make('name')
                ->label('Survey Name')
                ->required()
                ->maxLength(50),

            TextInput::make('code')
                ->label('Survey Code')
                ->required()
                ->maxLength(50),

            Select::make('payment_id')
                ->label('Payment Type')
                ->relationship('payment', 'payment_type') // assuming relation exists
                // ->searchable()
                ->required(),

            Select::make('team_id')
                ->label('Team')
                ->relationship('team', 'name')
                // ->searchable()
                ->required(),

            DatePicker::make('start_date')
                ->label('Start Date')
                ->required(),

            DatePicker::make('end_date')
                ->label('End Date')
                ->required(),

            TextInput::make('rate')
                ->numeric()
                ->prefix('Rp.')
                ->label('Rate')
                ->required(),

            // FileUpload::make('file')
            //     ->label('Attachment')
            //     ->directory('survey-files')
            //     ->maxSize(2048)
            //     ->preserveFilenames()
            //     ->nullable(),

            Toggle::make('is_scored')
                ->label('Scored?')
                ->disabled(),

            Toggle::make('is_synced')
                ->label('Synced?')
                ->disabled(),

            Select::make('status')
                ->options([
                    'not started' => 'Not Started',
                    'in progress' => 'In Progress',
                    'done' => 'Done',
                ])
                ->label('Status')
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('code')->sortable()->searchable(),
                TextColumn::make('payment.name')->label('Payment Type')->sortable(),
                TextColumn::make('team.name')->label('Team')->sortable(),
                TextColumn::make('start_date')->date()->sortable(),
                TextColumn::make('end_date')->date()->sortable(),
                TextColumn::make('rate')->money('IDR', true)->sortable(),

                IconColumn::make('is_scored')
                    ->label('Scored')
                    ->boolean(),

                IconColumn::make('is_synced')
                    ->label('Synced')
                    ->boolean(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'not started' => 'gray',
                        'in progress' => 'warning',
                        'done' => 'success',
                        default => 'secondary',
                    })
                    // ->icon(fn (string $state): ?string => match ($state) {
                    //     'done' => 'check-circle',
                    //     'in progress' => 'clock',
                    //     'not started' => 'circle',
                    //     default => null,
                    // })
                    ->sortable(),
            ])
            ->defaultSort(function (Builder $query): Builder {
                return $query
                    ->orderBy('is_scored')
                    ->orderBy('end_date', 'desc');
            })
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('view')
                ->label('Detail')
                ->icon('heroicon-o-eye')
                ->url(fn ($record) => SurveyResource::getUrl('view-survey-detail', ['record' => $record])),

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
            'index' => Pages\ListSurveys::route('/'),
            'create' => Pages\CreateSurvey::route('/create'),
            'edit' => Pages\EditSurvey::route('/{record}/edit'),
            'view-survey-detail' => Pages\ViewSurveyDetail::route('/{record}/view-survey-detail'), // ✅ THIS LINE

        ];
    }
}
