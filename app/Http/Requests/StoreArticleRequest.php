<?php

namespace App\Http\Requests;

use App\Models\Article;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() &&
            auth()->user()->hasAnyRole(['admin', 'pemerintah']);
    }

    public function rules(): array
    {
        return [
            'title'    => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:' . implode(',', Article::CATEGORIES)],
            'content'  => ['required', 'string', 'min:100'],
            'publish'  => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'    => 'Judul artikel wajib diisi.',
            'category.required' => 'Kategori wajib dipilih.',
            'category.in'       => 'Kategori tidak valid.',
            'content.required'  => 'Konten artikel wajib diisi.',
            'content.min'       => 'Konten minimal 100 karakter.',
        ];
    }
}
