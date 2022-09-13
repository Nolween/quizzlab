<?php

namespace Database\Factories;

use App\Helpers\ImageTransformation;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = fake()->unique()->name();
        $filename = Str::slug($name);

        //? Image Avatar
        $imageContent = file_get_contents('https://loremflickr.com/300/300/girl,boy,man,woman');
        // Si on a bien une image
        if (!empty($imageContent)) {
            Storage::disk('public')->put('img/profile/' . $filename . '.jpg', $imageContent);

            // Transformation en avif
            $gdImage = imagecreatefromjpeg(storage_path('app/public/img/profile/' . $filename . '.jpg'));
            $resizeBigImg = ImageTransformation::image_resize_small($gdImage, 300, 300);
            \imageavif($resizeBigImg, storage_path('app/public/img/profile/' . $filename . '.avif'));

            imagedestroy($gdImage);
            imagedestroy($resizeBigImg);
            // On efface le png original
            unlink(storage_path('app/public/img/profile/' . $filename . '.jpg'));
        }

        return [
            'name' => $name,
            'avatar' => !empty($imageContent) ?  $filename . '.avif' : null,
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt(123456), // password
            'remember_token' => Str::random(10),
            'is_banned' => false,
            'role_id' => Role::inRandomOrder()->first()->id
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
