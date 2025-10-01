<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class VehiculeController extends Controller
{

public function index()
{
    // Récupérer les véhicules avec leurs relations
    $vehicules = Vehicule::with([
        'equipes.chauffeurs',
        'equipes.controleurs',
        'versements' => function ($query) {
            // Filtrer les versements du jour
            $query->whereDate('created_at', Carbon::today());
        },
        'reparations'
    ])->get()->map(function ($vehicule) {
        // Mapper la photo du véhicule
        $vehicule->photo = $vehicule->photo ? asset('storage/' . $vehicule->photo) : null;

        // Vérifier si equipes existe
        if ($vehicule->equipes) {
            $equipe = $vehicule->equipes;

            // Mapper la photo du chauffeur si disponible
            if ($equipe->chauffeurs && $equipe->chauffeurs->isNotEmpty()) {
                $equipe->chauffeurs[0]->photo = asset('storage/' . $equipe->chauffeurs[0]->photo);
            }

            // Mapper la photo du contrôleur si disponible
            if ($equipe->controleurs && $equipe->controleurs->isNotEmpty()) {
                $equipe->controleurs[0]->photo = asset('storage/' . $equipe->controleurs[0]->photo);
            }
        }

        // Calculer le total des versements du jour
        $vehicule->total_versements_jour = $vehicule->versements->sum('montant');

        // Compter le nombre total de réparations
        $vehicule->nombre_reparations = $vehicule->reparations->count();

        return $vehicule;
    });

    // Retourner une réponse JSON
    return $vehicules;
}



    // récupérer un vehicule par son id
    public function getVehicule($id){

        $vehicule = Vehicule::with("equipes.chauffeurs", "equipes.controleurs")->findOrFail($id);


        // mappage des données photographiques
        $vehicule->photo = asset("storage/".$vehicule->photo);


        if ($vehicule->equipes) {

            if ($vehicule->equipes->chauffeurs->isNotEmpty()) {
                foreach ($vehicule->equipes->chauffeurs as $chauffeur) {
                    $chauffeur->photo = asset("storage/".$chauffeur->photo);
                }
            }

            if ($vehicule->equipes->controleurs->isNotEmpty()) {
                foreach ($vehicule->equipes->controleurs as $controleur) {
                    $controleur->photo = asset("storage/".$controleur->photo);
                }
            }
        }



        // on retourne le vehicule
        return $vehicule;
    }




    // ajouter un nouveau vehicule
    public function store (Request $request) {

        // validation des données
        $data = $request->validate([
            "immatriculation"=>"required|string|max:20",
            "equipe"=>"nullable",
            "photo"=>"required|image|max:2048"
        ]);


        // on récupère puis on traite la photo
        $photoVehiculePath = $request->file("photo")->store("/vehicules", "public");


        // création du vehicule
        $vehicule = Vehicule::create([
            "immatriculation"=>$data["immatriculation"],
            "equipe_id"=>$data["equipe"],
            "photo"=>$photoVehiculePath,
            "statut"=>"inactif"
        ]);



        // on retourne la réponse
        return response()->json([
            "message"=>"vehicule créé avec succès",
            "data"=>$vehicule
        ]);

    }


    // editer un vehicule
    public function update (Request $request, $id) {



        // on trouve le vehicule ayant le vehicule correspondant et on le met à jour
        $vehicule = Vehicule::findOrFail($id);



        // validation des données
        $data = $request->validate([
            "immatriculation"=>"nullable|string|max:20",
            "photo"=>"nullable|image|max:2048"
        ]);


        // traitement de la photo
        if ($request->hasFile("photo")){

            if ($vehicule->photo && Storage::disk("public")->exists($vehicule->photo)) {
                Storage::disk("public")->delete($vehicule->photo);
            }
            $data["photo"] = $request->file("photo")->store("vehicules", "public");
        }


        
        // Supprimer les champs vides
        $data = array_filter($data, function ($value) {
            return $value !== null && $value !== '';
        });



        // on met à jour les données du vehicule
        $vehicule->update($data);



        return response()->json([
            "message"=>"vehicule modifié avec succès",
            "data"=>$vehicule
        ]);
    }


    // changer le statut d'un vehicule
    public function changerStatut(Request $request, $id) {

        // récupération du vehicule
        $vehicule = Vehicule::findOrFail($id);


        // validation des données
        $data = $request->validate([
            "statut"=>"required|string|max:10"
        ]);


        // mise à jour
        $vehicule->update($data);

        
        return response()->json([
            "message"=>"statut mis à jour avec succès",
            "data"=>$vehicule
        ]);


    }
}
