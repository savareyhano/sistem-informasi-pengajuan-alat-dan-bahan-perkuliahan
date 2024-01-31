<?php

namespace App\Exports;

use App\Realization;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RealizationExport implements FromCollection, WithHeadings
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
        return Realization::select('nama_barang', 'image_path', 'jumlah', 'harga_satuan', 'harga_total', 'keterangan')->where('submission_id', $this->id)->get();
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
