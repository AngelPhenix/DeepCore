<?php 

namespace App\Livewire;

use App\Models\Planet;
use App\Models\User;
use Livewire\Component;

class GameDashboard extends Component
{
    public $planet;

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
    }

    public function render()
    {
        return view('livewire.game-dashboard')
            ->layout('components.layouts.app'); // On pointe vers le layout standard de Laravel/Livewire
    }
}