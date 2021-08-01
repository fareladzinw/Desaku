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
        \App\Models\User::factory()->count(1)->create();
        \App\Models\Desa::factory()->count(1)->create();
        \App\Models\RukunWarga::factory()->count(1)->create();
        \App\Models\RukunTetangga::factory()->count(1)->create();
    }
}
