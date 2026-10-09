<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'base_cost_iron',
        'cost_multiplier',
    ];

    // Un type de bâtiment peut être possédé plusieurs fois (sur différentes planètes)
    public function constructedBuildings()
    {
        return $this->hasMany(ConstructedBuilding::class);
    }
}