<?php

namespace App\Http\Requests\Questions;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class QuestionStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'question' => ['required', 'unique:questions', 'string', 'min:2'],
            'choices' => ['required', 'array'],
            'imageNeeded' => ['boolean'],
            'image' => ['nullable', 'mimes:jpg,png,jpeg,avif,webp'],
            'rules' => ['accepted'],
            'selectedThemes' => ['required', 'array'],
        ];
    }
}
