<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Arret;
use App\Models\Vehicule;
use DB;
use Illuminate\Http\Request;

class ArretController extends Controller
{    


    public function getArretByVehicule ($id) {

        $arrets = Arret::where("vehicule_id", $id)->get();

        return response()->json($arrets);

    }



    public function total_par_motif ($id){

        // Récupérer tous les arrêts du véhicule et compter par motif
        $total = Arret::where('vehicule_id', $id)
            ->select('motif', DB::raw('count(*) as total'))
            ->groupBy('motif')
            ->get();

        // Retourner la réponse JSON
        return $total;

    }



    public function store(Request $request, $id)
    {
        // Validation des données
        $validatedData = $request->validate([
            'motif' => 'required|string|max:255',
        ]);

        // on récupère le vehicule associé
        $vehicule = Vehicule::findOrFail($id);
    

        // Création de l'arrêt
        $arret = new Arret();
        $arret->vehicule_id = $id;
        $arret->motif = $validatedData['motif'];
        $arret->save();
    
        
        // on passe le vehicule à inactif
        $vehicule->statut = "inactif";
        $vehicule->save();


        // Retourner une réponse
        return response()->json([
            'success' => true,
            'message' => 'Arrêt créé avec succès',
            'data' => $arret
        ], 201);
    }



}
