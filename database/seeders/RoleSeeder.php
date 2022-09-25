<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $source = [['name' => 'Administrateur'], ['name' => 'Utilisateur']];

        foreach ($source as $item) {
            Role::create($item);
        }
    }
}
