<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $id = $this->route('store');
        return [
            'name' => [
                'required', 'string', 'min:3', 'max:255',
                Rule::unique('stores', 'name')->ignore($id),
            ],
            'description' => [
                'nullable', 'string', 'min:3',
            ],
            // 'logo_image' => [
            //     'nullable', 'image', 'max:1048576', 'dimensions:min_width=100,min_height=100',
            // ],
            // 'cover_image' => [
            //     'nullable', 'image', 'max:1048576', 'dimensions:min_width=100,min_height=100',
            // ],
            'status' => 'required|in:active,inactive',
        ];
    }
}
