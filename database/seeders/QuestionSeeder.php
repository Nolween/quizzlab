<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
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
            $file->makeDirectory(storage_path('app/public/img/questions/big'));
        }

        // Si le dossier des vignettes de question existe
        if ($file->isDirectory(storage_path('app/public/img/questions/small'))) {
            // Nettoyage du dossier
            $file->cleanDirectory(storage_path('app/public/img/questions/small'));
        } else {
            // Création du dossier
            $file->makeDirectory(storage_path('app/public/img/questions/small'));
        }
        // Création des nouvelles questions
        Question::factory(100)->create();
    }
}
