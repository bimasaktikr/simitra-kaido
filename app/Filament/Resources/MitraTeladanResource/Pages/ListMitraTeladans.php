<?php

namespace App\Filament\Resources\MitraTeladanResource\Pages;

use App\Filament\Resources\MitraTeladanResource;
use App\Services\MitraTeladanReportService;
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
        
        // Get data
        $topMitra = $service->getTopMitra($year, $quarter);
        
        if ($topMitra->isEmpty()) {
            Notification::make()
                ->warning()
                ->title('Data Tidak Ditemukan')
                ->body("Tidak ada data Mitra Teladan untuk Q{$quarter} {$year}")
                ->send();
            
            return null;
        }
        
        // Get details for each mitra
        $mitraDetails = [];
        foreach ($topMitra as $mt) {
            $mitraDetails[$mt->id] = $service->getMitraDetail($mt->id);
        }
        
        // Generate PDF
        $pdf = Pdf::loadView('exports.mitra-teladan.report', [
            'topMitra' => $topMitra,
            'mitraDetails' => $mitraDetails,
            'metadata' => $service->getReportMetadata($year, $quarter),
            'signatory' => $service->getSignatory(),
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
            ->body("Laporan Mitra Teladan Q{$quarter} {$year}")
            ->send();
        
        // Return download response
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }
}