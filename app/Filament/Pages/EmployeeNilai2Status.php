<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use App\Models\Employee;
use App\Models\Team;
use App\Models\MitraTeladan;
use App\Models\Nilai2;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Actions\Action;

class EmployeeNilai2Status extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static string $view = 'filament.pages.employee-nilai2-status';
    public static bool $shouldRegisterNavigation = true;

    public static function getNavigationGroup(): ?string
    {
        return 'Reports';
    }

    public $selectedYear;
    public $selectedQuarter;
    public $teams = [];
    public $employees = [];
    public $statusTable = [];

    public function mount()
    {
        $this->selectedYear = now()->year;
        $this->selectedQuarter = ceil(now()->month / 3);
    }

    public function loadData()
    {
        $this->teams = Team::where('has_survey', true)->take(6)->get();
        $this->employees = Employee::with('user')->get();
        $mitraTeladans = MitraTeladan::where('year', $this->selectedYear)
            ->where('quarter', $this->selectedQuarter)
            ->get();

        $statusTable = [];
        foreach ($this->employees as $employee) {
            $row = [
                'employee' => $employee,
                'statuses' => [],
            ];
            foreach ($this->teams as $team) {
                $mitraTeladan = $mitraTeladans->firstWhere('team_id', $team->id);
                if ($mitraTeladan) {
                    $hasNilai2 = Nilai2::where('mitra_teladan_id', $mitraTeladan->id)
                        ->where('user_id', $employee->user_id)
                        ->exists();
                    $row['statuses'][$team->id] = $hasNilai2 ? 'Sudah' : 'Belum';
                } else {
                    $row['statuses'][$team->id] = '-';
                }
            }
            $statusTable[] = $row;
        }
        $this->statusTable = $statusTable;
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            Grid::make('selectPeriod')
                ->columns(2)
                ->schema([
                    Select::make('selectedYear')
                        ->label('Tahun')
                        ->options(collect(range(now()->year, 2020))->mapWithKeys(fn($y) => [$y => $y])->toArray())
                        ->required(),

                    Select::make('selectedQuarter')
                        ->label('Kuartal')
                        ->options([
                            1 => 'Q1',
                            2 => 'Q2',
                            3 => 'Q3',
                            4 => 'Q4',
                        ])
                        ->required(),
                ]),
        ]);
    }

    public function filterAction(): Action
    {
        return Action::make('filter')
            ->label('Filter')
            ->action(fn () => $this->loadData())
            ->color('primary')
            ->icon('heroicon-m-magnifying-glass');
    }
}
