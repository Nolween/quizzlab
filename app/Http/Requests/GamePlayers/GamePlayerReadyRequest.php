<?php

namespace App\Http\Requests\GamePlayers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GamePlayerReadyRequest extends FormRequest
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
            'userId' => ['integer', 'required', 'exists:users,id'],
            'gameId' => ['integer', 'required', 'exists:games,id'],
        ];
    }
}
