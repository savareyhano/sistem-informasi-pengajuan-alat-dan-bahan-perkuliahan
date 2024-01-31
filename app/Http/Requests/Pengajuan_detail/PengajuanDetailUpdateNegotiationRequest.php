<?php

namespace App\Http\Requests\Pengajuan_detail;

use Illuminate\Foundation\Http\FormRequest;

class PengajuanDetailUpdateNegotiationRequest extends FormRequest
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
            'status' => ['required', 'present'],
            'jumlah_barang' => ['required_if:status,2', 'nullable', 'integer'],
            'harga_satuan' => ['required_if:status,2', 'nullable', 'integer']
        ];
    }
}
