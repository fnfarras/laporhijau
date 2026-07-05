<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnonymousReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Anonymous is always allowed to submit
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title'             => ['required', 'string', 'max:255'],
            'category_id'       => ['required', 'exists:report_categories,id'],
            'description'       => ['required', 'string'],
            'address'           => ['required', 'string'],
            'latitude'          => ['required', 'numeric'],
            'longitude'         => ['required', 'numeric'],
            'photos'            => ['nullable', 'array', 'max:3'],
            'photos.*'          => ['image', 'max:5120'], // Max 5MB
            'anonymous_name'    => ['nullable', 'string', 'max:100'],
            'anonymous_contact' => ['nullable', 'string', 'max:100'],
        ];
    }
}
