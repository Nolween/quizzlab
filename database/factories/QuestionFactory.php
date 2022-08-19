<?php

namespace Database\Factories;

use App\Helpers\ImageTransformation;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $question = substr_replace(fake()->unique()->sentence(10), '?', -1);
        $hasImage = fake()->boolean(50);
        // Si on a une image
        if ($hasImage == true) {
            $lastQuestion = Question::latest()->first();
            // Dernier id de question
            // Génération d'une image PNG (AVIF Pas dispo) dans le dossier big
            $randomHex = substr(fake()->hexColor(), 1);
            $filename = fake()->randomNumber(6, true);
            Storage::disk('public')->put('img/questions/big/' . $filename . '.png', file_get_contents('https://dummyimage.com/640x480/' . $randomHex . '.png?text=' . $question));
            // Storage::disk('public')->put('img/questions/small/'. $filename . '.png', file_get_contents('https://dummyimage.com/240x180/'. $randomHex .'.png?text=' . $question));

            // Transformation en avif
            $gdImage = imagecreatefrompng(storage_path('app/public/img/questions/big/' . $filename . '.png'));
            $resizeBigImg = ImageTransformation::image_resize_big($gdImage, 640, 480);
            \imageavif($resizeBigImg, storage_path('app/public/img/questions/big/' . $filename . '.avif'));
            $resizeSmallImg = ImageTransformation::image_resize_small($gdImage, 240, 180);
            \imageavif($resizeSmallImg, storage_path('app/public/img/questions/small/' . $filename . '.avif'));

            imagedestroy($gdImage);
            imagedestroy($resizeBigImg);
            imagedestroy($resizeSmallImg);
            // On efface le png original
            unlink(storage_path('app/public/img/questions/big/' . $filename . '.png'));
        }
        $image = $hasImage == true ? $filename . '.avif' : null;
        // La question a t-elle déjà été modérée? Si oui on défini si l'admin a validé
        $isModerated = fake()->boolean(80) == true ? fake()->boolean(80) : null;
        // Si la question a été modérée, chance qu'elle soit intégrée
        $vote = $isModerated == true ? rand(-500, 2000) : 0;
        $isIntegrated = $isModerated == true && $vote > 100 ? fake()->boolean(80) : null;
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'question' => $question,
            'answer' => fake()->sentence(3),
            'image' => $image,
            'is_moderated' => $isModerated,
            'is_integrated' => $isIntegrated,
            'vote' => $vote,
            'ratio_score' => $isModerated == true ? fake()->randomFloat(2, 0, 1) : 0,
        ];
    }
}
