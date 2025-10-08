<?php

namespace App\Livewire;

use App\Models\MaximalPayment;
use App\Models\Mitra;
use App\Models\Survey;
use App\Models\Transaction;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Contracts\TranslatableContentDriver;
use Filament\Support\Facades\Filament;
use Filament\Forms\Contracts\HasForms;                 // ✅ add
use Filament\Forms\Concerns\InteractsWithForms;       // ✅ add
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
use Livewire\Component;

class MitraPicker extends Component implements HasTable, HasForms   // ✅ add HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;                                       // ✅ add

    public int $surveyId;
    public ?Survey $survey = null;

    public function mount(int $surveyId): void
    {
        $this->surveyId = $surveyId;
        $this->survey   = Survey::find($surveyId);
    }

    // // Satisfy translatable requirement
    // public function makeFilamentTranslatableContentDriver(): ?TranslatableContentDriver
    // {
    //     return Filament::getCurrentPanel()?->makeTranslatableContentDriver($this);
    // }

    public function getTranslatableLocales(): array
    {
        return [app()->getLocale()];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->mitraQuery())
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->description(fn (\App\Models\Mitra $record) => $record->email, position: 'below'),

                TextColumn::make('avg_rating')
                    ->label('Average Rating')
                    ->numeric(decimalPlaces: 2)
                    ->formatStateUsing(function ($state, Mitra $record) {
                        $fmt = fn ($v) => $v !== null ? number_format((float) $v, 2) : '-';
                        return $fmt($state) . ' / ' . $fmt($record->avg_rating_this_master);
                    })
                    // ->formatStateUsing(fn ($state) => $state !== null ? number_format($state, 2) : '—')
                    ->sortable(),


                TextColumn::make('surveys_count')
                    ->label('Jumlah (All / Master)')
                    ->formatStateUsing(fn ($state, Mitra $record) =>
                        (int) $state . ' / ' . (int) ($record->worked_this_master ?? 0)
                    )
                    // sort by the “all” count (change to worked_this_master if you prefer)
                    ->sortable(query: fn (Builder $query, string $direction) =>
                        $query->orderBy('surveys_count', $direction)
                ),

                TextColumn::make('payment_this_month')
                    ->label('Payment This Month')
                    ->formatStateUsing(function ($state, Mitra $record) {
                        // Assume payment_this_month is eager loaded or calculated in the query
                        // If not, you may need to calculate it here
                        return $state !== null ? 'IDR ' . number_format($state, 0, ',', '.') : '-';
                    })
                    ->sortable(),
                // TextColumn::make('worked_this_master') // this is the alias from withCount()
                //     ->label('Pernah di Master ini?')
                //     ->formatStateUsing(fn ($state) => (int) $state > 0 ? 'Pernah' : 'Belum')
                //     ->badge()
                //     ->color(fn ($state) => (int) $state > 0 ? 'success' : 'gray')
                //     ->sortable(
                //         query: fn (Builder $query, string $direction) =>
                //             $query->orderBy('worked_this_master', $direction)
                //     ),

                // TextColumn::make('status_badge')
                //     ->label('Status')
                //     ->formatStateUsing(fn (Mitra $record) =>
                //         $this->alreadyAssigned($record->id) ? 'Sudah ditugaskan' : 'Belum'
                //     )
                //     ->badge()
                //     ->color(fn (Mitra $record) => $this->alreadyAssigned($record->id) ? 'success' : 'gray'),
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

                                        Grid::make(2) // compact preview
                                            ->schema([
                                                Placeholder::make('projected_add')
                                                    ->label('Tambah')
                                                    ->inlineLabel()
                                                    ->content(fn (Get $get) => $fmt(((int) $get('target')) * ((int) $get('rate'))))
                                                    ->extraAttributes(['class' => 'text-xs text-gray-600']),

                                                Placeholder::make('projected_total')
                                                    ->label('Total')
                                                    ->inlineLabel()
                                                    ->content(fn (Get $get) => $fmt($current + (((int) $get('target')) * ((int) $get('rate')))))
                                                    ->extraAttributes(['class' => 'text-xs text-gray-600']),
                                            ])
                                            ->extraAttributes(['class' => 'gap-2 mt-1']),
                                    ];
                        })

                        // ✅ Submit (race-condition safe): recheck against DB
                        ->action(function (Mitra $record, array $data) {
                            // 1) Prevent duplicate in this survey
                            if ($this->alreadyAssigned($record->getKey())) {
                                Notification::make()->title('Mitra sudah ada di survei ini.')->warning()->send();
                                return;
                            }

                            $cap   = MaximalPayment::value(); // 0 => no cap
                            $month = (int) $this->survey->payment_month;
                            $year  = (int) $this->survey->year;

                            $current = (int) DB::table('transactions')
                                ->join('surveys', 'surveys.id', '=', 'transactions.survey_id')
                                ->where('transactions.mitra_id', $record->getKey())
                                ->where('surveys.payment_month', $month)
                                ->where('surveys.year', $year)
                                ->selectRaw('COALESCE(SUM(transactions.target * transactions.rate), 0) AS total')
                                ->value('total');

                            $target = (int) ($data['target'] ?? 0);
                            $rate   = (int) ($data['rate'] ?? ($this->survey?->rate ?? 0));
                            $added  = $target * $rate;

                            if ($cap > 0 && ($current + $added) > $cap) {
                                $remaining = max(0, $cap - $current);
                                Notification::make()
                                    ->title('Melebihi batas pembayaran bulanan')
                                    ->body('Sisa kuota bulan ini: Rp' . number_format($remaining, 0, ',', '.'))
                                    ->danger()
                                    ->send();
                                // return;
                                throw new Halt;
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
                // ✅ In this survey? (Yes / No / Any)
                // TernaryFilter::make('in_this_survey')
                //     ->label('Di survei ini?')
                //     ->trueLabel('Sudah')
                //     ->falseLabel('Belum')
                //     ->queries(
                //         true: fn ($query)  => $query->having('in_this_survey', '>', 0),
                //         false: fn ($query) => $query->having('in_this_survey', '=', 0),
                //     ),

                // ✅ Ever worked under the same master? (Yes / No / Any)
                TernaryFilter::make('worked_this_master')
                    ->label('Pernah di master ini?')
                    ->trueLabel('Pernah')
                    ->falseLabel('Belum')
                    ->queries(
                        true: fn ($query)  => $query->having('worked_this_master', '>', 0),
                        false: fn ($query) => $query->having('worked_this_master', '=', 0),
                    ),

                // ✅ Thresholds for averages (overall & master)
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
            ->paginationPageOptions([10, 25, 50]);
    }

    protected function mitraQuery(): Builder
    {
        $masterId = $this->survey->master_survey_id;

        $month      = (int) $this->survey->payment_month;   // 1..12
        $year       = $this->survey->year;

        $mitraKey = (new Mitra())->getQualifiedKeyName(); // ✅ "mitras.id" or "mitras.id_sobat"

        return \App\Models\Mitra::query()
            // average rating for THIS survey
            // ->withAvg([
            //     'nilai1s as avg_rating' => fn ($query) =>
            //         $query->where('transactions.survey_id', $this->surveyId),
            // ], 'rerata')
            ->withAvg(
                [
                'nilai1s as avg_rating',
                'nilai1s as avg_rating_this_master' => fn ($query) =>
                    $query->join('surveys', 'surveys.id', '=', 'transactions.survey_id')
                      ->where('surveys.master_survey_id', $masterId)
                ], 'rerata')

            // how many surveys ever (via transactions)
            ->withCount('surveys as surveys_count')

            // worked under the SAME MASTER as current survey?
            ->withCount([
                'surveys as worked_this_master' => fn ($query) =>
                    $query->where('surveys.master_survey_id', $masterId),
            ])

            // has a transaction in THIS survey (fastest way)
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
    }

    protected function alreadyAssigned(int $mitraId): bool
    {
        $masterId = $this->survey?->master_survey_id;
        if (! $masterId) {
            return false;
        }

        // If Transaction has `survey()` relation:
        return \App\Models\Transaction::query()
            ->where('mitra_id', $mitraId)
            ->whereHas('survey', fn ($query) => $query->where('master_survey_id', $masterId))
            ->exists();

        // Or, if you prefer a JOIN (doesn't require the relation):
        /*
        return \App\Models\Transaction::query()
            ->join('surveys', 'surveys.id', '=', 'transactions.survey_id')
            ->where('transactions.mitra_id', $mitraId)
            ->where('surveys.master_survey_id', $masterId)
            ->exists();
        */
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

        $cap = \App\Models\MaximalPayment::value(); // 0 = no cap
        if ($cap > 0) {
            $paid = (int) ($record->payment_this_month ?? 0); // from your query addSelect
            if ($paid >= $cap) {
                return 'Batas pembayaran bulanan sudah tercapai.';
            }
        }

        return null; // no reason -> not disabled
    }
}
