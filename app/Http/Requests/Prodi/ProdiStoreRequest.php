<?php

namespace App\Http\Requests\Prodi;

use Illuminate\Foundation\Http\FormRequest;

class ProdiStoreRequest extends FormRequest
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
            'kode_prodi' => ['required', 'present', 'max:5', 'unique:program_studies,kode_prodi'],
            'nama_prodi' => ['required', 'present', 'max:50', 'unique:program_studies,nama_prodi'],
            'pagu' => ['required', 'present', 'integer'],
        ];
    }
}
