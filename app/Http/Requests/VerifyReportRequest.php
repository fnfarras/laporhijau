<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyReportRequest extends FormRequest
{
    /**
     * Hanya relawan yang boleh memverifikasi laporan.
     */
    public function authorize(): bool
    {
        return $this->user()?->hasRole('relawan') ?? false;
    }

    /**
     * Tidak ada input tambahan yang diperlukan untuk verifikasi —
     * aksi ini bersifat konfirmasi satu klik.
     * Form Request ini hadir untuk memastikan otorisasi tetap
     * melewati layer yang benar (bukan hanya middleware route).
     */
    public function rules(): array
    {
        return [];
    }
}
