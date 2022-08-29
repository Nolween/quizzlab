<?php

namespace App\Http\Requests\GameChats;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

    class GameChatStoreRequest extends FormRequest
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
            'message' => ['required', 'string'],
            'gameId' => ['required', 'integer', 'exists:game_chats,id']
        ];
    }
}
