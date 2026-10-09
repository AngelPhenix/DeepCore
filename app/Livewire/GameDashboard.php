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
    public $ironPerSecond = 5.00; // Pour l'instant, on fixe à 5 fer par seconde de base (on l'attachera aux bâtiments ensuite)

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
                'type' => 'rocky',
                'iron' => 100.00,
                'energy' => 50.00,
                'depth' => 0.00,
                'last_updated_at' => now(),
            ]
        );

        // --- CALCUL DU TICK ASYNCHRONE ---
        $this->calculateResourceTick();

        // Charger le catalogue des bâtiments disponibles
        $this->buildings = Building::all();
    }

    public function calculateResourceTick()
    {
        $now = Carbon::now();
        $lastUpdated = Carbon::parse($this->planet->last_updated_at);
        
        // Nombre de secondes écoulées depuis la dernière mise à jour
        $secondsElapsed = $lastUpdated->diffInSeconds($now);

        if ($secondsElapsed > 0) {
            // Calcul du fer généré
            $ironGenerated = $secondsElapsed * $this->ironPerSecond;

            // Mise à jour de la planète
            $this->planet->iron += $ironGenerated;
            $this->planet->last_updated_at = $now;
            $this->planet->save();
        }
    }

    // --- LOGIQUE D'ACHAT ET DE GOD ROLL ---
    public function buyBuilding($buildingId)
    {
        $building = Building::find($buildingId);
        if (!$building) return;

        // Coût de base (pour l'instant, simple)
        $cost = $building->base_cost_iron;

        // Vérifier si le joueur a assez de fer
        if ($this->planet->iron >= $cost) {
            // Déduire le coût
            $this->planet->iron -= $cost;
            $this->planet->save();

            // --- LE SYSTEME DE GOD ROLL ---
            // On tire un nombre aléatoire entre 1 et 10000 (pour gérer les pourcentages fins)
            $roll = mt_rand(1, 10000);
            
            $rarity = 'common';
            $multiplier = 1.00;

            if ($roll === 1) { // 0.01% de chance !
                $rarity = 'legendary';
                $multiplier = 5.00; // x5 d'efficacité !
            } elseif ($roll <= 100) { // 1% de chance
                $rarity = 'epic';
                $multiplier = 2.00; // x2 d'efficacité
            } elseif ($roll <= 1000) { // 9% de chance
                $rarity = 'rare';
                $multiplier = 1.50; 
            }

            // Enregistrer ou mettre à jour le bâtiment possédé sur la planète
            // (Pour l'instant, on crée une entrée dans constructed_buildings)
            ConstructedBuilding::create([
                'planet_id' => $this->planet->id,
                'building_id' => $building->id,
                'level' => 1,
                'rarity' => $rarity,
                'efficiency_multiplier' => $multiplier,
            ]);

            // Flash un message ou rafraîchir
            session()->flash('message', "Bâtiment construit ! Rareté : " . strtoupper($rarity) . " (Multiplicateur : x{$multiplier})");
        } else {
            session()->flash('error', "Pas assez de fer pour construire ceci !");
        }
    }

    public function render()
    {
        return view('livewire.game-dashboard');
    }
}