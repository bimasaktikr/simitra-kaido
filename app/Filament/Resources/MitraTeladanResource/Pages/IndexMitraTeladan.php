<?php

namespace App\Filament\Resources\MitraTeladanResource\Pages;

use App\Filament\Resources\MitraTeladanResource;
use Filament\Pages\Page;
use App\Services\MitraService;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Actions;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Actions\Action as FormAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

// class IndexMitraTeladan extends Page implements HasForms
class IndexMitraTeladan extends Page
{
    // use InteractsWithForms;

    protected static string $resource = MitraTeladanResource::class;

    protected static string $view = 'filament.resources.mitra-teladan-resource.pages.index-mitra-teladan';

    // public $topMitra; // Declare the property

    // public function mount(): void

    // {
    //     $mitraService = new MitraService();
    // }

    // public function form(Forms\Form $form): Forms\Form
    // {
    //     return $form->schema([
    //         Grid::make(2)->schema([
    //             Select::make('selectedYear')
    //                 ->label('Year')
    //                 ->options(array_combine(
    //                     range(now()->year - 5, now()->year),
    //                     range(now()->year - 5, now()->year)
    //                 ))
    //                 ->required(),

    //             Select::make('selectedQuarter')
    //                 ->label('Quarter')
    //                 ->options([
    //                     1 => 'Q1',
    //                     2 => 'Q2',
    //                     3 => 'Q3',
    //                     4 => 'Q4',
    //                 ])
    //                 ->required(),
    //         ]),
    //         Actions::make([
    //             FormAction::make('loadData')
    //                 ->label('Load Data')
    //                 ->action('loadData')
    //                 ->icon('heroicon-o-arrow-path')
    //                 ->color('primary')
    //         ])->alignEnd()->fullWidth(),
    //     ]);
    // }



    // public function loadData(): void
    // {
    //     $this->validate([
    //         'selectedYear' => 'required|integer',
    //         'selectedQuarter' => 'required|integer',
    //     ]);

    //     $mitraService = new MitraService();
    //     $this->topMitra = $mitraService->getTopMitra($this->selectedYear, $this->selectedQuarter, 1); // Example teamId
    // }
}
