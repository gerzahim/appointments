<?php

namespace Database\Seeders;
use App\Models\Analyst;

use Illuminate\Database\Seeder;

class AnalystSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Analyst::factory()
            ->count(2)
            ->create();
    }
}
