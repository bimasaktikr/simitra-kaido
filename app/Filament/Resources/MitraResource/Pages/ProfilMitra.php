<?php

namespace App\Filament\Resources\MitraResource\Pages;

use App\Filament\Resources\MitraResource;
use App\Jobs\ExportAllMitrasJob;
use App\Models\Mitra;
use App\Models\Transaction;
use App\Services\ProfilMitraService;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Response;

class ProfilMitra extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    protected static string $resource = MitraResource::class;

    protected static string $view = 'filament.resources.mitra-resource.pages.profil-mitra';

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    
    protected static ?string $navigationLabel = 'Profil Mitra';
    
    protected static ?string $title = 'Profil Mitra';

    public ?int $selectedMitraId = null;
    public ?Mitra $selectedMitra = null;
    public array $summary = [];

    protected ProfilMitraService $service;

    public function boot(ProfilMitraService $service): void
    {
        $this->service = $service;
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('selectedMitraId')
                    ->label('Pilih Mitra')
                    ->placeholder('Cari nama mitra...')
                    ->searchable()
                    ->options(fn() => $this->service->getAllMitrasForDropdown())
                    ->getSearchResultsUsing(fn(string $search) => $this->service->searchMitras($search))
                    ->getOptionLabelUsing(fn($value): ?string => Mitra::find($value)?->name)
                    ->reactive()
                    ->afterStateUpdated(function ($state) {
                        if ($state) {
                            $this->selectedMitra = $this->service->getMitraProfile($state);
                            $this->summary = $this->service->getMitraSummary($state);
                        } else {
                            $this->selectedMitra = null;
                            $this->summary = [];
                        }
                    })
                    ->helperText('Ketik minimal 2 karakter untuk mencari'),
            ])
            ->statePath('data');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                if (!$this->selectedMitraId) {
                    return Transaction::query()->whereRaw('1 = 0');
                }

                return Transaction::query()
                    ->where('mitra_id', $this->selectedMitraId)
                    ->with(['survey.masterSurvey', 'mitra', 'nilai']);
            })
            ->columns([
                TextColumn::make('survey.masterSurvey.name')
                    ->label('Nama Kegiatan')
                    ->default('-')
                    ->wrap()
                    ->searchable(),

                TextColumn::make('survey.masterSurvey.code')
                    ->label('Kode Survey')
                    ->default('-')
                    ->searchable(),

                TextColumn::make('survey.triwulan')
                    ->label('Triwulan')
                    ->formatStateUsing(fn($state) => $state ? 'Q' . $state : '-')
                    ->sortable(),

                TextColumn::make('survey.year')
                    ->label('Tahun')
                    ->sortable(),

                TextColumn::make('target')
                    ->label('Target')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('rate')
                    ->label('Rate')
                    ->money('IDR', true)
                    ->sortable(),

                TextColumn::make('total_bayar')
                    ->label('Total Bayar')
                    ->getStateUsing(fn($record) => $record->target * $record->rate)
                    ->money('IDR', true)
                    ->sortable(),

                TextColumn::make('nilai.rerata')
                    ->label('Nilai (Rerata)')
                    ->default('-')
                    ->badge()
                    ->color(fn($state) => $state ? 'success' : 'gray')
                    ->formatStateUsing(fn($state) => $state ? number_format($state, 2) : '-'),

                TextColumn::make('nilai.aspek1')
                    ->label('Aspek 1')
                    ->default('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('nilai.aspek2')
                    ->label('Aspek 2')
                    ->default('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('nilai.aspek3')
                    ->label('Aspek 3')
                    ->default('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('status_penilaian')
                    ->label('Status Penilaian')
                    ->badge()
                    ->getStateUsing(fn($record) => $record->nilai ? 'Sudah Dinilai' : 'Belum Dinilai')
                    ->color(fn($record) => $record->nilai ? 'success' : 'warning'),
            ])
            ->defaultSort('survey.year', 'desc')
            ->heading('Detail Kegiatan Mitra')
            ->emptyStateHeading('Belum ada kegiatan')
            ->emptyStateDescription('Mitra ini belum pernah mengikuti survey/kegiatan apapun.');
    }

    public function exportSingle()
    {
        if (!$this->selectedMitraId) {
            Notification::make()
                ->title('Pilih Mitra Terlebih Dahulu')
                ->warning()
                ->send();
            return;
        }

        try {
            $mitra = Mitra::find($this->selectedMitraId);
            $filename = 'mitra_' . $mitra->sobat_id . '_' . now()->format('YmdHis') . '.csv';
            
            $csvContent = $this->service->generateSingleMitraCSV($this->selectedMitraId);

            return Response::streamDownload(
                fn() => print($csvContent),
                $filename,
                [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                ]
            );
        } catch (\Exception $e) {
            Notification::make()
                ->title('Export Gagal')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function exportAll()
    {
        ExportAllMitrasJob::dispatch(auth()->id());

        Notification::make()
            ->title('Export Dimulai')
            ->body('Export semua mitra sedang diproses di background. File akan tersimpan di storage/app/public/exports/')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\Action::make('exportSingle')
                ->label('Export Mitra Terpilih')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->disabled(fn() => !$this->selectedMitraId)
                ->action('exportSingle'),
            
            \Filament\Actions\Action::make('exportAll')
                ->label('Export Semua Mitra')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('primary')
                ->action('exportAll')
                ->requiresConfirmation()
                ->modalHeading('Export Semua Mitra')
                ->modalDescription('Proses ini akan mengexport data dari ~500 mitra dan diproses di background. File akan tersimpan di folder exports. Apakah Anda yakin?')
                ->modalSubmitActionLabel('Ya, Export'),
        ];
    }

    public function getSummary(): array
    {
        return $this->summary;
    }
}