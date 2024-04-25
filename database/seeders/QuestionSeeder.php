<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //? Partie images
        // On efface au préalable les anciennes images de questions
        $file = new Filesystem;
        // Si le dossier des grandes images de question existe
        if ($file->isDirectory(storage_path('app/public/img/questions/big'))) {
            // Nettoyage du dossier
            $file->cleanDirectory(storage_path('app/public/img/questions/big'));
        } else {
            // Création du dossier
            $file->makeDirectory(storage_path('app/public/img/questions/big'), 0755, true);
        }

        // Si le dossier des vignettes de question existe
        if ($file->isDirectory(storage_path('app/public/img/questions/small'))) {
            // Nettoyage du dossier
            $file->cleanDirectory(storage_path('app/public/img/questions/small'));
        } else {
            // Création du dossier
            $file->makeDirectory(storage_path('app/public/img/questions/small'), 0755, true);
        }
        // Création des nouvelles questions
        Question::factory(100)->create();
    }
}
