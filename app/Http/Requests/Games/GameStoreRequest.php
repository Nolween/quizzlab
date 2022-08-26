<?php

namespace App\Http\Requests\Games;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GameStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'maxPlayers' => ['integer', 'required', 'min:1', 'max:30'],
            'questionCount' => ['integer', 'required', 'min:1', 'max:50'],
            'responseTime' => ['integer', 'required', 'min:1', 'max:180'],
            'allTags' => ['boolean'],
            'selectedThemes' => ['array', 'nullable']
        ];
    }
}
