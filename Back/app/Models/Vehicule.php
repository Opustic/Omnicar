<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicule extends Model
{
    // fillable
    protected $fillable = [
        "immatriculation",
        "photo",
        "statut"
    ];


    // objectif journalier
    public const OBJECTIF_JOURNALIER = 30000;



    // relations
    public function equipes() {
        return $this->hasOne(Equipe::class);
    }


    public function versements () {
        return $this->hasMany(Versement::class);
    }

    
    public function reparations() {
        return $this->hasMany(Reparation::class);
    }


    public function arrets () {
        return $this->hasMany(Arret::class);
    }

}
