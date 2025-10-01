<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arret extends Model
{
    //


    // fillable
    protected $fillable = [
        "motif",
        "vehicule_id"
    ];



    // relations
    public function vehicule() {

        return $this->belongsTo(Vehicule::class);

    }


}
