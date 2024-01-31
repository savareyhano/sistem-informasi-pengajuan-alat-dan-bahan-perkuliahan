<?php

namespace App\Http\Requests\Realisasi;

use Illuminate\Foundation\Http\FormRequest;

class RealisasiUpdateRequest extends FormRequest
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
            'jumlah_barang' => ['required', 'present', 'integer', 'min:1'],
            'harga_satuan' => ['required', 'present', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'present', 'string']
        ];
    }
}
