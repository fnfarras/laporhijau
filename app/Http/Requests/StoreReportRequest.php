<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReportRequest extends FormRequest
{
    /**
     * Hanya user yang sudah login yang bisa submit laporan.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Aturan validasi form laporan.
     */
    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:20'],
            'category_id' => ['required', 'integer', 'exists:report_categories,id'],
            'address'     => ['required', 'string', 'max:500'],
            'latitude'    => ['required', 'numeric', 'between:-90,90'],
            'longitude'   => ['required', 'numeric', 'between:-180,180'],
            'photos'      => ['nullable', 'array', 'max:5'],
            'photos.*'    => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Judul laporan wajib diisi.',
            'title.max'            => 'Judul maksimal 255 karakter.',
            'description.required' => 'Deskripsi laporan wajib diisi.',
            'description.min'      => 'Deskripsi minimal 20 karakter.',
            'category_id.required' => 'Kategori masalah wajib dipilih.',
            'category_id.exists'   => 'Kategori tidak valid.',
            'address.required'     => 'Alamat lokasi wajib diisi.',
            'latitude.required'    => 'Koordinat lokasi wajib ditentukan. Klik peta atau gunakan GPS.',
            'longitude.required'   => 'Koordinat lokasi wajib ditentukan. Klik peta atau gunakan GPS.',
            'photos.max'           => 'Maksimal 5 foto yang dapat diunggah.',
            'photos.*.image'       => 'Setiap file harus berupa gambar.',
            'photos.*.max'         => 'Ukuran setiap foto maksimal 5MB.',
        ];
    }
}
