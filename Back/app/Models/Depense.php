<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Depense extends Model
{
    //
    protected $fillable = [
        "categorie",
        "montant", 
        "vehicule_id",
        "employe_id"
    ];
}
