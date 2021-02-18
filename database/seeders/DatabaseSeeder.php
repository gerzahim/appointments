<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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

        // Adding an admin user
        $user = User::factory()
            ->count(1)
            ->create([
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
            ]);

        $this->call(ServiceSeeder::class);
        $this->call(AnalystSeeder::class);
        $this->call(AppoinmentSeeder::class);
    }
}
