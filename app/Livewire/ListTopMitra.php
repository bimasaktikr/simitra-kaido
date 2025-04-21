<?php

namespace App\Livewire;

use App\Models\Mitra;
use App\Models\MitraTeladan;
use App\Services\MitraService;
use App\Services\Nilai2Service;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Form;
use Filament\Notifications\Notification;

class ListTopMitra extends Component implements HasForms
{
    use InteractsWithForms;

    public $isOpen = false;
    public ?object $confirmingMitra = null;

    public int $selectedYear;
    public int $selectedQuarter;

    public $savedData = [];
    public $calculatedData = [];
    public $mergedData = [];

    public function mount(): void
    {
        $this->selectedYear = now()->year;
        $this->selectedQuarter = ceil(now()->month / 3);
        $this->loadData();
    }

    public function updatedSelectedYear()
    {
        $this->loadData();
    }

    public function updatedSelectedQuarter()
    {
        $this->loadData();
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
        logger('[loadData] Triggered');

        $mitraService = app(MitraService::class);
        $nilai2Service = app(Nilai2Service::class);

        $this->savedData = [];
        $this->calculatedData = [];

        $check = $mitraService->checkMitraTeladan($this->selectedYear, $this->selectedQuarter);
        $emptyTeams = $check['mitra_teladan_empty'];

        logger(['checkMitraTeladan' => $check]);

        // Get saved data from MitraTeladan table
        $saved = MitraTeladan::with('mitra')
            ->where('year', $this->selectedYear)
            ->where('quarter', $this->selectedQuarter)
            ->get()
            ->map(function ($item) use ($nilai2Service) {
                return [
                    'mitra_id' => $item->mitra_id,
                    'team_id' => $item->team_id,
                    'mitra_name' => $item->mitra->name ?? '-',
                    'avg_rating' => $item->avg_rating_1,
                    'surveys_count' => $item->surveys_count,
                    'status' => 'final', // Mark as final
                    'nilai_2' => $nilai2Service->getAverageRating($item->id),
                    'employee_done' => $nilai2Service->getEmployeeDone($item->id),
                    'is_all_final' => $nilai2Service->checkFinal($item->id),
                ];
            });

        $this->savedData = $saved; // Store saved data

        logger('[Saved mitra_teladan]', ['count' => $saved->count()]);

        $calculated = collect();

        foreach ($emptyTeams as $teamId) {
            logger("[getTopMitra] for team {$teamId}");

            $topMitra = $mitraService->getTopMitra($this->selectedYear, $this->selectedQuarter, $teamId);
            logger("[Top Mitra] result", ['data' => $topMitra->toArray()]);

            // Map calculated mitras with status 'nofinal'
            $topMitra = $topMitra->map(function ($item) {
                $item['status'] = 'nonfinal'; // Mark as nofinal
                return $item;
            });

            $calculated = $calculated->merge($topMitra);
        }

        $this->calculatedData = collect($calculated); // Wrap calculated data in a collection

        // Merge savedData with calculatedData based on mitra_id
        $mergedData = $this->calculatedData->map(function ($calculatedItem) {
            // Find matching item from savedData by mitra_id
            $savedItem = $this->savedData->firstWhere('mitra_id', $calculatedItem['mitra_id']);

            // If there's a matching item in savedData, merge the two items
            if ($savedItem) {
                return array_merge($savedItem, $calculatedItem);
            }

            // If no match, return just the calculated item
            return $calculatedItem;
        });

        // Store merged data in the property
        $this->mergedData = $mergedData;

        // Log the merged data
        Log::info('Merged Table Data:', $this->mergedData->toArray());
    }

    public function openModal($item)
    {
        $mitra = Mitra::find($item['mitra_id']);
        if (!$mitra) {
            logger('Mitra not found for id: ' . $item['mitra_id']);
            return;
        }
        $this->confirmingMitra = $mitra;
        $this->isOpen = true;

        logger('Modal Opened');
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function acceptMitraTeladan(): void
    {
        $record = $this->confirmingMitra;
        logger('Mitra confirmed:', (array) $record);

        $exists = MitraTeladan::where([
            'mitra_id' => $record->mitra_id,
            'year' => $this->selectedYear,
            'quarter' => $this->selectedQuarter,
        ])->exists();

        if (!$exists) {
            MitraTeladan::create([
                'mitra_id' => $record->mitra_id,
                'team_id' => $record->team_id,
                'year' => $this->selectedYear,
                'quarter' => $this->selectedQuarter,
                'avg_rating_1' => $record->avg_rating,
                'surveys_count' => $record->surveys_count,
            ]);
        }

        $this->confirmingMitra = null;
        $this->isOpen = false;

        Log::info("Accepted mitra teladan: " . json_encode($this->confirmingMitra));

        Notification::make()
            ->title("Berhasil memilih {$record->mitra_name}")
            ->success()
            ->send();

        $this->loadData();
    }

    public function render()
    {
        return view('livewire.list-top-mitra');
    }
}
