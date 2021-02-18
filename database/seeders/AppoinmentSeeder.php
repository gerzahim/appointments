<?php

namespace Database\Seeders;
use App\Models\Appoinment;

use Illuminate\Database\Seeder;

class AppoinmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Appoinment::factory()
                  ->count(4)
                  ->create();
    }
}
