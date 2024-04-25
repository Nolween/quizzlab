<?php

namespace App\Http\Requests\Questions;

use App\Enums\UserRoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class QuestionModerateEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === UserRoleEnum::Admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'id' => ['integer', 'required', 'exists:questions,id'],
            'question' => ['required', 'string', 'min:2'],
            'choices' => ['required', 'array'],
            'imageNeeded' => ['boolean'],
            'image' => ['nullable', 'mimes:jpg,png,jpeg,avif,webp'],
            'selectedThemes' => ['required', 'array'],
        ];
    }
}
