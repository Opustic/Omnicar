<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Versement extends Model
{
    //fillable
    protected $fillable = [
        "vehicule_id",
        "employe_id",
        "role",
        "montant",
        "date_versement"
    ];




    public function vehicule () {
        return $this->belongsTo(Vehicule::class);
    }


    public function employe() {
        if ($this->role=="chauffeur"){
            return $this->belongsTo(Chauffeur::class, "employe_id");
        }

        if ($this->role=="controleur"){
            return $this->belongsTo(Controleur::class, "employe_id");
        }


        return null;
    }
}
