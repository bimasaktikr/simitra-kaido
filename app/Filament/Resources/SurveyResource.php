<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyResource\Pages;
use App\Filament\Resources\SurveyResource\RelationManagers;
use App\Models\Survey;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
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

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Surveys';


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
                Section::make('Survey Info')
                    ->schema([
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                Select::make('master_survey_id')
                                    ->label('Master Survey')
                                    ->relationship('masterSurvey', 'name')
                                    ->getOptionLabelFromRecordUsing(fn($record) => $record->name . ' (' . $record->code . ')')
                                    ->searchable()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nama Survey')
                                            ->required(),
                                        Forms\Components\TextInput::make('code')
                                            ->label('Kode Survey')
                                            ->required(),
                                    ])
                                    ->required(),
                                Select::make('team_id')
                                    ->label('Team')
                                    ->relationship('team', 'name')
                                    ->required(),
                                Select::make('triwulan')
                                    ->label('Triwulan')
                                    ->options([
                                        1 => 'Q1',
                                        2 => 'Q2',
                                        3 => 'Q3',
                                        4 => 'Q4',
                                    ])
                                    ->required(),
                                Select::make('year')
                                    ->label('Year')
                                    ->options(
                                        collect(range(now()->year, 2020))->mapWithKeys(fn($y) => [$y => $y])->toArray()
                                    )
                                    ->required(),
                            ])
                    ]),

                Section::make('Pembayaran')
                    ->schema([
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                Select::make('payment_month')
                                    ->label('Payment Month')
                                    ->options([
                                        1 => 'January',
                                        2 => 'February',
                                        3 => 'March',
                                        4 => 'April',
                                        5 => 'May',
                                        6 => 'June',
                                        7 => 'July',
                                        8 => 'August',
                                        9 => 'September',
                                        10 => 'October',
                                        11 => 'November',
                                        12 => 'December',
                                    ])
                                    ->required(),
                                Select::make('payment_id')
                                    ->label('Payment Type')
                                    ->relationship('payment', 'payment_type')
                                    ->required(),
                                TextInput::make('rate')
                                    ->numeric()
                                    ->prefix('Rp.')
                                    ->label('Rate')
                                    ->required(),
                            ])
                    ]),


                // FileUpload::make('file')
                //     ->label('Attachment')
                //     ->directory('survey-files')
                //     ->maxSize(2048)
                //     ->preserveFilenames()
                //     ->nullable(),
                Section::make('Status')
                    ->columns(3)
                    ->schema([
                        Select::make('status')
                            ->options([
                                'not started' => 'Not Started',
                                'in progress' => 'In Progress',
                                'done' => 'Done',
                            ])
                            ->label('Status')
                            ->required(),
                        Toggle::make('is_scored')
                            ->label('Scored?')
                            ->disabled(),
                        Toggle::make('is_synced')
                            ->label('Synced?')
                            ->disabled(),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('masterSurvey.name')
                    ->label('Nama Survey')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('masterSurvey.code')
                    ->label('Kode Survey')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('triwulan')
                    ->label('Triwulan')
                    ->formatStateUsing(fn($state) => $state ? 'Q' . $state : null)
                    ->sortable(),
                TextColumn::make('year')
                    ->label('Year')
                    ->sortable(),
                TextColumn::make('payment.name')
                    ->label('Payment Type')
                    ->sortable(),
                TextColumn::make('team.name')
                    ->label('Team')
                    ->sortable(),
                TextColumn::make('rate')
                    ->money('IDR', true)
                    ->sortable(),

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
                    ->orderBy('triwulan', 'desc');
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
