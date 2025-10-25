<?php

namespace App\Livewire;

use App\Models\MaximalPayment;
use App\Models\Mitra;
use App\Models\Survey;
use App\Models\Transaction;
use App\Services\MLRecommendationService;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Contracts\TranslatableContentDriver;
use Filament\Support\Facades\Filament;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Get;
use Filament\Support\Exceptions\Halt;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class MitraPicker extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public int $surveyId;
    public ?Survey $survey = null;
    public array $mlRecommendations = [];

    public function mount(int $surveyId): void
    {
        $this->surveyId = $surveyId;
        $this->survey   = Survey::with('masterSurvey')->find($surveyId);
        
        // Fetch ML recommendations on mount
        $this->fetchMLRecommendations();
    }

    protected function fetchMLRecommendations(): void
    {
        if (!$this->survey) {
            Log::warning("⚠️ Cannot fetch ML recommendations: Survey is null");
            return;
        }
        
        if (!$this->survey->masterSurvey) {
            Log::warning("⚠️ Cannot fetch ML recommendations: MasterSurvey is null for survey {$this->surveyId}");
            return;
        }
        
        if (!$this->survey->masterSurvey->type) {
            Log::warning("⚠️ Cannot fetch ML recommendations: Survey type is null for master_survey_id {$this->survey->master_survey_id}");
            return;
        }

        $surveyType = $this->survey->masterSurvey->type;
        
        Log::info("🎯 Starting ML recommendations fetch", [
            'survey_id' => $this->surveyId,
            'master_survey_id' => $this->survey->master_survey_id,
            'survey_type' => $surveyType,
            'master_survey_name' => $this->survey->masterSurvey->name ?? 'N/A'
        ]);

        try {
            $mlService = new MLRecommendationService();
            $result = $mlService->getRecommendations($surveyType, 100);

            Log::info("📡 API Response received", [
                'success' => $result['success'],
                'data_count' => count($result['data'] ?? []),
                'message' => $result['message'] ?? 'No message'
            ]);

            if ($result['success'] && !empty($result['data'])) {
                // Store FULL recommendation data with ALL parameters from API
                $this->mlRecommendations = collect($result['data'])
                    ->map(fn($rec, $index) => [
                        'mitra_id' => $rec['mitra_id'],
                        'final_rank_score' => (float) ($rec['final_rank_score'] ?? 0), // ML Score (main ranking score)
                        'optimized_score' => (float) ($rec['optimized_score'] ?? 0), // Rating Mitra (PSO optimized)
                        'survey_score' => (float) ($rec['survey_score'] ?? 0), // Average rating survey
                        'jumlah_survey' => (int) ($rec['jumlah_survey'] ?? 0), // Total survey count
                        'rank' => $index + 1, // Ranking position (1-based, sorted by API)
                    ])
                    ->keyBy('mitra_id')
                    ->toArray();
                    
                Log::info("✅ Loaded " . count($this->mlRecommendations) . " ML recommendations", [
                    'survey_id' => $this->surveyId,
                    'survey_type' => $surveyType,
                    'top_3' => array_slice(array_map(fn($id) => [
                        'id' => $id,
                        'rank' => $this->mlRecommendations[$id]['rank'],
                        'final_rank_score' => $this->mlRecommendations[$id]['final_rank_score'],
                        'optimized_score' => $this->mlRecommendations[$id]['optimized_score'],
                        'survey_score' => $this->mlRecommendations[$id]['survey_score'],
                        'jumlah_survey' => $this->mlRecommendations[$id]['jumlah_survey']
                    ], array_keys($this->mlRecommendations)), 0, 3)
                ]);
            } else {
                Log::warning("❌ Failed to load ML recommendations", [
                    'success' => $result['success'],
                    'message' => $result['message'] ?? 'No data',
                    'survey_type' => $surveyType
                ]);
            }
        } catch (\Exception $e) {
            Log::error("🚨 ML Recommendations exception", [
                'error' => $e->getMessage(),
                'survey_id' => $this->surveyId,
                'survey_type' => $surveyType,
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        }
    }

    public function getTranslatableLocales(): array
    {
        return [app()->getLocale()];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->mitraQuery())
            ->columns([
                TextColumn::make('final_rank_score')
                    ->label('ML Score')
                    ->numeric(decimalPlaces: 1)
                    ->formatStateUsing(fn ($state) => $state !== null ? number_format($state * 100, 1) . '%' : '-')
                    ->badge()
                    ->color(fn ($state) => match(true) {
                        $state >= 0.8 => 'success',
                        $state >= 0.6 => 'warning',
                        default => 'gray'
                    })
                    ->sortable()
                    ->description('ML Final Rank Score')
                    ->tooltip('Higher score = Better predicted match'),
                
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->description(fn (\App\Models\Mitra $record) => $record->email, position: 'below'),

                TextColumn::make('optimized_score')
                    ->label('Rating Mitra')
                    ->formatStateUsing(fn ($state) => $state !== null && $state > 0 ? number_format((float) $state * 100, 1) . '%' : '-')
                    ->description('PSO Optimized Score')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => match(true) {
                        $state >= 0.9 => 'success',
                        $state >= 0.7 => 'warning',
                        $state > 0 => 'gray',
                        default => 'gray'
                    }),

                TextColumn::make('avg_rating')
                    ->label('Average Rating Survey')
                    ->formatStateUsing(function ($state, Mitra $record) {
                        // Use survey_score from API virtual column
                        $apiScore = $record->api_survey_score ?? null;
                        $fmt = fn ($v) => $v !== null && $v > 0 ? number_format((float) $v, 2) : '-';
                        
                        if ($apiScore !== null && $apiScore > 0) {
                            return $fmt($apiScore) . ' / ' . $fmt($record->avg_rating_this_master);
                        }
                        
                        return $fmt($state) . ' / ' . $fmt($record->avg_rating_this_master);
                    })
                    ->description('From Surveys / This Master')
                    ->sortable(),

                TextColumn::make('surveys_count')
                    ->label('Jumlah Survey')
                    ->formatStateUsing(fn ($state, Mitra $record) => 
                        // Use jumlah_survey from API virtual column
                        ($record->api_jumlah_survey ?? $state) . 
                        ' / ' . 
                        (int) ($record->worked_this_master ?? 0)
                    )
                    ->description('Total / This Master')
                    ->sortable(query: fn (Builder $query, string $direction) =>
                        $query->orderBy('surveys_count', $direction)
                    ),

                TextColumn::make('payment_this_month')
                    ->label('Payment This Month')
                    ->formatStateUsing(function ($state, Mitra $record) {
                        return $state !== null ? 'IDR ' . number_format($state, 0, ',', '.') : 'IDR 0';
                    })
                    ->description('Total rate bulan ini')
                    ->sortable(),
                ])
                ->actions([
                    TableAction::make('add')
                        ->label('Add')
                        ->icon('heroicon-o-plus')
                        ->color('success')

                        // 🔒 Disable if already assigned OR cap already reached for this month
                        ->disabled(function (Mitra $record): bool {
                            if ($this->alreadyAssigned($record->getKey())) {
                                return true;
                            }

                            $cap = MaximalPayment::value(); // 0 = no cap
                            if ($cap <= 0) return false;

                            $paidThisMonth = (int) ($record->payment_this_month ?? 0);
                            return $paidThisMonth >= $cap;
                        })
                        ->modalHeading(fn (Mitra $record) => 'Add Assignment: ' . $record->name)

                        // 🧾 Form with live preview (rate locked to survey rate)
                        ->form(function (Mitra $record) {
                            $cap   = MaximalPayment::value();
                            $month = (int) $this->survey->payment_month;
                            $year  = (int) $this->survey->year;

                            // authoritative current sum (server)
                            $current = (int) DB::table('transactions')
                                ->join('surveys', 'surveys.id', '=', 'transactions.survey_id')
                                ->where('transactions.mitra_id', $record->getKey())
                                ->where('surveys.payment_month', $month)
                                ->where('surveys.year', $year)
                                ->selectRaw('COALESCE(SUM(transactions.target * transactions.rate), 0) AS total')
                                ->value('total');

                            $fmt = fn (int $v) => 'Rp' . number_format($v, 0, ',', '.');

                            return [
                                Section::make() // compact summary bar
                                    ->compact()
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                Placeholder::make('cap_info')
                                                    ->label('Batas Bayar')
                                                    ->inlineLabel()
                                                    ->content($cap > 0 ? $fmt($cap) : '—')
                                                    ->extraAttributes(['class' => 'text-xs text-gray-600']),

                                                Placeholder::make('paid_now')
                                                    ->label('Dibayar')
                                                    ->inlineLabel()
                                                    ->content($fmt($current))
                                                    ->extraAttributes(['class' => 'text-xs text-gray-600']),

                                                Placeholder::make('remain_now')
                                                    ->label('Sisa')
                                                    ->inlineLabel()
                                                    ->content($cap > 0 ? $fmt(max(0, $cap - $current)) : '—')
                                                    ->extraAttributes(['class' => 'text-xs text-gray-600']),
                                            ])
                                            ->extraAttributes(['class' => 'gap-2']),
                                    ]),

                                        Grid::make(2) // inputs, still compact
                                            ->schema([
                                                TextInput::make('target')
                                                    ->label('Target')
                                                    ->numeric()
                                                    ->required()
                                                    ->minValue(0)
                                                    ->live(debounce: 300),

                                                TextInput::make('rate')
                                                    ->label('Rate (IDR)')
                                                    ->numeric()
                                                    ->required()
                                                    ->disabled()
                                                    ->default((int) ($this->survey?->rate ?? 0))
                                                    ->minValue(0)
                                                    ->live(debounce: 300),
                                            ])
                                            ->extraAttributes(['class' => 'gap-3 mt-1']),

                                        // Mini totals box
                                        Section::make()
                                            ->compact()
                                            ->schema([
                                                Placeholder::make('tambah_display')
                                                    ->label('Tambah')
                                                    ->inlineLabel()
                                                    ->content(function (Get $get) use ($fmt): string {
                                                        $target = (int) ($get('target') ?? 0);
                                                        $rate   = (int) ($get('rate') ?? 0);
                                                        return $fmt($target * $rate);
                                                    })
                                                    ->extraAttributes(['class' => 'text-xs text-gray-600']),

                                                Placeholder::make('total_display')
                                                    ->label('Total')
                                                    ->inlineLabel()
                                                    ->content(function (Get $get) use ($current, $fmt): string {
                                                        $target = (int) ($get('target') ?? 0);
                                                        $rate   = (int) ($get('rate') ?? 0);
                                                        return $fmt($current + ($target * $rate));
                                                    })
                                                    ->extraAttributes(['class' => 'text-xs text-gray-600']),

                                                Placeholder::make('sisa_display')
                                                    ->label('Sisa')
                                                    ->inlineLabel()
                                                    ->content(function (Get $get) use ($current, $cap, $fmt): string {
                                                        if ($cap <= 0) return '—';
                                                        $target = (int) ($get('target') ?? 0);
                                                        $rate   = (int) ($get('rate') ?? 0);
                                                        $new = $current + ($target * $rate);
                                                        return $fmt(max(0, $cap - $new));
                                                    })
                                                    ->extraAttributes(['class' => 'text-xs text-gray-600']),
                                            ])
                                            ->extraAttributes(['class' => 'mt-1']),
                            ];
                        })

                        // ✅ Process + validation
                        ->action(function (Mitra $record, array $data) {
                            $target = (int) ($data['target'] ?? 0);
                            $rate   = (int) ($data['rate'] ?? 0);

                            if ($target <= 0 || $rate <= 0) {
                                Notification::make()->title('Target dan rate harus > 0')->danger()->send();
                                throw new Halt;
                            }

                            $cap = MaximalPayment::value();
                            if ($cap > 0) {
                                $month  = (int) $this->survey->payment_month;
                                $year   = (int) $this->survey->year;

                                $current = (int) DB::table('transactions')
                                    ->join('surveys', 'surveys.id', '=', 'transactions.survey_id')
                                    ->where('transactions.mitra_id', $record->getKey())
                                    ->where('surveys.payment_month', $month)
                                    ->where('surveys.year', $year)
                                    ->selectRaw('COALESCE(SUM(transactions.target * transactions.rate), 0) AS total')
                                    ->value('total');

                                $newTotal = $current + ($target * $rate);
                                if ($newTotal > $cap) {
                                    Notification::make()
                                        ->title('Payment akan melebihi batas maksimal.')
                                        ->danger()
                                        ->send();
                                    throw new Halt;
                                }
                            }

                            // 2) Create the transaction
                            Transaction::create([
                                'survey_id' => $this->surveyId,
                                'mitra_id'  => $record->getKey(),
                                'target'    => $target,
                                'rate'      => $rate,
                            ]);

                            Notification::make()->title('Mitra ditambahkan ke assignment.')->success()->send();
                            $this->dispatch('mitra-added');
                        })
                ])
            ->filters([
                TernaryFilter::make('worked_this_master')
                    ->label('Pernah di master ini?')
                    ->trueLabel('Pernah')
                    ->falseLabel('Belum')
                    ->queries(
                        true: fn ($query)  => $query->having('worked_this_master', '>', 0),
                        false: fn ($query) => $query->having('worked_this_master', '=', 0),
                    ),

                Filter::make('avg_thresholds')
                    ->label('Min Avg')
                    ->form([
                        TextInput::make('min_all')->label('All ≥')->numeric(),
                        TextInput::make('min_master')->label('Master ≥')->numeric(),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                filled($data['min_all'] ?? null),
                                fn ($query) => $query->having('avg_rating', '>=', (float) $data['min_all'])
                            )
                            ->when(
                                filled($data['min_master'] ?? null),
                                fn ($query) => $query->having('avg_rating_this_master', '>=', (float) $data['min_master'])
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $chips = [];
                        if (filled($data['min_all'] ?? null)) {
                            $chips[] = 'All ≥ ' . number_format((float) $data['min_all'], 2);
                        }
                        if (filled($data['min_master'] ?? null)) {
                            $chips[] = 'Master ≥ ' . number_format((float) $data['min_master'], 2);
                        }
                        return $chips;
                    }),
            ])
            ->paginationPageOptions([10, 25, 50])
            ->defaultSort('final_rank_score', 'desc'); // Sort by ML final rank score by default
    }

    protected function mitraQuery(): Builder
    {
        $masterId = $this->survey->master_survey_id;
        $month    = (int) $this->survey->payment_month;
        $year     = $this->survey->year;
        $mitraKey = (new Mitra())->getQualifiedKeyName();

        $query = \App\Models\Mitra::query();

        // 🎯 FILTER: Only show mitras from ML recommendations IF available
        if (!empty($this->mlRecommendations)) {
            $mitraIds = array_keys($this->mlRecommendations);
            $query->whereIn('id', $mitraIds);
            
            Log::info("📋 Filtering to " . count($mitraIds) . " recommended mitras");
        } else {
            // If no ML recommendations, show all mitras (fallback mode)
            Log::warning("⚠️ No ML recommendations available, showing all mitras");
        }

        $query
            // Rating Mitra: Overall average from nilai1s table
            ->withAvg(['nilai1s as mitra_rating'], 'rerata')
            
            // Average Rating Survey: Per survey ratings
            ->withAvg(
                [
                'nilai1s as avg_rating',
                'nilai1s as avg_rating_this_master' => fn ($query) =>
                    $query->join('surveys', 'surveys.id', '=', 'transactions.survey_id')
                      ->where('surveys.master_survey_id', $masterId)
                ], 'rerata')

            ->withCount('surveys as surveys_count')

            ->withCount([
                'surveys as worked_this_master' => fn ($query) =>
                    $query->where('surveys.master_survey_id', $masterId),
            ])

            ->withCount([
                'transactions as in_this_survey' => fn ($query) =>
                    $query->where('survey_id', $this->surveyId),
            ])
            ->addSelect([
                'payment_this_month' => Transaction::query()
                    ->selectRaw('COALESCE(SUM(transactions.target * transactions.rate), 0)')
                    ->join('surveys', 'surveys.id', '=', 'transactions.survey_id')
                    ->where('surveys.payment_month', $month)
                    ->when($year, fn ($q) => $q->where('surveys.year', $year))
                    ->whereColumn('transactions.mitra_id', $mitraKey),
            ]);

        // Add ML scores and ranking as virtual columns
        if (!empty($this->mlRecommendations)) {
            // Build CASE statement for final_rank_score (ML Score column)
            $finalRankCases = collect($this->mlRecommendations)
                ->map(fn($data, $mitraId) => "WHEN {$mitraId} THEN {$data['final_rank_score']}")
                ->join(' ');
            
            // Build CASE statement for optimized_score (Rating Mitra column)
            $optimizedCases = collect($this->mlRecommendations)
                ->map(fn($data, $mitraId) => "WHEN {$mitraId} THEN {$data['optimized_score']}")
                ->join(' ');
            
            // Build CASE statement for rank
            $rankCases = collect($this->mlRecommendations)
                ->map(fn($data, $mitraId) => "WHEN {$mitraId} THEN {$data['rank']}")
                ->join(' ');
            
            // Build CASE statement for survey_score
            $surveyCases = collect($this->mlRecommendations)
                ->map(fn($data, $mitraId) => "WHEN {$mitraId} THEN {$data['survey_score']}")
                ->join(' ');
            
            // Build CASE statement for jumlah_survey
            $countCases = collect($this->mlRecommendations)
                ->map(fn($data, $mitraId) => "WHEN {$mitraId} THEN {$data['jumlah_survey']}")
                ->join(' ');
            
            $query
                ->addSelect(DB::raw("(CASE mitras.id {$finalRankCases} ELSE 0 END) as final_rank_score"))
                ->addSelect(DB::raw("(CASE mitras.id {$optimizedCases} ELSE 0 END) as optimized_score"))
                ->addSelect(DB::raw("(CASE mitras.id {$rankCases} ELSE 999 END) as ml_rank"))
                ->addSelect(DB::raw("(CASE mitras.id {$surveyCases} ELSE 0 END) as api_survey_score"))
                ->addSelect(DB::raw("(CASE mitras.id {$countCases} ELSE 0 END) as api_jumlah_survey"))
                ->orderByRaw("ml_rank ASC"); // Sort by API ranking
                
            Log::info("✅ Applied ML scoring with " . count($this->mlRecommendations) . " scores");
        } else {
            // Fallback: no ML scores, sort by overall rating
            $query
                ->addSelect(DB::raw("0 as final_rank_score"))
                ->addSelect(DB::raw("0 as optimized_score"))
                ->addSelect(DB::raw("999 as ml_rank"))
                ->orderBy('mitra_rating', 'desc'); // Fallback sort by rating
                
            Log::info("⚠️ Fallback mode: sorting by mitra_rating");
        }

        return $query;
    }

    protected function alreadyAssigned(int $mitraId): bool
    {
        return \App\Models\Transaction::query()
            ->where('mitra_id', $mitraId)
            ->where('survey_id', $this->surveyId)
            ->exists();
    }

    public function render()
    {
        return view('livewire.mitra-picker');
    }

    protected function getAddDisabledReason(\App\Models\Mitra $record): ?string
    {
        if ($this->alreadyAssigned($record->getKey())) {
            return 'Mitra sudah ditugaskan di survei ini.';
        }

        $cap = \App\Models\MaximalPayment::value();
        if ($cap > 0) {
            $paid = (int) ($record->payment_this_month ?? 0);
            if ($paid >= $cap) {
                return 'Batas pembayaran bulanan sudah tercapai.';
            }
        }

        return null;
    }
}
