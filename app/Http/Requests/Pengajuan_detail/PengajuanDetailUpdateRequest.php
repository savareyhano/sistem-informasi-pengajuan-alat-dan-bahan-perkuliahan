<?php

namespace App\Http\Requests\Pengajuan_detail;

use Illuminate\Foundation\Http\FormRequest;

class PengajuanDetailUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => ['required', 'present', 'integer'],
            'nama_barang' => ['required', 'present', 'string'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png'],
            'jumlah_barang' => ['required', 'present', 'integer', 'min:1'],
            'harga_satuan' => ['required', 'present', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'present', 'string']
        ];
    }
}
