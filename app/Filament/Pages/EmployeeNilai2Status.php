<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use App\Models\Employee;
use App\Models\Team;
use App\Models\MitraTeladan;
use App\Models\Nilai2;
use Filament\Tables\Filters\SelectFilter;

class EmployeeNilai2Status extends Page implements HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static string $view = 'filament.pages.employee-nilai2-status';
    protected static ?string $title = 'Monitoring Penilaian';
    protected static ?string $slug = 'monitoring-penilaian';
    protected static ?string $navigationLabel = 'Monitoring Penilaian';
    public static bool $shouldRegisterNavigation = true;
    public static function getNavigationGroup(): ?string { return 'Penilaian'; }

    public function table(Table $table): Table
    {
        $teams = Team::where('has_survey', true)->take(6)->get();

        return $table
            ->query(Employee::query()->with('user'))
            ->columns([
                TextColumn::make('name')->label('Nama')->searchable(),
                TextColumn::make('nip')->label('NIP')->searchable(),
                ...$teams->map(function ($team) {
                    return TextColumn::make("team_{$team->id}_status")
                        ->label($team->name)
                        ->badge()
                        ->color(fn ($state) => match ($state) {
                            'Sudah' => 'success',
                            'Belum' => 'danger',
                            '-' => 'gray',
                            default => 'gray',
                        })
                        ->getStateUsing(function ($record) use ($team) {
                            $year = $this->tableFilters['selectedYear'] ?? now()->year;
                            $quarter = $this->tableFilters['selectedQuarter'] ?? ceil(now()->month / 3);
                            $mitraTeladan = MitraTeladan::where('team_id', $team->id)
                                ->where('year', $year)
                                ->where('quarter', $quarter)
                                ->first();
                            if ($mitraTeladan) {
                                $hasNilai2 = Nilai2::where('mitra_teladan_id', $mitraTeladan->id)
                                    ->where('user_id', $record->user_id)
                                    ->exists();
                                return $hasNilai2 ? 'Sudah' : 'Belum';
                            }
                            return '-';
                        });
                })->toArray(),
            ])
            ->filters([
                SelectFilter::make('selectedYear')
                    ->label('Tahun')
                    ->options(collect(range(now()->year, 2020))->mapWithKeys(fn($y) => [$y => $y])->toArray())
                    ->default(now()->year)
                    ->query(fn ($query) => $query),
                SelectFilter::make('selectedQuarter')
                    ->label('Kuartal')
                    ->options([
                        1 => 'Q1',
                        2 => 'Q2',
                        3 => 'Q3',
                        4 => 'Q4',
                    ])
                    ->default(ceil(now()->month / 3))
                    ->query(fn ($query) => $query),
            ]);
    }
}
