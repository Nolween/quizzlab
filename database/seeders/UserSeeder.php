<?php

namespace Database\Seeders;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        //? Partie images d'avatar
        $file = new Filesystem;
        // Si le dossier des avatars existe
        if ($file->isDirectory(storage_path('app/public/img/profile'))) {
            // Nettoyage du dossier
            $file->cleanDirectory(storage_path('app/public/img/profile'));
        } else {
            // Création du dossier
            $file->makeDirectory(storage_path('app/public/img/profile'), 0755, true);
        }

        // Création de l'admin, fixe
        User::factory()->create([
            'name' => 'Cashandrick',
            'email' => 'nolween.lopez@gmail.com',
            'password' => bcrypt('123456'),
            'role' => UserRoleEnum::Admin,
            'is_banned' => false
        ]);
        // Utilisateurs random
        User::factory(29)->create();
    }
}
