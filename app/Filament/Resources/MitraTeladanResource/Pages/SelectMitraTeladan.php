<?php

namespace App\Filament\Resources\MitraTeladanResource\Pages;

use App\Filament\Resources\MitraTeladanResource;
use App\Models\MitraTeladan;
use App\Models\Team;
use App\Services\MitraService;
use App\Services\Nilai2Service;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Actions;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class SelectMitraTeladan extends Page
{
    // use InteractsWithTable;

    protected static string $resource = MitraTeladanResource::class;
    protected static string $view = 'filament.resources.mitra-teladan-resource.pages.select-mitra-teladan';

    public int $selectedYear;
    public int $selectedQuarter;
    public array $tableData = [];

    public function mount(): void
    {
        $this->selectedYear = now()->year;
        $this->selectedQuarter = ceil(now()->month / 3);
        // $this->loadData();
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Grid::make(2)->schema([
                Select::make('selectedYear')
                    ->label('Year')
                    ->options(array_combine(
                        range(now()->year - 5, now()->year),
                        range(now()->year - 5, now()->year)
                    ))
                    ->required(),

                Select::make('selectedQuarter')
                    ->label('Quarter')
                    ->options([
                        1 => 'Q1',
                        2 => 'Q2',
                        3 => 'Q3',
                        4 => 'Q4',
                    ])
                    ->required(),
            ]),
            Actions::make([
                FormAction::make('loadData')
                    ->label('Load Data')
                    ->action('loadData')
                    ->icon('heroicon-o-arrow-path')
                    ->color('primary')
            ])->alignEnd()->fullWidth(),
        ]);
    }

    public function loadData(): void
    {
        logger('[loadData] Triggered'); // <- Tambahkan ini

        $mitraService = app(MitraService::class);
        $nilai2Service = app(Nilai2Service::class);

        $this->tableData = [];

        $check = $mitraService->checkMitraTeladan($this->selectedYear, $this->selectedQuarter);
        $emptyTeams = $check['mitra_teladan_empty'];
        logger(['checkMitraTeladan' => $check]); // <- Tambahkan log ini

        $saved = MitraTeladan::with('mitra')
            ->where('year', $this->selectedYear)
            ->where('quarter', $this->selectedQuarter)
            ->get()
            ->map(fn ($item) => [
                'mitra_id' => $item->mitra_id,
                'team_id' => $item->team_id,
                'mitra_name' => $item->mitra->name ?? '-',
                'avg_rating' => $item->avg_rating_1,
                'surveys_count' => $item->surveys_count,
                'status' => 'final',
                'nilai_2' => $nilai2Service->getAverageRating($item->id),
                'employee_done' => $nilai2Service->getEmployeeDone($item->id),
                'is_all_final' => $nilai2Service->checkFinal($item->id),
            ]);

            // logger('[Saved Type]', get_class($saved));  // Should be Collection

        logger('[Saved mitra_teladan]', ['count' => $saved->count()]);

        $calculated = collect();

        foreach ($emptyTeams as $teamId) {
            logger("[getTopMitra] for team {$teamId}");

            $topMitra = $mitraService->getTopMitra($this->selectedYear, $this->selectedQuarter, $teamId);
            // logger("[Top Mitra] result", ['data' => $topMitra]);
            logger("[Top Mitra] result", ['data' => $topMitra->toArray()]);


            $calculated = $calculated->merge($topMitra);
        }

        // Log::info('[Merged Table Data]', $calculated->toArray());
        // Log::info('[Saved Type]', ['type' => get_class($saved)]);  // Logging class type of $saved
        // Log::info('[Calculated Type]', ['type' => get_class($calculated)]);   // Should be Collection
        $saved = $saved->keyBy('mitra_id');  // Ensures that each item has a unique key
        $calculated = $calculated->keyBy('mitra_id');

        // $this->tableData = $saved->merge($calculated)->toArray();
        $this->tableData = $saved->concat($calculated)->toArray();
        // dd($this->tableData);
        Log::info('Loaded Table Data:', $this->tableData);
        // logger(['tableData' => $this->tableData]); // <- Tambahkan log ini
    }

    public function getTableRecords(): Collection
    {
        return collect($this->tableData);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('mitra_name')->label('Nama Mitra'),
                TextColumn::make('team_id')->label('Team'),
                TextColumn::make('avg_rating')->label('Nilai 1')->numeric(2),
                TextColumn::make('surveys_count')->label('Jumlah Survey'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => $state === 'final' ? 'success' : 'gray'),
            ])
            ->actions([
                Action::make('accept')
                    ->label('Pilih')
                    ->icon('heroicon-m-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record['status'] !== 'final') // only show if not already accepted
                    ->modalHeading('Konfirmasi Pilihan Mitra')
                    ->modalSubheading(fn ($record) => "Yakin memilih {$record['mitra_name']} sebagai Mitra Teladan?")
                    ->action(fn ($record) => $this->acceptMitraTeladan($record)),
            ])
            ->paginated(false);
    }

    public function acceptMitraTeladan(array $record): void
    {
        // Check if already selected (optional)
        $exists = MitraTeladan::where([
            'mitra_id' => $record['mitra_id'],
            'year' => $this->selectedYear,
            'quarter' => $this->selectedQuarter,
        ])->exists();

        if (!$exists) {
            MitraTeladan::create([
                'mitra_id' => $record['mitra_id'],
                'team_id' => $record['team_id'],
                'year' => $this->selectedYear,
                'quarter' => $this->selectedQuarter,
                'avg_rating_1' => $record['avg_rating'],
                'surveys_count' => $record['surveys_count'],
            ]);
        }

        Notification::make()
            ->title("Berhasil memilih {$record['mitra_name']}")
            ->success()
            ->send();

        $this->loadData(); // Refresh the table data
    }


    public function render()
    {
        return view('livewire.select-mitra-teladan');
    }
//     public function query()
//     {
//         return MitraTeladan::query()->whereNull('id'); // dummy for Filament interface
//     }

}
