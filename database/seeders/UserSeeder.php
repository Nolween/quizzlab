<?php

namespace Database\Seeders;

use App\Helpers\ImageTransformation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //? Partie images d'avatar
        $file = new Filesystem;
        // Si le dossier des avatars existe
        if ($file->isDirectory(storage_path('app/public/img/profile'))) {
            // Nettoyage du dossier
            $file->cleanDirectory(storage_path('app/public/img/profile'));
        } else {
            // Création du dossier
            $file->makeDirectory(storage_path('app/public/img/profile'));
        }


        //? Image Avatar
        Storage::disk('public')->put('img/profile/Cashandrick.jpg', file_get_contents('https://loremflickr.com/300/300'));
        // Transformation en avif
        $gdImage = imagecreatefromjpeg(storage_path('app/public/img/profile/Cashandrick.jpg'));
        $resizeBigImg = ImageTransformation::image_resize_small($gdImage, 300, 300);
        \imageavif($resizeBigImg, storage_path('app/public/img/profile/Cashandrick.avif'));

        imagedestroy($gdImage);
        imagedestroy($resizeBigImg);
        // On efface le png original
        unlink(storage_path('app/public/img/profile/Cashandrick.jpg'));


        //? Data
        // Création de l'admin, fixe
        User::create(['name' => 'Cashandrick', 'avatar' => 'Cashandrick.avif', 'email' => 'nolween.lopez@gmail.com', 'password' => bcrypt('123456'), 'role_id' => 1, 'is_banned' => false, 'email_verified_at' => now()]);

        // Utilisateurs random
        User::factory(29)->create();
    }
}
