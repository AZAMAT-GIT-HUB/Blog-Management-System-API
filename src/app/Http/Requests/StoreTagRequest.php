<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:tags,name',
            'color' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Название тега обязательно',
            'name.unique' => 'Тег с таким названием уже существует',
            'color.regex' => 'Цвет должен быть в формате HEX (#000000)',
        ];
    }
}
