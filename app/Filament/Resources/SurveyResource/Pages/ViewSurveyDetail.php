<?php

namespace App\Filament\Resources\SurveyResource\Pages;

use App\Filament\Resources\SurveyResource;
use App\Imports\SurveyNilaiImport;
use App\Imports\SurveyTransactionImport;
use App\Models\Survey;
use App\Models\Transaction;
use App\Services\MitraService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Contracts\View\View; // at top
use Illuminate\Support\Collection;



class ViewSurveyDetail extends Page implements Tables\Contracts\HasTable
{
    use InteractsWithTable;

    protected static string $resource = SurveyResource::class;

    public ?Survey $record = null;

    public function mount(Survey $record): void
    {
        $this->record = $record;
    }

    protected static string $view = 'filament.resources.survey-resource.pages.view-survey-detail';

    protected function getTableQuery(): Builder
    {
        // return $this->record->mitras(); // assuming a relation exists
        return Transaction::query()
                ->where('survey_id', $this->record->id)
                ->with(['mitra', 'nilai'])
                // ->whereHas('nilai') // Only include transactions with nilai
                ->orderByDesc(
                    DB::raw('(SELECT rerata FROM nilai1s WHERE nilai1s.transaction_id = transactions.id LIMIT 1)')
                );
    }



    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('mitra.name')
                ->label('Mitra Name')
                ->sortable()
                ->searchable(),

            TextColumn::make('target')
                ->label('Target'),


            TextColumn::make('rate')
                ->label('Rate')
                ->money('IDR', true),

            TextColumn::make('payment')
                ->label('Payment')
                ->money('IDR', true)
                ->alignRight()
                ->state(fn (Transaction $r) => (int) $r->target * (int) $r->rate)
                ->sortable(
                    query: fn (Builder $q, string $dir) =>
                        $q->orderByRaw('target * rate ' . ($dir === 'desc' ? 'desc' : 'asc'))
                )
                ->summarize(
                    Sum::make()
                        ->label('Σ Payment')
                        ->using(fn (Collection $records) =>
                            $records->sum(fn (Transaction $r) => (int) $r->target * (int) $r->rate)
                        )
                ),
            TextColumn::make('nilai.aspek1')
                ->label('Kualitas Data'),

            TextColumn::make('nilai.aspek2')
                ->label('Ketepatan Waktu'),

            TextColumn::make('nilai.aspek3')
                ->label('Pemahaman Pengetahuan Kerja'),

            TextColumn::make('nilai.rerata')
                ->label('Rerata'),
            ];
    }

    protected function getTableActions(): array
    {
        return [
            EditAction::make()
                ->label('Edit Nilai')
                ->form([
                    TextInput::make('nilai.aspek1')
                        ->label('Aspek 1')
                        ->numeric()
                        ->required()
                        ->minValue(1)
                        ->maxValue(5),

                    TextInput::make('nilai.aspek2')
                        ->label('Aspek 2')
                        ->numeric()
                        ->required()
                        ->minValue(1)
                        ->maxValue(5),

                    TextInput::make('nilai.aspek3')
                        ->label('Aspek 3')
                        ->numeric()
                        ->required()
                        ->minValue(1)
                        ->maxValue(5),
                ])
                ->mutateFormDataUsing(function ($data) {
                    $data['nilai']['rerata'] = number_format(
                        (
                            $data['nilai']['aspek1'] +
                            $data['nilai']['aspek2'] +
                            $data['nilai']['aspek3']
                        ) / 3, 2);

                    return $data;
                })
                ->action(function ($record, $data) {
                    $record->nilai->update([
                        'aspek1' => $data['nilai']['aspek1'],
                        'aspek2' => $data['nilai']['aspek2'],
                        'aspek3' => $data['nilai']['aspek3'],
                        'rerata' => $data['nilai']['rerata'],
                    ]);
                })
                ->visible(fn () => !$this->record->is_scored), // disable edit if already finalized
        ];
    }

    // public function getHeaderActions(): array
    // {
    //     return [
    //         Action::make('Upload Mitra Excel')
    //             ->form([
    //                 FileUpload::make('file')
    //                         ->label('Upload Excel File')
    //                         ->disk('local')
    //                         ->directory('survey-data')
    //                         ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv'])
    //                         ->preserveFilenames()
    //                         ->required(),
    //                     ])
    //                     ->action(function (array $data) {

    //                         try {
    //                             Excel::import(
    //                                 new SurveyTransactionImport($this->record->id, $this->record->rate),
    //                                 $data['file']
    //                             );

    //                             Notification::make()
    //                                 ->title('Import successful!')
    //                                 ->success()
    //                                 ->send();
    //                         } catch (\Exception $e) {
    //                             // Optionally log error
    //                             logger()->error('Excel Import Failed: ' . $e->getMessage());

    //                             Notification::make()
    //                                 ->title('Import failed!')
    //                                 ->body('An error occurred during the import: ' . $e->getMessage())
    //                                 ->danger()
    //                                 ->send();
    //                         }
    //                     })
    //                     ->modalHeading('Upload Mitra Assignment File')
    //                     ->modalDescription(
    //                         "Upload the Excel file containing the list of mitras and their target payments. The file should be in .xlsx or .csv format. Make sure to download the template first to ensure the correct format."
    //                     )
    //                     ->modalFooterActionsAlignment('between')

    //                     ->extraModalFooterActions([
    //                         Action::make('download-template')
    //                             ->label('Download Template')
    //                             ->icon('heroicon-o-arrow-down-tray')
    //                             ->url(route('mitra.template.download'))
    //                             ->openUrlInNewTab()
    //                             ->color('secondary'),
    //                     ])
    //                     ->modalSubmitActionLabel('Import'),
    //                  // ✅ Add this Finalize button

    //         Action::make('toggleFinalizeNilai')
    //             ->label(fn () => $this->record->is_scored ? 'Unfinalize Nilai' : 'Finalize Nilai')
    //             ->icon(fn () => $this->record->is_scored ? 'heroicon-o-lock-open' : 'heroicon-o-lock-closed')
    //             ->color(fn () => $this->record->is_scored ? 'gray' : 'danger')
    //             ->requiresConfirmation()
    //             // ->visible(fn () =>
    //             //     ! $this->record->is_scored || Auth::user()->hasRole('superadmin')
    //             // )
    //             ->visible(fn () => ! $this->record->is_scored || auth()->user()->can('survey.unfinalize'))
    //             ->action(function () {
    //                 $survey = $this->record;

    //                 // Restrict unfinalize unless superadmin
    //                 if ($survey->is_scored && ! Auth::user()->hasRole('superadmin')) {
    //                     Notification::make()
    //                         ->title('You do not have permission to unfinalize this survey.')
    //                         ->danger()
    //                         ->send();
    //                     return;
    //                 }

    //                 $survey->is_scored = ! $survey->is_scored;
    //                 $survey->save();

    //                 Notification::make()
    //                     ->title($survey->is_scored ? 'Survey finalized!' : 'Finalization cancelled!')
    //                     ->success()
    //                     ->send();
    //             }),
    //         Action::make('Add Mitra Assignment')
    //             ->label('Add Mitra Assignment')
    //             ->icon('heroicon-o-plus')
    //             ->color('success')
    //             ->requiresConfirmation(false) // we’re showing a table, no need for confirm
    //             ->modalHeading('Add Mitra to Assignment')
    //             ->modalWidth(MaxWidth::ExtraLarge)
    //             ->slideOver() // optional, looks nice for wide tables
    //             ->modalSubmitAction(false) // modal has no submit; actions are per-row inside the table
    //             ->extraModalWindowAttributes([
    //                 'style' => 'width:48vw;max-width:48vw;', // ≈ half screen
    //             ])
    //             ->modalContent(function (): View {
    //                 return view('filament.partials.mitra-picker-modal', [
    //                     'parentId' => $this->record->id,   // ✅ use the page’s record
    //                 ]);
    //             }),
    //         ];

    // }


    public function getHeaderActions(): array
    {
        return [
            // Group related to Mitra assignment & import
            ActionGroup::make([
                Action::make('Add Mitra Assignment')
                    ->label('Add Mitra Assignment')
                    ->icon('heroicon-o-plus')
                    ->color('success')
                    ->requiresConfirmation(false)
                    ->modalHeading('Add Mitra to Assignment')
                    ->modalWidth(MaxWidth::ExtraLarge)
                    ->slideOver()
                    ->modalSubmitAction(false)
                    ->extraModalWindowAttributes(['style' => 'width:48vw;max-width:48vw;'])
                    ->modalContent(function (): View {
                        return view('filament.partials.mitra-picker-modal', [
                            'parentId' => $this->record->id,
                        ]);
                    }),

                Action::make('Upload Mitra Excel')
                    ->label('Upload Mitra Excel')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->form([
                        FileUpload::make('file')
                            ->label('Upload Excel File')
                            ->disk('local')
                            ->directory('survey-data')
                            ->acceptedFileTypes([
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'text/csv',
                            ])
                            ->preserveFilenames()
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        try {
                            Excel::import(
                                new SurveyTransactionImport($this->record->id, $this->record->rate),
                                $data['file']
                            );
                            Notification::make()->title('Import successful!')->success()->send();
                        } catch (\Exception $e) {
                            logger()->error('Excel Import Failed: ' . $e->getMessage());
                            Notification::make()
                                ->title('Import failed!')
                                ->body('An error occurred during the import: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->modalHeading('Upload Mitra Assignment File')
                    ->modalDescription('Upload the Excel file …')
                    ->modalFooterActionsAlignment('between')
                    ->extraModalFooterActions([
                        Action::make('download-template')
                            ->label('Download Template')
                            ->icon('heroicon-o-arrow-down-tray')
                            ->url(route('mitra.template.download'))
                            ->openUrlInNewTab()
                            ->color('secondary'),
                    ])
                    ->modalSubmitActionLabel('Import'),

            ])
                ->label('Mitra')
                ->icon('heroicon-o-user-group')
                ->color('primary')
                ->button(), // render as a button with dropdown

            // Separate group for finalize toggle (or keep as a standalone action if you prefer)
            ActionGroup::make([
                Action::make('toggleFinalizeNilai')
                    ->label(fn () => $this->record->is_scored ? 'Unfinalize Nilai' : 'Finalize Nilai')
                    ->icon(fn () => $this->record->is_scored ? 'heroicon-o-lock-open' : 'heroicon-o-lock-closed')
                    ->color(fn () => $this->record->is_scored ? 'gray' : 'danger')
                    ->requiresConfirmation()
                    ->visible(fn () => ! $this->record->is_scored || auth()->user()->can('survey.unfinalize'))
                    ->action(function () {
                        $survey = $this->record;
                        if ($survey->is_scored && ! auth()->user()->hasRole('superadmin')) {
                            Notification::make()->title('You do not have permission to unfinalize this survey.')->danger()->send();
                            return;
                        }
                        $survey->is_scored = ! $survey->is_scored;
                        $survey->save();

                        Notification::make()
                            ->title($survey->is_scored ? 'Survey finalized!' : 'Finalization cancelled!')
                            ->success()
                            ->send();
                    }),
                    Action::make('Upload Penilaian Excel')
                    ->label('Upload Penilaian Excel')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->form([
                        FileUpload::make('file')
                            ->label('Upload Excel File')
                            ->disk('local')
                            ->directory('survey-data')
                            ->acceptedFileTypes([
                                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                                'text/csv',
                            ])
                            ->preserveFilenames()
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        try {
                            Excel::import(
                                new SurveyNilaiImport($this->record->id),
                                $data['file']
                            );
                            Notification::make()->title('Import successful!')->success()->send();
                        } catch (\Exception $e) {
                            logger()->error('Excel Import Failed: ' . $e->getMessage());
                            Notification::make()
                                ->title('Import failed!')
                                ->body('An error occurred during the import: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->modalHeading('Upload Penilaian Mitra')
                    ->modalDescription('Download template di bawah, isi kolom aspek1–aspek3, lalu upload kembali file tersebut.')
                    ->modalFooterActionsAlignment('between')
                    ->extraModalFooterActions([
                        Action::make('download-penilaian-template')
                            ->label('Download Template (Penilaian)')
                            ->icon('heroicon-o-arrow-down-tray')
                            ->url(fn () => route('survey.penilaian.template.download', $this->record->id)) // ✅ dynamic
                            ->openUrlInNewTab()
                            ->color('secondary'),
                    ])
                    ->modalSubmitActionLabel('Import')
            ])
                ->label('Nilai')
                ->icon('heroicon-o-lock-closed')
                ->button(),
        ];
    }

}
