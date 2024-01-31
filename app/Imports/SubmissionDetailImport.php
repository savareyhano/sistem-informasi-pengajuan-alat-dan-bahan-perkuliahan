<?php

namespace App\Imports;

use App\SubmissionDetail;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SubmissionDetailImport implements OnEachRow, WithHeadingRow
{
    protected $id;

    function __construct($id) {
            $this->id = $id;
    }

    public function onRow(Row $row)
    {
        $row = $row->toArray();

        $submissionDetails = SubmissionDetail::firstOrCreate(
            ['submission_id' => $this->id, 'nama_barang' => $row['nama_barang']],
            [
                'submission_id' => $this->id,
                'nama_barang' => $row['nama_barang'],
                'image_path' => $row['image_path'] ?? '-',
                'jumlah' => $row['jumlah'],
                'harga_satuan' => $row['harga_satuan'],
                'harga_total' => $row['harga_total'],
                'keterangan' => $row['keterangan'],
            ]
        );

        if (! $submissionDetails->wasRecentlyCreated) {
            $submissionDetails->update([
                'image_path' => $row['image_path'] ?? '-',
                'jumlah' => $row['jumlah'],
                'harga_satuan' => $row['harga_satuan'],
                'harga_total' => $row['harga_total'],
                'keterangan' => $row['keterangan'],
            ]);
        }
    }
}