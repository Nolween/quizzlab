<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'question' => ['string', 'required'],
            'answer' => ['string', 'required'],
            'image' => ['string', 'nullable'],
            'is_integrated' => ['boolean', 'nullable'],
            'vote' => ['integer', 'nullable'],
            'ratio_score' => ['numeric', 'min:0', 'max:1', 'nullable'],
        ];
    }
}
