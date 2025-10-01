<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reparation extends Model
{
    //

    // fillable
    protected $fillable = [
        "vehicule_id",
        "mecanicien_id",
        "cout",
        "main_doeuvre",
        "description",
        "statut"
    ];



    // relations
    public function vehicule() {
        return $this->belongsTo(Vehicule::class);
    }

    
    public function mecanicien() {
        return $this->belongsTo(Mecanicien::class);
    }

}
