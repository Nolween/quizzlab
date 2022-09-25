<?php

namespace Database\Seeders;

use App\Models\GameChat;
use Illuminate\Database\Seeder;

class GameChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        GameChat::factory(80)->create();
    }
}
