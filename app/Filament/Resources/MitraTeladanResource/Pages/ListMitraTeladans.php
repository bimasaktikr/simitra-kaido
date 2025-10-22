<?php

namespace App\Filament\Resources\MitraTeladanResource\Pages;

use App\Filament\Resources\MitraTeladanResource;
use App\Services\MitraTeladanReportService;
use App\Services\Nilai2Service;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;

class ListMitraTeladans extends ListRecords
{
    protected static string $resource = MitraTeladanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            
            // Export PDF Action
            Actions\Action::make('exportPdf')
                ->label('Export Laporan PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->form([
                    Select::make('year')
                        ->label('Tahun')
                        ->options(function () {
                            $years = range(date('Y'), date('Y') - 5);
                            return array_combine($years, $years);
                        })
                        ->default(date('Y'))
                        ->required(),
                    
                    Select::make('quarter')
                        ->label('Kuartal')
                        ->options([
                            1 => 'Kuartal 1 (Jan-Mar)',
                            2 => 'Kuartal 2 (Apr-Jun)',
                            3 => 'Kuartal 3 (Jul-Sep)',
                            4 => 'Kuartal 4 (Okt-Des)',
                        ])
                        ->default(ceil(date('n') / 3))
                        ->required(),
                ])
                ->action(function (array $data) {
                    return $this->generatePdfReport($data['year'], $data['quarter']);
                }),
        ];
    }

    protected function generatePdfReport(int $year, int $quarter)
    {
        $service = new MitraTeladanReportService();
        $nilai2Service = app(Nilai2Service::class);
        
        // Get top 5 mitra data
        $topMitra = $service->getTopMitra($year, $quarter);
        
        if ($topMitra->isEmpty()) {
            Notification::make()
                ->warning()
                ->title('Data Tidak Ditemukan')
                ->body("Tidak ada data Mitra Teladan untuk Q{$quarter} {$year}")
                ->send();
            
            return null;
        }
        
        // Check if all Mitra Teladan are finalized
        $allFinalized = \App\Models\MitraTeladan::where('year', $year)
            ->where('quarter', $quarter)
            ->where(function($q) {
                $q->whereNull('status_phase_2')->orWhere('status_phase_2', '!=', 1);
            })
            ->count() === 0;
            
        if (!$allFinalized) {
            Notification::make()
                ->warning()
                ->title('Data Belum Final')
                ->body("Masih ada Mitra Teladan yang belum difinalisasi untuk Q{$quarter} {$year}")
                ->send();
            
            return null;
        }
        
        // Get details for top 5 mitra
        $mitraDetails = [];
        foreach ($topMitra as $mt) {
            $mitraDetails[$mt->id] = $service->getMitraDetail($mt->id);
        }
        
        // Get nilai2 report data (all mitra)
        $nilai2Report = $nilai2Service->getReportData($year, $quarter);
        $reportData = $nilai2Report['data'];
        $aspekDescriptions = $nilai2Report['aspekDescriptions'];
        $mitraRanking = $nilai2Report['mitraRanking'];
        
        // Generate PDF
        $pdf = Pdf::loadView('exports.mitra-teladan.report', [
            'topMitra' => $topMitra,
            'mitraDetails' => $mitraDetails,
            'metadata' => $service->getReportMetadata($year, $quarter),
            'signatory' => $service->getSignatory(),
            // Tambahan data untuk lampiran nilai2-report
            'reportData' => $reportData,
            'aspekDescriptions' => $aspekDescriptions,
            'mitraRanking' => $mitraRanking,
        ]);
        
        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');
        
        // Generate filename
        $filename = sprintf(
            'Laporan_Mitra_Teladan_Q%d_%d_%s.pdf',
            $quarter,
            $year,
            now()->format('Ymd_His')
        );
        
        // Success notification
        Notification::make()
            ->success()
            ->title('PDF Berhasil Dibuat')
            ->body("Laporan Mitra Teladan Q{$quarter} {$year} dengan lampiran lengkap")
            ->send();
        
        // Return download response
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }
}