<?php

namespace App\Http\Requests\Pengajuan;

use App\Rules\AcademicYear;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PengajuanStoreRequest extends FormRequest
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
            'tahun_akademik' => ['required', 'present', 'max:9', 'min:9', new AcademicYear()],
            'semester' => ['required', 'present', Rule::in(['ganjil', 'genap'])],
            'prodi' => ['required', 'present', 'array'],
            'siswa' => ['required', 'present']
        ];
    }
}
