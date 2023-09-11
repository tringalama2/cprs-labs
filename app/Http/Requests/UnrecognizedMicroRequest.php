<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UnrecognizedMicroRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'label' => ['required', 'string', 'max:255'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
