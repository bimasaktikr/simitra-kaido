<?php

namespace App\Livewire;

use App\Models\Mitra;
use App\Models\Survey;
use App\Models\Transaction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Contracts\TranslatableContentDriver;
use Filament\Support\Facades\Filament;
use Filament\Forms\Contracts\HasForms;                 // ✅ add
use Filament\Forms\Concerns\InteractsWithForms;       // ✅ add
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
                // TextColumn::make('worked_this_master') // this is the alias from withCount()
                //     ->label('Pernah di Master ini?')
                //     ->formatStateUsing(fn ($state) => (int) $state > 0 ? 'Pernah' : 'Belum')
                //     ->badge()
                //     ->color(fn ($state) => (int) $state > 0 ? 'success' : 'gray')
                //     ->sortable(
                //         query: fn (Builder $query, string $direction) =>
                //             $query->orderBy('worked_this_master', $direction)
                //     ),

                TextColumn::make('status_badge')
                    ->label('Status')
                    ->formatStateUsing(fn (Mitra $record) =>
                        $this->alreadyAssigned($record->id) ? 'Sudah ditugaskan' : 'Belum'
                    )
                    ->badge()
                    ->color(fn (Mitra $record) => $this->alreadyAssigned($record->id) ? 'success' : 'gray'),
                ])
            ->actions([
                TableAction::make('add')
                    ->label('Add')
                    ->icon('heroicon-o-plus')
                    ->color('success')
                    ->disabled(fn (Mitra $record) => $this->alreadyAssigned($record->id))
                    ->action(function (Mitra $record) {
                        if ($this->alreadyAssigned($record->id)) {
                            Notification::make()->title('Mitra sudah ada di survei ini.')->warning()->send();
                            return;
                        }

                        Transaction::create([
                            'survey_id' => $this->surveyId,
                            'mitra_id'  => $record->id,
                            'target'    => 0,
                            'rate'      => $this->survey?->rate ?? 0,
                        ]);

                        Notification::make()->title('Mitra ditambahkan ke assignment.')->success()->send();
                        $this->dispatch('mitra-added');
                    }),
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
}
