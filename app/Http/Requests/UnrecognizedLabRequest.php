<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnrecognizedLabRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'label' => ['required', 'string', 'max:255'],
            'panel_id' => ['required', 'integer', 'exists:panels,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
