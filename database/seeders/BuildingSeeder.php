<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BuildingSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('buildings')->insert([
            [
                'name' => 'Iron Mine',
                'slug' => 'iron_mine',
                'description' => 'Extracts raw iron from the rocky strata of the planet.',
                'base_cost_iron' => 60.00,
                'cost_multiplier' => 1.50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}