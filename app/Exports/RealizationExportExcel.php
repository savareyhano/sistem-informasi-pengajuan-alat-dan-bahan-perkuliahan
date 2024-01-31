<?php

namespace App\Exports;

use App\Http\Repository\RealisasiRepository;
use App\Submission;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class RealizationExportExcel implements FromView, WithEvents
{
    private $submission;
    private $realizations;

    /**
     * RealizationExport constructor.
     * @param $submission
     */
    public function __construct(Submission $submission)
    {
        $realisasiRepository = new RealisasiRepository();
        $this->realizations = $realisasiRepository->getBySubmission($submission);
        $this->submission = $submission->load('programStudies');
    }

    /**
     * @inheritDoc
     */
    public function view(): View
    {
        $realizations = $this->realizations;
        return view('exports.realisasi', compact('realizations'));
    }

    /**
     * @inheritDoc
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $lastColumn = Coordinate::stringFromColumnIndex($this->realizations[0]->count() + 1);
                $beforeLastColumn = Coordinate::stringFromColumnIndex($this->realizations[0]->count());

                $lastRow = $this->realizations->count() + 5 + 2 + 1;

                $styleTextCenter = [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER
                    ],
                    'font' => [
                        'bold' => true
                    ]
                ];

                $styleTextLeft = [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT
                    ]
                ];

                $styleTextRight = [
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_RIGHT
                    ],
                    'font' => [
                        'bold' => true
                    ]
                ];

                $event->sheet->insertNewRowBefore(1, 5);

                $event->sheet->mergeCells(sprintf('A1:%s1', $lastColumn));
                $event->sheet->mergeCells(sprintf('A2:%s2', $lastColumn));
                $event->sheet->mergeCells(sprintf('A%d:%s%d', $lastRow, $beforeLastColumn, $lastRow));

                $event->sheet->setCellValue('A1', 'Laporan Realisasi Prodi ' . ucwords($this->submission->programStudies->implode('nama_prodi', ', ')));
                $event->sheet->setCellValue('A2', 'Tahun Akademik ' . $this->submission->tahun_akademik . ' Semester ' . ucwords($this->submission->semester));
                $event->sheet->setCellValue('A3', 'Siswa');
                $event->sheet->setCellValue('B3', $this->submission->siswa);
                $event->sheet->setCellValue('A4', 'Pagu');
                $event->sheet->setCellValue('B4', $this->submission->pagu);
                $event->sheet->setCellValue(sprintf('A%d', $lastRow), 'Total Biaya');

                $percent = round((($this->realizations->sum('harga_total') / $this->submission->pagu) * 100), 2) . '%';
                $event->sheet->setCellValue(sprintf('%s%d', $lastColumn, $lastRow), $this->realizations->sum('harga_total') . ' ( ' . $percent . ' )');

                $event->sheet->getStyle('A1:A2')->applyFromArray($styleTextCenter);
                $event->sheet->getStyle('B3:B4')->applyFromArray($styleTextLeft);
                $event->sheet->getStyle(sprintf('A6:%s7', $lastColumn))->getFont()->setBold(true);
                $event->sheet->getStyle(sprintf('A%d', $lastRow))->applyFromArray($styleTextRight);
            }
        ];
    }
}
