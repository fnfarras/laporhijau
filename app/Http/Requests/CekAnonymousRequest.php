<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CekAnonymousRequest extends FormRequest
{
    /**
     * Semua orang bisa mengecek status laporan anonim mereka.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'min:3', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Kode laporan wajib diisi.',
            'code.min'      => 'Kode laporan tidak valid.',
        ];
    }
}
