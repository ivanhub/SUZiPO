<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\AllUserSap;

class AllUserSapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        fake()->unique(true);
        AllUserSap::factory()->count(200)->create();
    }
}
