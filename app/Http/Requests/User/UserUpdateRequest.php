<?php

namespace App\Http\Requests\User;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
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
            $user = User::findOrFail($this->id);
        }

        return [
            'id' => ['required', 'present', 'integer'],
            'name' => ['required', 'present', 'max:50', 'regex:/^[a-zA-Z0-9\s]+$/'],
            'email' => ['required', 'present', 'max:50', 'email'],
            'username' => ['required', 'present', 'max:24', 'alpha_num', 'unique:users,username,' . optional($user)->username . ',username'],
            'password' => ['nullable', 'present', 'alpha_num', 'max:32', 'min:8'],
            'role' => ['required', 'present', Rule::in(['prodi', 'wakil_direktur', 'tata_usaha'])],
        ];
    }
}
