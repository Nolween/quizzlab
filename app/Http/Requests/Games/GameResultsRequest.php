<?php

namespace App\Http\Requests\Games;

use App\Models\GamePlayer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class GameResultsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $gameId = $this->route('game');
        $gamePlayerExists = GamePlayer::where('user_id', Auth::user()->id)->where('game_id', $gameId)->exists();

        return Auth::check() && $gamePlayerExists;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
