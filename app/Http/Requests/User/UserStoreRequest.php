<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserStoreRequest extends FormRequest
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
            'name' => ['required', 'present', 'max:50', 'regex:/^[a-zA-Z0-9\s]+$/'],
            'email' => ['required', 'present', 'max:50', 'email', 'unique:users,email'],
            'username' => ['required', 'present', 'max:24', 'alpha_num'],
            'password' => ['required', 'present', 'alpha_num', 'max:32', 'min:8'],
            'role' => ['required', 'present', Rule::in(['prodi', 'wakil_direktur', 'tata_usaha'])],
        ];
    }
}
