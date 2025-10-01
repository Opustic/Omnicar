<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mecanicien extends Model
{
    // utiliser le softdeletes
    use SoftDeletes;

    // fillable
    protected $fillable = [
        "nom",
        "telephone",
        "adresse",
        "email",
        "photo",
        "employe_id"
    ];


    // relations 
    public function reparations() {
        return $this->hasMany(Reparation::class);
    }
    

    public function employe() {

        return $this->belongsTo(Employe::class);

    }
}
