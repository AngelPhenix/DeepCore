<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'iron',
        'energy',
        'depth',
        'last_updated_at',
    ];

    protected $casts = ['last_updated_at' => 'datetime'];

    // Une planète appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}