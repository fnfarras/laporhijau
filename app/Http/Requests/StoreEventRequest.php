<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() &&
            (auth()->user()->hasRole('relawan') || auth()->user()->hasRole('pemerintah') || auth()->user()->hasRole('admin'));
    }

    public function rules(): array
    {
        return [
            'title'            => ['required', 'string', 'max:255'],
            'description'      => ['required', 'string', 'min:20'],
            'location'         => ['required', 'string', 'max:255'],
            'latitude'         => ['nullable', 'numeric', 'between:-90,90'],
            'longitude'        => ['nullable', 'numeric', 'between:-180,180'],
            'event_date'       => ['required', 'date', 'after:now'],
            'max_participants' => ['nullable', 'integer', 'min:1', 'max:10000'],
            'category'         => ['required', 'string', 'in:Bersih-bersih,Tanam Pohon,Gotong Royong,Edukasi,Pengolahan Sampah,Umum'],
            'report_id'        => ['nullable', 'exists:reports,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Judul event wajib diisi.',
            'description.required' => 'Deskripsi event wajib diisi.',
            'description.min'      => 'Deskripsi minimal 20 karakter.',
            'location.required'    => 'Lokasi event wajib diisi.',
            'event_date.required'  => 'Tanggal event wajib diisi.',
            'event_date.after'     => 'Tanggal event harus di masa mendatang.',
            'category.required'    => 'Kategori event wajib dipilih.',
            'category.in'          => 'Kategori tidak valid.',
        ];
    }
}
