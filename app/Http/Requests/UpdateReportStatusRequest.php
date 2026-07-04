<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReportStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasAnyRole(['relawan', 'pemerintah', 'admin']) ?? false;
    }

    public function rules(): array
    {
        return [
            'action'      => ['required', 'string', 'in:verify,reject,in_progress,resolved'],
            'reason'      => ['required_if:action,reject', 'nullable', 'string', 'min:10', 'max:500'],
            'after_photo' => ['required_if:action,resolved', 'nullable', 'image', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'action.required'  => 'Aksi tidak valid.',
            'action.in'        => 'Aksi harus verify atau reject.',
            'reason.required_if' => 'Alasan penolakan wajib diisi.',
            'reason.min'       => 'Alasan penolakan minimal 10 karakter.',
        ];
    }
}
