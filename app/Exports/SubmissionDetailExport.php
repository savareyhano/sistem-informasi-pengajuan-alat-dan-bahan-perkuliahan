<?php

namespace App\Exports;

use App\SubmissionDetail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubmissionDetailExport implements FromCollection, WithHeadings
{
    protected $id;

    function __construct($id) {
            $this->id = $id;
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return SubmissionDetail::select('nama_barang', 'image_path', 'jumlah', 'harga_satuan', 'harga_total', 'keterangan')->where('submission_id', $this->id)->get();
        // return SubmissionDetail::all()->where('submission_id', $this->id);
    }
    public function headings(): array
    {
        return [
            'nama_barang',
            'image_path',
            'jumlah',
            'harga_satuan',
            'harga_total',
            'keterangan',
        ];
    }
}
