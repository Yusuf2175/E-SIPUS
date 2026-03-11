<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin() || $this->user()->isPetugas();
    }

    public function rules(): array
    {
        return [
            'category' => 'nullable|string',
            'availability' => 'nullable|in:all,available,borrowed',
            'format' => 'required|in:pdf,excel'
        ];
    }

    public function messages(): array
    {
        return [
            'format.required' => 'Report format is required',
        ];
    }
}
