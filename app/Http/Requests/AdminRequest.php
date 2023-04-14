<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $id = $this->route('admin');
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => [
                'required', 'string', 'max:255',
                Rule::unique('admins', 'username')->ignore($id),
            ],
            'phone_number' => [
                'required', 'max:10',
                Rule::unique('admins', 'phone_number')->ignore($id),
            ],
            'password' => 'sometimes|confirmed',
            'roles' => 'required', 'array',
            'email' => [
                'required', 'string', 'email',
                Rule::unique('admins', 'email')->ignore($id),
            ],
        ];
    }
}
