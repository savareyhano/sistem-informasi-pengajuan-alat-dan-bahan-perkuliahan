<?php

namespace App\Http\Requests\Prodi;

use App\ProgramStudy;
use Illuminate\Foundation\Http\FormRequest;

class ProdiUpdateRequest extends FormRequest
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
        if ($this->id) {
            $programStudy = ProgramStudy::findOrFail($this->id);
        }
        return [
            'id' => ['required', 'present', 'integer'],
            'kode_prodi' => ['required', 'present', 'max:5', 'unique:program_studies,kode_prodi,' . optional($programStudy)->kode_prodi . ',kode_prodi'],
            'nama_prodi' => ['required', 'present', 'max:50', 'unique:program_studies,nama_prodi,' . optional($programStudy)->nama_prodi . ',nama_prodi'],
            'pagu' => ['required', 'present', 'integer'],
        ];
    }
}
