<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Controleur extends Model
{

    // utilisation du softdeletes
    use SoftDeletes;

    
    // fillable 
    protected $fillable = [
        "nom",
        "equipe_id",
        "telephone",
        "adresse",
        "email", 
        "photo",
        "employe_id"
    ];



    // relations
    public function equipe () {
        return $this->belongsTo(Equipe::class);
    }



    public function versements () {
        return $this->hasMany(Versement::class, "employe_id")->where("role", "controleur");
    }


    public function employe() {

        return $this->belongsTo(Employe::class);

    }
}
