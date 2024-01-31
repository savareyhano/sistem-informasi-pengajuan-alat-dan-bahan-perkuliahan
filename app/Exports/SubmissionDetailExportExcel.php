<?php

namespace App\Exports;

use App\Http\Repository\PengajuanDetailRepository;
use App\Submission;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SubmissionDetailExportExcel implements FromView, WithEvents
{
    private $submission;
    private $submissionDetail;

    /**
     * RealizationExport constructor.
     * @param $submission
     */
    public function __construct(Submission $submission)
    {
        $pengajuanDetailRepository = new PengajuanDetailRepository();
        $this->submissionDetail = $pengajuanDetailRepository->getBySubmission($submission);
        $this->submission = $submission->load('programStudies');
    }

    /**
     * @inheritDoc
     */
    public function view(): View
    {
        $submissionDetail = $this->submissionDetail;
        return view('exports.pengajuan_detail', compact('submissionDetail'));
    }

    /**
     * @inheritDoc
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $lastColumn = Coordinate::stringFromColumnIndex($this->submissionDetail[0]->count() + 1);
                $beforeLastColumn = Coordinate::stringFromColumnIndex($this->submissionDetail[0]->count());

                $lastRow = $this->submissionDetail->count() + 4 + 2 + 1;

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

                $event->sheet->setCellValue('A1', 'Laporan Detail Pengajuan Prodi ' . ucwords($this->submission->programStudies->implode('nama_prodi', ', ')));
                $event->sheet->setCellValue('A2', 'Tahun Akademik ' . $this->submission->tahun_akademik . ' Semester ' . ucwords($this->submission->semester));
                $event->sheet->setCellValue('A3', 'Siswa');
                $event->sheet->setCellValue('B3', $this->submission->siswa);
                $event->sheet->setCellValue('A4', 'Pagu');
                $event->sheet->setCellValue('B4', $this->submission->pagu);
                $event->sheet->setCellValue(sprintf('A%d', $lastRow), 'Total Biaya');

                $event->sheet->setCellValue(sprintf('%s%d', $lastColumn, $lastRow), $this->submissionDetail->sum('harga_total'));

                $event->sheet->getStyle('A1:A2')->applyFromArray($styleTextCenter);
                $event->sheet->getStyle('B3:B4')->applyFromArray($styleTextLeft);
                $event->sheet->getStyle(sprintf('A6:%s6', $lastColumn))->getFont()->setBold(true);
                $event->sheet->getStyle(sprintf('A%d', $lastRow))->applyFromArray($styleTextRight);
            }
        ];
    }
}
