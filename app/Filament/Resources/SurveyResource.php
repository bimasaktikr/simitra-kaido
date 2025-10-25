<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyResource\Pages;
use App\Filament\Resources\SurveyResource\RelationManagers;
use App\Models\Survey;
use App\Services\MLRecommendationService;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Surveys';
    
    protected static ?int $navigationSort = 2;


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
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if ($state) {
                                            // Clear previous recommendations
                                            $set('ml_recommendations', null);
                                            $set('ml_recommendations_loaded', false);
                                            
                                            // Get survey type from master_survey
                                            $masterSurvey = \App\Models\MasterSurvey::find($state);
                                            
                                            \Log::info("🎯 Create Survey - Master Survey Selected", [
                                                'master_survey_id' => $state,
                                                'master_survey_name' => $masterSurvey?->name,
                                                'type' => $masterSurvey?->type
                                            ]);
                                            
                                            if ($masterSurvey && $masterSurvey->type) {
                                                $set('survey_type_detected', $masterSurvey->type);
                                                
                                                // Auto-fetch ML recommendations (silently)
                                                try {
                                                    $mlService = new MLRecommendationService();
                                                    $result = $mlService->getRecommendations($masterSurvey->type, 20);
                                                    
                                                    \Log::info("📥 Create Survey - ML API Response", [
                                                        'success' => $result['success'],
                                                        'total' => $result['total'] ?? 0,
                                                        'data_count' => count($result['data'] ?? []),
                                                        'first_item' => !empty($result['data']) ? $result['data'][0] : null
                                                    ]);
                                                    
                                                    if ($result['success'] && !empty($result['data'])) {
                                                        $set('ml_recommendations', $result['data']);
                                                        $set('ml_recommendations_loaded', true);
                                                    }
                                                } catch (\Exception $e) {
                                                    \Log::error("🚨 Create Survey - ML API Error", [
                                                        'error' => $e->getMessage(),
                                                        'file' => $e->getFile(),
                                                        'line' => $e->getLine()
                                                    ]);
                                                }
                                            }
                                        }
                                    })
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Nama Survey')
                                            ->required(),
                                        Forms\Components\TextInput::make('code')
                                            ->label('Kode Survey')
                                            ->required(),
                                        Forms\Components\Select::make('type')
                                            ->label('Survey Type')
                                            ->options([
                                                'Rumah Tangga' => 'Rumah Tangga',
                                                'Perusahaan' => 'Perusahaan',
                                            ])
                                            ->required()
                                            ->helperText('Required for ML recommendations'),
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
                
                // ML Recommendations Section
                Section::make('ML Recommended Mitras')
                    ->description('Machine learning optimized mitra recommendations based on PSO algorithm')
                    ->schema([
                        Placeholder::make('ml_info')
                            ->label('')
                            ->content(function (callable $get) {
                                $loaded = $get('ml_recommendations_loaded');
                                $surveyType = $get('survey_type_detected');
                                
                                if ($loaded) {
                                    return "✅ Recommendations loaded for: {$surveyType} | Sorted by ML Score (higher = better match)";
                                } else {
                                    return "⏳ Select a Master Survey to load ML recommendations automatically...";
                                }
                            })
                            ->columnSpanFull(),
                        
                        \Filament\Forms\Components\Actions::make([
                            \Filament\Forms\Components\Actions\Action::make('refresh_ml_recommendations')
                                ->label('Refresh Recommendations')
                                ->icon('heroicon-o-arrow-path')
                                ->color('primary')
                                ->action(function (callable $get, callable $set) {
                                    $masterSurveyId = $get('master_survey_id');
                                    
                                    if (!$masterSurveyId) {
                                        return;
                                    }
                                    
                                    // Clear current recommendations
                                    $set('ml_recommendations', null);
                                    $set('ml_recommendations_loaded', false);
                                    
                                    // Reload recommendations
                                    $masterSurvey = \App\Models\MasterSurvey::find($masterSurveyId);
                                    
                                    if (!$masterSurvey) {
                                        return;
                                    }
                                    
                                    $mlService = new \App\Services\MLRecommendationService();
                                    $result = $mlService->getRecommendations($masterSurvey->type, 20);
                                    
                                    if ($result['success']) {
                                        $set('ml_recommendations', $result['data']);
                                        $set('ml_recommendations_loaded', true);
                                        $set('survey_type_detected', $masterSurvey->type);
                                    }
                                })
                                ->visible(fn (callable $get) => $get('ml_recommendations_loaded') === true)
                        ])
                        ->columnSpanFull(),
                        
                        ViewField::make('ml_recommendations')
                            ->label('')
                            ->view('filament.forms.components.ml-recommendations-table')
                            ->columnSpanFull()
                            ->visible(fn (callable $get) => $get('ml_recommendations_loaded') === true),
                    ])
                    ->collapsible()
                    ->collapsed(false)
                    ->visible(fn ($operation) => $operation === 'create'),

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
                    ->sortable(),
            ])
            ->defaultSort(function (Builder $query): Builder {
                // Urutkan berdasarkan triwulan terakhir terlebih dahulu, kemudian is_scored
                return $query
                    ->orderByDesc('triwulan')
                    ->orderByDesc('year')
                    ->orderBy('is_scored');
            })
            ->filters([
                // Filter berdasarkan quarter (triwulan)
                Tables\Filters\SelectFilter::make('triwulan')
                    ->label('Quarter')
                    ->options([
                        1 => 'Q1',
                        2 => 'Q2',
                        3 => 'Q3',
                        4 => 'Q4',
                    ]),
                // Filter berdasarkan team
                Tables\Filters\SelectFilter::make('team_id')
                    ->label('Team')
                    ->relationship('team', 'name'),
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
