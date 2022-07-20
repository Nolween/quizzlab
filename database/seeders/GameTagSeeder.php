<?php

namespace Database\Seeders;

use App\Models\GameTag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GameTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GameTag::factory(800)->create();
    }
}
