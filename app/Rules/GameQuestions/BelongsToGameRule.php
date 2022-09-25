<?php

namespace App\Rules\GameQuestions;

use App\Models\GameQuestion;
use Closure;
use Illuminate\Contracts\Validation\InvokableRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class BelongsToGameRule implements InvokableRule
{

    public int $gameQuestionId;

    /**
     * Construction de la règle
     *
     * @param integer $gameQuestionId
     */
    public function __construct(int $gameQuestionId)
    {
        $this->gameQuestionId = $gameQuestionId;
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  Closure(string): PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail): void
    {
        // Récupération de la question de partie
        $gameQuestion = GameQuestion::find($this->gameQuestionId);
        // Si pas de question de partie trouvée
        if(empty($gameQuestion)) {
            $fail('Pas de question trouvée');
        }
        // Si la partie et la partie de la question ne correspondent pas
        if($gameQuestion->game_id != $value) {
            $fail("Les valeurs $gameQuestion->game_id et $value de partie ne correspondent pas");
        }
    }
}
