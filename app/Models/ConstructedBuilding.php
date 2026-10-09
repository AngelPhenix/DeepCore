<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConstructedBuilding extends Model
{
    use HasFactory;

    protected $fillable = [
        'planet_id',
        'building_id',
        'level',
        'rarity',
        'efficiency_multiplier',
    ];

    // Un bâtiment construit appartient à une planète
    public function planet()
    {
        return $this->belongsTo(Planet::class);
    }

    // Il est lié à un modèle de bâtiment du catalogue
    public function building()
    {
        return $this->belongsTo(Building::class);
    }
}