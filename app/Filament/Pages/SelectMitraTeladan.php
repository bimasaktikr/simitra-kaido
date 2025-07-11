<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Team;
use App\Models\Mitra;
use App\Models\Transaction;
use App\Services\MitraService;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Button;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Forms\Components\Grid;
use App\Models\MitraTeladan;
use Filament\Notifications\Notification;
use App\Services\Nilai2Service;

class SelectMitraTeladan extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.select-mitra-teladan';

    protected static ?string $slug = 'select-mitra-teladan';
    protected static ?string $navigationGroup = 'Mitra';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    protected $listeners = [
        'nilai2Saved' => 'loadTopMitras',
    ];

    public array $topMitras = [];
    public array $topMitrasByTeam = [];
    public array $teamTabs = [];
    public string $activeTab = '';
    public int $selectedYear;
    public int $selectedQuarter;
    public array $groupedByTeam = [];
    public array $candidatesForAcceptance = [];
    public array $acceptedMitraTeladan = [];
    public array $acceptedMitraIdByTeam = [];
    public array $canAcceptTeamIds = [];
    public int $aspek1 = 0;
    public int $aspek2 = 0;
    public int $aspek3 = 0;
    public int $aspek4 = 0;
    public int $aspek5 = 0;
    public int $aspek6 = 0;
    public int $aspek7 = 0;
    public int $aspek8 = 0;
    public int $aspek9 = 0;
    public int $aspek10 = 0;

    public function mount(): void
    {
        $this->selectedYear = now()->year;
        $this->selectedQuarter = ceil(now()->month / 3);
    }

    public function loadTopMitras(): void
    {
        $user = auth()->user();
        $canAcceptTeamIds = [];
        if ($user->hasRole('super_admin')) {
            $canAcceptTeamIds = \App\Models\Team::where('has_survey', true)->pluck('id')->toArray();
        } elseif ($user->hasRole('ketua_tim') && $user->employee) {
            $canAcceptTeamIds = [$user->employee->team_id];
        }
        $this->canAcceptTeamIds = $canAcceptTeamIds;

        $year = $this->selectedYear;
        $quarter = $this->selectedQuarter;
        $mitraService = app(MitraService::class);
        $nilai2Service = app(Nilai2Service::class);
        $userId = auth()->id();

        $teams = \App\Models\Team::where('has_survey', true)->get();
        $grouped = [];
        $accepted = [];
        $acceptedMitraIdByTeam = [];

        foreach ($teams as $team) {
            // Get all MitraTeladan for this team and period
            $mitraTeladans = \App\Models\MitraTeladan::where('team_id', $team->id)
                ->where('year', $year)
                ->where('quarter', $quarter)
                ->get();
            // Build array of [mitra_teladan_id, mitra_id, nilai2_average]
            $nilai2Averages = $mitraTeladans->map(function ($mt) use ($nilai2Service) {
                return [
                    'mitra_teladan_id' => $mt->id,
                    'mitra_id' => $mt->mitra_id,
                    'nilai2_average' => $nilai2Service->getAverageRating($mt->id),
                ];
            })->sortByDesc('nilai2_average')->values();
            // Assign ranking
            $rankingMap = [];
            foreach ($nilai2Averages as $idx => $row) {
                $rankingMap[$row['mitra_teladan_id']] = $idx + 1;
            }
            $topMitras = $mitraService->getTopMitra($year, $quarter, $team->id) ?? collect();
            if ($topMitras->isEmpty()) {
                $mitraTeladan = $mitraTeladans->first();
                if ($mitraTeladan) {
                    $mitra = \App\Models\Mitra::find($mitraTeladan->mitra_id);
                    $nilai2Average = $nilai2Service->getAverageRating($mitraTeladan->id);
                    $ranking = isset($rankingMap[$mitraTeladan->id]) ? $rankingMap[$mitraTeladan->id] : null;
                    $accepted[] = [
                        'team_name'     => $team->name,
                        'mitra_name'    => $mitra?->name ?? '-',
                        'avg_rating'    => $mitraTeladan->avg_rating_1 ?? '-',
                        'surveys_count' => $mitraTeladan->surveys_count ?? '-',
                        'mitra_id'      => $mitraTeladan->mitra_id ?? null,
                        'mitra_teladan_id' => $mitraTeladan->id ?? null,
                        'user_has_nilai2' => $mitraTeladan ? $nilai2Service->userHasNilai2($mitraTeladan->id, $userId) : false,
                        'nilai2_average' => $nilai2Average,
                        'ranking' => $ranking,
                    ];
                    $acceptedMitraIdByTeam[$team->id] = $mitraTeladan->mitra_id;
                } else {
                    $accepted[] = [
                        'team_name'     => $team->name,
                        'mitra_name'    => 'Belum ada Mitra Teladan',
                        'avg_rating'    => '-',
                        'surveys_count' => '-',
                        'mitra_id'      => null,
                        'mitra_teladan_id' => null,
                        'user_has_nilai2' => false,
                        'nilai2_average' => null,
                        'ranking' => null,
                    ];
                    $acceptedMitraIdByTeam[$team->id] = null;
                }
                continue;
            }
            foreach ($topMitras as $mitra) {
                $grouped[] = [
                    'mitra_id' => $mitra->mitra_id ?? $mitra->id,
                    'mitra_name' => $mitra->name ?? ($mitra->mitra->name ?? 'Unknown'),
                    'avg_rating' => $mitra->avg_rating,
                    'surveys_count' => $mitra->surveys_count ?? '-',
                    'team_id' => $team->id,
                    'team_name' => $team->name,
                ];
            }
            $mitraTeladan = $mitraTeladans->first();
            if ($mitraTeladan) {
                $mitra = \App\Models\Mitra::find($mitraTeladan->mitra_id);
                $nilai2Average = $nilai2Service->getAverageRating($mitraTeladan->id);
                $ranking = isset($rankingMap[$mitraTeladan->id]) ? $rankingMap[$mitraTeladan->id] : null;
                $accepted[] = [
                    'team_name'     => $team->name,
                    'mitra_name'    => $mitra?->name ?? '-',
                    'avg_rating'    => $mitraTeladan->avg_rating_1 ?? '-',
                    'surveys_count' => $mitraTeladan->surveys_count ?? '-',
                    'mitra_id'      => $mitraTeladan->mitra_id ?? null,
                    'mitra_teladan_id' => $mitraTeladan->id ?? null,
                    'user_has_nilai2' => $mitraTeladan ? $nilai2Service->userHasNilai2($mitraTeladan->id, $userId) : false,
                    'nilai2_average' => $nilai2Average,
                    'ranking' => $ranking,
                ];
                $acceptedMitraIdByTeam[$team->id] = $mitraTeladan->mitra_id;
            } else {
                $accepted[] = [
                    'team_name'     => $team->name,
                    'mitra_name'    => 'Belum ada Mitra Teladan',
                    'avg_rating'    => '-',
                    'surveys_count' => '-',
                    'mitra_id'      => null,
                    'mitra_teladan_id' => null,
                    'user_has_nilai2' => false,
                    'nilai2_average' => null,
                    'ranking' => null,
                ];
                $acceptedMitraIdByTeam[$team->id] = null;
            }
        }
        $this->groupedByTeam = $grouped;
        $this->acceptedMitraTeladan = $accepted;
        $this->acceptedMitraIdByTeam = $acceptedMitraIdByTeam;

        // Cross-team ranking: dense rank all accepted mitra teladan by nilai2_average
        $acceptedWithRanking = collect($accepted)
            ->filter(fn($item) => !is_null($item['mitra_id']))
            ->sortByDesc(fn($item) => $item['nilai2_average'])
            ->values();
        $currentRank = 1;
        $prevAverage = null;
        foreach ($acceptedWithRanking as $idx => $item) {
            if ($prevAverage !== null && $item['nilai2_average'] !== $prevAverage) {
                $currentRank = $idx + 1;
            }
            foreach ($accepted as &$acc) {
                if ($acc['mitra_teladan_id'] === $item['mitra_teladan_id']) {
                    $acc['ranking'] = $currentRank;
                }
            }
            $prevAverage = $item['nilai2_average'];
        }
        unset($acc);
        $this->acceptedMitraTeladan = $accepted;
    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make('selectPeriod')
                    ->columns(2)
                    ->schema([
                        Select::make('selectedYear')
                            ->label('Tahun')
                            ->options(
                                collect(range(now()->year, 2020))->mapWithKeys(fn($y) => [$y => $y])->toArray()
                            )
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
                    ])
            ]);
    }

    public function filterTopMitras()
    {
        $this->loadTopMitras();
    }

    public function filterAction(): Action
    {
        return Action::make('filter')
            ->label('Filter')
            ->action(fn () => $this->loadTopMitras())
            ->color('primary')
            ->icon('heroicon-m-magnifying-glass');
    }

    public function acceptMitra($mitraId)
    {
        // Find the mitra data in groupedByTeam
        $mitra = collect($this->groupedByTeam)->firstWhere('mitra_id', $mitraId);

        if (!$mitra) {
            Notification::make()
                ->title('Mitra not found')
                ->danger()
                ->send();
            return;
        }

        // Check if already exists for this team/year/quarter
        $exists = MitraTeladan::where('mitra_id', $mitraId)
            ->where('team_id', $mitra['team_id'])
            ->where('year', $this->selectedYear)
            ->where('quarter', $this->selectedQuarter)
            ->exists();

        if ($exists) {
            Notification::make()
                ->title('Already accepted')
                ->warning()
                ->body('This mitra has already been accepted for this team, year, and quarter.')
                ->send();
            return;
        }

        // Create the record
        MitraTeladan::create([
            'mitra_id'      => $mitraId,
            'team_id'       => $mitra['team_id'],
            'year'          => $this->selectedYear,
            'quarter'       => $this->selectedQuarter,
            'avg_rating_1'  => $mitra['avg_rating'],
            'surveys_count' => $mitra['surveys_count'],
        ]);

        Notification::make()
            ->title('Success')
            ->success()
            ->body('Mitra Teladan has been accepted!')
            ->send();

        // Optionally, refresh the data
        $this->loadTopMitras();
    }

}
