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

        // Création de l'admin, fixe
        $adminUser = User::factory()->create([
            'name' => 'Cashandrick',
            'email' => 'nolween.lopez@gmail.com',
            'password' => bcrypt('123456'),
            'role_id' => 1,
            'is_banned' => false
        ]);
        // Utilisateurs random
        User::factory(29)->create();
    }
}
