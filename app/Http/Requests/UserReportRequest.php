<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin() || $this->user()->isPetugas();
    }

    public function rules(): array
    {
        return [
            'role' => 'nullable|in:all,user,petugas,admin',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date|after_or_equal:registration_start',
            'format' => 'required|in:pdf,excel'
        ];
    }

    public function messages(): array
    {
        return [
            'registration_end.after_or_equal' => 'Registration end date must be after or equal to start date',
            'format.required' => 'Report format is required',
        ];
    }
}
