<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Hash;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         \App\Models\User::factory(15)->create();

         \App\Models\User::factory()->create([
             'name' => 'Taha',
             'email' => 'tahayassine@example.com',
             'password' =>Hash::make('12341234'),
         ]);
         \App\Models\User::factory()->create([
            'name' => 'adem',
            'email' => 'adem@example.com',
            'password' =>Hash::make('00000000'),
        ]);
    }
}
