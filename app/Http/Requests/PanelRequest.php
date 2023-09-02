<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PanelRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'label' => ['required', 'string', 'max:255'],
            'order_column' => ['nullable', 'integer'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
