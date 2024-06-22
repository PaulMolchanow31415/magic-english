<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePosterRequest extends FormRequest {
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'photo'               => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,gif,webp',
                'max:20000',
            ],
            'photo_external_path' => 'active_url|nullable',
        ];
    }
}
