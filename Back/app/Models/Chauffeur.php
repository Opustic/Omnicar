<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chauffeur extends Model
{
    
    use SoftDeletes;
    
    // fillable
    protected $fillable = [
        "nom",
        "equipe_id",
        "telephone",
        "adresse",
        "email",
        "photo",
        "numero_permis",
        "employe_id"
    ];


    // pourcentage de commission
    protected $pourcentage = 0.40; //40% des versements effectués

    
    // fonction pour calculer le salaire
    public function calculerSalaire($annee = null, $mois = null) {

        $annee = $annee ?? now()->year;
        $mois = $mois ?? now()->month;

        // Récupération du total des versements pour ce bus
        $totalVersements = Versement::where('employe_id', $this->id)
            ->where('role', 'chauffeur')
            ->whereYear('created_at', $annee)
            ->whereMonth('created_at', $mois)
            ->sum('montant');

        return $this->pourcentage * $totalVersements;

    }


    // relations
    public function equipe () {
        return $this->belongsTo(Equipe::class);
    }


    public function versements() {
        return $this->hasMany(Versement::class, "employe_id")->where("role", "chauffeur");
    }


    public function employe() {

        return $this->belongsTo(Employe::class);

    }

}
