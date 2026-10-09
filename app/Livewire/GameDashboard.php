<?php

namespace App\Livewire;

use App\Models\Planet;
use App\Models\User;
use App\Models\Building;
use App\Models\ConstructedBuilding;
use Carbon\Carbon;
use Livewire\Component;

class GameDashboard extends Component
{
    public $planet;
    public $buildings;
    public $ironPerSecond = 5.00; 

    public function mount()
    {
        $user = auth()->user() ?? User::first();
        
        if (!$user) {
            $user = User::create([
                'name' => 'admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('admin'),
            ]);
        }

        $this->planet = Planet::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => 'Nova Prime',
                'type' => 'Terrestrial',
                'iron' => 100.00,
                'energy' => 50.00,
                'depth' => 0.00,
                'last_updated_at' => now(),
            ]
        );

        $this->buildings = Building::all();
    }

    /** Fer réel à l'instant T, sans rien écrire en base. */
    public function currentIron(): float
    {
        $elapsed = max(0, now()->timestamp - Carbon::parse($this->planet->last_updated_at)->timestamp);

        return (float) $this->planet->iron + $elapsed * $this->ironPerSecond;
    }

    public function buyBuilding($buildingId)
    {
        $building = Building::find($buildingId);
        if (!$building) return;

        $iron = $this->currentIron(); 

        if ($iron >= $building->base_cost_iron) {
            $this->planet->iron = $iron - $building->base_cost_iron;
            $this->planet->last_updated_at = now();
            $this->planet->save();

            // Votre logique de God Roll ici...
            $roll = mt_rand(1, 10000);
            $rarity = 'common';
            $multiplier = 1.00;

            if ($roll === 1) {
                $rarity = 'legendary';
                $multiplier = 5.00;
            } elseif ($roll <= 100) {
                $rarity = 'epic';
                $multiplier = 2.50;
            } elseif ($roll <= 1000) {
                $rarity = 'rare';
                $multiplier = 1.50;
            }

            ConstructedBuilding::create([
                'planet_id' => $this->planet->id,
                'building_id' => $building->id,
                'level' => 1,
                'rarity' => $rarity,
                'efficiency_multiplier' => $multiplier,
            ]);

            $msg = "Building constructed! Rarity: " . strtoupper($rarity) . " (Multiplier: x{$multiplier})";
            
            // On déclenche la synchro du fer ET le toast de succès
            $this->dispatch('sync-iron', iron: $this->planet->iron);
            $this->dispatch('notify', message: $msg, type: 'success', rarity: $rarity);

        } else {
            $this->dispatch('notify', message: "Not enough iron to build this!", type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.game-dashboard');
    }
}