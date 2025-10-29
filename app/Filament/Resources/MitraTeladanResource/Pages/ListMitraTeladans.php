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
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class ListMitraTeladans extends ListRecords
{
    protected static string $resource = MitraTeladanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            // Export PDF Laporan
            Actions\Action::make("exportPdf")
                ->label("Export Laporan PDF")
                ->icon("heroicon-o-document-arrow-down")
                ->color("success")
                ->form([
                    Select::make("year")
                        ->label("Tahun")
                        ->options(function () {
                            $years = range(date("Y"), date("Y") - 5);
                            return array_combine($years, $years);
                        })
                        ->default(date("Y"))
                        ->required(),

                    Select::make("quarter")
                        ->label("Kuartal")
                        ->options([
                            1 => "Kuartal 1 (Jan-Mar)",
                            2 => "Kuartal 2 (Apr-Jun)",
                            3 => "Kuartal 3 (Jul-Sep)",
                            4 => "Kuartal 4 (Okt-Des)",
                        ])
                        ->default(ceil(date("n") / 3))
                        ->required(),
                ])
                ->action(function (array $data) {
                    return $this->generatePdfReport(
                        $data["year"],
                        $data["quarter"],
                    );
                }),

            // Export Sertifikat PDF
            Actions\Action::make("exportCertificates")
                ->label("Export Sertifikat PDF")
                ->icon("heroicon-o-academic-cap")
                ->color("warning")
                ->form([
                    Select::make("year")
                        ->label("Tahun")
                        ->options(function () {
                            $years = range(date("Y"), date("Y") - 5);
                            return array_combine($years, $years);
                        })
                        ->default(date("Y"))
                        ->required(),

                    Select::make("quarter")
                        ->label("Kuartal")
                        ->options([
                            1 => "Kuartal 1 (Jan-Mar)",
                            2 => "Kuartal 2 (Apr-Jun)",
                            3 => "Kuartal 3 (Jul-Sep)",
                            4 => "Kuartal 4 (Okt-Des)",
                        ])
                        ->default(ceil(date("n") / 3))
                        ->required(),
                ])
                ->action(function (array $data) {
                    return $this->generateCertificatesZip(
                        $data["year"],
                        $data["quarter"],
                    );
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
                ->title("Data Tidak Ditemukan")
                ->body("Tidak ada data Mitra Teladan untuk Q{$quarter} {$year}")
                ->send();

            return null;
        }

        // Check if all Mitra Teladan are finalized
        $allFinalized =
            \App\Models\MitraTeladan::where("year", $year)
                ->where("quarter", $quarter)
                ->where(function ($q) {
                    $q->whereNull("status_phase_2")->orWhere(
                        "status_phase_2",
                        "!=",
                        1,
                    );
                })
                ->count() === 0;

        if (!$allFinalized) {
            Notification::make()
                ->warning()
                ->title("Data Belum Final")
                ->body(
                    "Masih ada Mitra Teladan yang belum difinalisasi untuk Q{$quarter} {$year}",
                )
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
        $reportData = $nilai2Report["data"];
        $aspekDescriptions = $nilai2Report["aspekDescriptions"];

        // Generate PDF
        $pdf = Pdf::loadView("exports.mitra-teladan.report", [
            "topMitra" => $topMitra,
            "mitraDetails" => $mitraDetails,
            "metadata" => $service->getReportMetadata($year, $quarter),
            "signatory" => $service->getSignatory(),
            "reportData" => $reportData,
            "aspekDescriptions" => $aspekDescriptions,
        ]);

        $pdf->setPaper("A4", "portrait");

        $filename = sprintf(
            "Laporan_Mitra_Teladan_Q%d_%d_%s.pdf",
            $quarter,
            $year,
            now()->format("Ymd_His"),
        );

        Notification::make()
            ->success()
            ->title("PDF Berhasil Dibuat")
            ->body(
                "Laporan Mitra Teladan Q{$quarter} {$year} dengan lampiran lengkap",
            )
            ->send();

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename);
    }

    protected function generateCertificatesZip(int $year, int $quarter)
    {
        $service = new MitraTeladanReportService();

        // Get top 5 mitra data
        $topMitra = $service->getTopMitra($year, $quarter);

        if ($topMitra->isEmpty()) {
            Notification::make()
                ->warning()
                ->title("Data Tidak Ditemukan")
                ->body("Tidak ada data Mitra Teladan untuk Q{$quarter} {$year}")
                ->send();

            return null;
        }

        // Check if all finalized
        $allFinalized =
            \App\Models\MitraTeladan::where("year", $year)
                ->where("quarter", $quarter)
                ->where(function ($q) {
                    $q->whereNull("status_phase_2")->orWhere(
                        "status_phase_2",
                        "!=",
                        1,
                    );
                })
                ->count() === 0;

        if (!$allFinalized) {
            Notification::make()
                ->warning()
                ->title("Data Belum Final")
                ->body(
                    "Masih ada Mitra Teladan yang belum difinalisasi untuk Q{$quarter} {$year}",
                )
                ->send();

            return null;
        }

        // Create temporary directory for PDFs
        $tempDir = storage_path("app/temp/certificates_" . time());
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $pdfFiles = [];

        try {
            // Generate individual PDF for each certificate
            foreach ($topMitra as $index => $mt) {
                $certificateData = $service->getCertificateData(
                    $mt,
                    $index + 1,
                    $year,
                    $quarter,
                );

                // Generate PDF
                $pdf = Pdf::loadView(
                    "exports.mitra-teladan.certificate-single",
                    [
                        "certificate" => $certificateData,
                    ],
                );

                $pdf->setPaper("a4", "landscape");
                $pdf->setOption("dpi", 96);
                $pdf->setOption("enable-local-file-access", true);

                // Sanitize filename
                $sanitizedName = $this->sanitizeFilename(
                    $certificateData["mitra_name"],
                );

                $filename = sprintf(
                    "%d_Sertifikat_%s_Q%d_%d.pdf",
                    $index + 1,
                    $sanitizedName,
                    $quarter,
                    $year,
                );

                $filepath = $tempDir . "/" . $filename;
                $pdf->save($filepath);

                $pdfFiles[] = [
                    "path" => $filepath,
                    "name" => $filename,
                ];
            }

            // Create ZIP file
            $zipFilename = sprintf(
                "Sertifikat_Mitra_Teladan_Q%d_%d_%s.zip",
                $quarter,
                $year,
                now()->format("Ymd_His"),
            );

            $zipPath = $tempDir . "/" . $zipFilename;

            $zip = new ZipArchive();

            if (
                $zip->open(
                    $zipPath,
                    ZipArchive::CREATE | ZipArchive::OVERWRITE,
                ) === true
            ) {
                foreach ($pdfFiles as $file) {
                    $zip->addFile($file["path"], $file["name"]);
                }
                $zip->close();

                Notification::make()
                    ->success()
                    ->title("Sertifikat Berhasil Dibuat")
                    ->body(
                        count($pdfFiles) .
                            " sertifikat Mitra Teladan Q{$quarter} {$year} dalam format ZIP",
                    )
                    ->send();

                // Return ZIP file as download
                return response()
                    ->download($zipPath, $zipFilename)
                    ->deleteFileAfterSend(true);
            } else {
                throw new \Exception("Tidak dapat membuat file ZIP");
            }
        } catch (\Exception $e) {
            Notification::make()
                ->danger()
                ->title("Error")
                ->body("Gagal membuat sertifikat: " . $e->getMessage())
                ->send();

            return null;
        } finally {
            // Cleanup: Delete temporary PDF files
            foreach ($pdfFiles as $file) {
                if (file_exists($file["path"])) {
                    @unlink($file["path"]);
                }
            }

            // Delete temporary directory
            if (file_exists($tempDir)) {
                @rmdir($tempDir);
            }
        }
    }

    /**
     * Sanitize filename to remove special characters
     */
    private function sanitizeFilename(string $filename): string
    {
        // Remove special characters and replace spaces with underscore
        $filename = preg_replace("/[^A-Za-z0-9\-_]/", "_", $filename);
        // Remove multiple underscores
        $filename = preg_replace("/_+/", "_", $filename);
        // Trim underscores from start and end
        $filename = trim($filename, "_");
        // Limit length
        return substr($filename, 0, 50);
    }
}
