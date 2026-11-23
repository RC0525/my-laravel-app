<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\PostureStretchSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoutineSeeder::class,
            PostureStretchSeeder::class,
        ]);
    }
}
