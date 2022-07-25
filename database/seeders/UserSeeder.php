<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
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
        User::create(['name' => 'Cashandrick', 'avatar' => '1.jpg', 'email' => 'nolween.lopez@gmail.com', 'password' => bcrypt('123456'), 'role_id' => 1, 'is_banned' => false, 'email_verified_at' => now()]);
        for ($i=2; $i <= 30; $i++) { 
            User::create(
                [
                    'name' => fake()->unique()->name(),
                    'avatar' => $i . '.jpg',
                    'email' => fake()->unique()->safeEmail(),
                    'email_verified_at' => now(),
                    'password' => bcrypt(123456), // password
                    'remember_token' => Str::random(10),
                    'is_banned' => false,
                    'role_id' => Role::inRandomOrder()->first()->id
                    ]
            );
        }
    }
}
