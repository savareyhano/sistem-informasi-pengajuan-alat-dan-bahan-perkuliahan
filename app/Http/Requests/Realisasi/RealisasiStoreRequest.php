<?php

namespace App\Http\Requests\Realisasi;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RealisasiStoreRequest extends FormRequest
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
            'type' => ['required', 'present', Rule::in(['new', 'existing'])],
            'barang' => ['required_if:type,existing', 'nullable', 'integer'],
            'nama_barang' => ['required_if:type,new', 'nullable', 'present', 'string'],
            'gambar' => ['required_if:type,new', 'image', 'mimes:jpeg,png'],
            'jumlah_barang' => ['required', 'present', 'integer', 'min:1'],
            'harga_satuan' => ['required', 'present', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'present', 'string']
        ];
    }
}
