<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        \App\Models\User::factory()->create([
            'email' => 'f@f.com',
            'password' => \Hash::make('barcelona'),
            'name' => 'flori'
        ]);

        \DB::table('users')->insert([
            'name' => 'lolo',
            'email' => \Str::random(10).'@gmail.com',
            'password' => \Hash::make('password'),
        ]);

    }
}
