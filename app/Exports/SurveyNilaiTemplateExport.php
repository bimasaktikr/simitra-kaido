<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;   // ✅
use Maatwebsite\Excel\Concerns\WithEvents;            // ✅
use Maatwebsite\Excel\Events\AfterSheet;

class SurveyNilaiTemplateExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithCustomStartCell,   // ✅
    WithEvents             // ✅
{
    public function __construct(public int $surveyId) {}

    public function query()
    {
        return Transaction::query()
            ->with('mitra')
            ->where('survey_id', $this->surveyId)
            ->orderBy('id');
    }

    // Real headers (DB keys)
    public function headings(): array
    {
        return ['transaction_id','mitra_name','aspek1','aspek2','aspek3'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->mitra?->name ?? '-',
            '', '', '',
        ];
    }

    // Put the headings at row 3
    public function startCell(): string
    {
        return 'A3';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Instruction rows (1–2)
                $sheet->setCellValue('A1', 'Keterangan Kolom Penilaian');
                $sheet->mergeCells('A1:E1');

                $sheet->setCellValue('A2', 'aspek1 = Kualitas Data | aspek2 = Ketepatan Waktu | aspek3 = Pemahaman Kerja. Jangan ubah kolom A–B.');
                $sheet->mergeCells('A2:E2');

                $sheet->getStyle('A1')->getFont()->setBold(true);
                $sheet->getStyle('A1:A2')->getAlignment()->setWrapText(true);

                // Make the header row (row 3) bold & freeze below it
                $sheet->getStyle('A3:E3')->getFont()->setBold(true);
                $sheet->freezePane('A4');
            },
        ];
    }
}
