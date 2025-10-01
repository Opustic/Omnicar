<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employe extends Model
{
    //

    protected $fillable = [
        "salaire"
    ];


    public function chauffeurs () {
        return $this->hasOne(Chauffeur::class);
    }


    public function controleurs () {
        return $this->hasOne(Controleur::class);
    }


    public function mecaniciens () {
        return $this->hasOne(Mecanicien::class);
    }

}
