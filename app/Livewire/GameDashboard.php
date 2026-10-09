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
                'name' => 'Commandant Test',
                'email' => 'test@deepcore.local',
                'password' => bcrypt('password'),
            ]);
        }

        $this->planet = Planet::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => 'Nova Prime',
                'type' => 'rocheuse',
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

        $iron = $this->currentIron(); // valeur serveur exacte, jamais celle du client

        if ($iron >= $building->base_cost_iron) {
            // On "fige" l'état : nouveau fer, nouveau point de départ
            $this->planet->iron = $iron - $building->base_cost_iron;
            $this->planet->last_updated_at = now();
            $this->planet->save();

            // Système de God Roll
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

            session()->flash('message', "Building constructed! Rarity: " . strtoupper($rarity) . " (Multiplier: x{$multiplier})");
        } else {
            session()->flash('error', "Not enough iron to build this!");
        }

        $this->dispatch('sync-iron', iron: $this->currentIron());
    }

    public function render()
    {
        return view('livewire.game-dashboard');
    }
}