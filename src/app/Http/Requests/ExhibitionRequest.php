<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image_path' => 'required|image|mimes:jpeg,png',
            'categories' => 'required|array|min:1',
            'categories.*' => 'string|max:255',
            'condition_id' => 'required|in:1,2,3,4',
            'price' => 'required|integer|min:0',
        ];
    }
}
