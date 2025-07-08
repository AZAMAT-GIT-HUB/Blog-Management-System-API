<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        if (is_string($this->categories)) {
            $this->merge([
                'categories' => explode(',', $this->categories),
            ]);
        }

        if (is_string($this->tags)) {
            $this->merge([
                'tags' => explode(',', $this->tags),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:posts,title',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published',
            'categories' => 'nullable|array',
            'categories.*' => 'integer|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'integer|exists:tags,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Заголовок обязателен для заполнения',
            'title.unique' => 'Пост с таким заголовком уже существует',
            'content.required' => 'Содержимое поста обязательно',
            'featured_image.image' => 'Файл должен быть изображением',
            'featured_image.max' => 'Размер изображения не должен превышать 2MB',
            'status.in' => 'Статус должен быть draft или published',
            'categories.*.exists' => 'Выбранная категория не существует',
            'tags.*.exists' => 'Выбранный тег не существует',
        ];
    }
}
