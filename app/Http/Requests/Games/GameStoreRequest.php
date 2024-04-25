<?php

namespace App\Http\Requests\Games;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class GameStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('authorized-user');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'maxPlayers' => ['integer', 'required', 'min:1', 'max:30'],
            'questionCount' => ['integer', 'required', 'min:1', 'max:50'],
            'responseTime' => ['integer', 'required', 'min:1', 'max:180'],
            'allTags' => ['required', 'boolean'],
            'selectedThemes' => ['array', 'nullable'],
            'selectedThemes.*' => ['string', 'exists:tags,name'],
            'possibleQuestions' => ['integer', 'required', 'min:1', 'gte:questionCount'], // On doit avoir plus de questions possibles que de questions définies
        ];
    }
}
