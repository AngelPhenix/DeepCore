<?php

namespace Database\Seeders;

use App\Models\Building;
use Illuminate\Database\Seeder;

class BuildingSeeder extends Seeder
{
    public function run(): void
    {
        Building::create([
            'name' => 'Iron Mine',
            'slug' => 'iron-mine',
            'description' => 'Extracts raw iron ore from the depths of the planet.',
            'base_cost_iron' => 10,
            'cost_multiplier' => 1.15,
        ]);

        Building::create([
            'name' => 'Solar Plant',
            'slug' => 'solar-plant',
            'description' => 'Provides the energy needed to automate extraction operations.',
            'base_cost_iron' => 100,
            'cost_multiplier' => 1.20,
        ]);

        Building::create([
            'name' => 'Advanced Foundry',
            'slug' => 'advanced-foundry',
            'description' => 'Refines minerals to increase overall yield and efficiency.',
            'base_cost_iron' => 250,
            'cost_multiplier' => 1.25,
        ]);
    }
}