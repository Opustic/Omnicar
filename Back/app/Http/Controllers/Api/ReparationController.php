<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Depense;
use App\Models\Reparation;
use DB;
use Illuminate\Http\Request;


class ReparationController extends Controller
{
    //


    public function index() {
        return "Je suis la page pour toutes les pannes";
    }


    // récupérer les réparations d'un vehicule
    public function getPanneByVehicule($id) {

        $reparations = Reparation::where("vehicule_id", $id)
        ->where("statut","!=" , "annulé")
        ->with("mecanicien")
        ->orderBy("created_at", "desc")
        ->get();

        $reparations = $reparations->map(function ($reparation) {
            // Vérifier si le champ photo contient déjà une URL complète
            if ($reparation->mecanicien->photo && !str_contains($reparation->mecanicien->photo, 'http')) {
                // Si ce n'est pas une URL complète, utiliser asset()
                $reparation->mecanicien->photo = asset("storage/" . $reparation->mecanicien->photo);
            }
            // Si c'est déjà une URL complète, ne rien faire
            return $reparation;
        });

        // Retourner les réparations
        return response()->json($reparations);
    }



    // total par motif
    public function total_par_motif($id) {

        $total = Reparation::where("vehicule_id", $id)
        ->select("description", DB::raw("count(*) as total"))
        ->groupBy("description")
        ->get();


        // on retourne le résultat
        return $total;

    }



    // ajouter une nouvelle réparation
    public function store(Request $request, $id)
    {
        $data = $request->validate([
            "mecanicien_id" => "required|exists:mecaniciens,id",
            "cout" => "required|numeric",
            "main_doeuvre" => "required|numeric",
            "description" => "required|string",
        ]);
    

        $reparation = Reparation::create([
            "vehicule_id"   => $id,
            "mecanicien_id" => $data["mecanicien_id"],
            "cout"          => $data["cout"],
            "main_doeuvre"  => $data["main_doeuvre"],
            "description"   => $data["description"],
            "statut"        => "en_attente"
        ]);


    
        return response()->json($reparation, 201);
    }
    


    // modifier le statut d'une réparation
    public function updateRepairStatus (Request $request, $reparation_id) {

        $data = $request->validate([
            "statut"=>"required|string"
        ]);


        // création de la réparation
        $reparation = Reparation::findOrFail($reparation_id);
        $reparation->statut = $data["statut"];
        $reparation->save();


        if ($reparation->statut === "terminé") {

            // calcul de la dépense totale
            $depense_totale = $data["cout"]+$data["main_doeuvre"];
            $depense = Depense::create([
                "vehicule_id"=>$reparation->vehicule_id,
                "categorie"=>"reparation",
                "montant"=>$depense_totale,
            ]);    

        }


        // réparation modifié avec succès
        return $reparation;


        
    }
    

}
