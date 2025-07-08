<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($this->route('category'))
            ],
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|regex:/^#[a-fA-F0-9]{6}$/',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Название категории обязательно',
            'name.unique' => 'Категория с таким названием уже существует',
            'color.regex' => 'Цвет должен быть в формате HEX (#000000)',
        ];
    }
}
