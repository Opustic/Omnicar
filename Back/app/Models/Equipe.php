<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipe extends Model
{
    
    // fillable
    protected $fillable = [
        "id",
        "vehicule_id"
    ];



    // Relations
    public function chauffeurs()
    {
        return $this->hasMany(Chauffeur::class);
    }



    public function controleurs()
    {
        return $this->hasMany(Controleur::class);
    }


    public function vehicules() {
        return $this->belongsTo(Vehicule::class);
    }


}
