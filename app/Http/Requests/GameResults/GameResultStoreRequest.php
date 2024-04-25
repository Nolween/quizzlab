<?php

namespace App\Http\Requests\GameResults;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GameResultStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
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
            'choice_id' => ['nullable', 'integer', 'exists:question_choices,id'],
            'game_question_id' => ['integer', 'exists:game_questions,id', 'required'],
            'question_id' => ['integer', 'exists:questions,id', 'required'],
            'game_id' => ['integer', 'exists:games,id', 'required'],
        ];
    }
}
