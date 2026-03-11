<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isAdmin() || $this->user()->isPetugas();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $bookId = $this->route('book')->id;
        
        return [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn,' . $bookId,
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'published_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'total_copies' => 'required|integer|min:1',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Book title is required',
            'author.required' => 'Author name is required',
            'isbn.required' => 'ISBN is required',
            'isbn.unique' => 'This ISBN already exists in the system',
            'category.required' => 'Category is required',
            'total_copies.required' => 'Total copies is required',
            'total_copies.min' => 'Total copies must be at least 1',
            'published_year.max' => 'Published year cannot be in the future',
            'cover_image.image' => 'Cover must be an image file',
            'cover_image.max' => 'Cover image size must not exceed 2MB',
        ];
    }
}
