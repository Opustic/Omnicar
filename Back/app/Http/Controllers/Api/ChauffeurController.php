<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateChauffeurRequest;
use App\Models\Chauffeur;
use App\Models\Employe;
use App\Models\Equipe;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChauffeurController extends Controller
{
    
    // Fonction pour récupérer tous les chauffeurs
    public function index()
    {
        $chauffeurs = Chauffeur::all()
        ->map(function($chauffeur){
            $chauffeur->photo = asset("storage/".$chauffeur->photo);
            return $chauffeur;
        });

        return response()->json($chauffeurs);
    }

    
    // Fonction pour ajouter un nouveau chauffeur
    public function store (CreateChauffeurRequest $request){

        // Récupération des données validées
        $data = $request->validated();


        // photo du chauffeur
        $photoChauffeurPath = $request->file("photo_chauffeur")->store("chauffeurs", "public");


        // création de l'employé
        $employe = Employe::create([
            "salaire"=>0
        ]);


        // Création du chauffeur
        $chauffeur = Chauffeur::create([
            'nom' => $data['nom_chauffeur'],
            'telephone' => $data['telephone_chauffeur'],
            'adresse' => $data['adresse_chauffeur'],
            'email' => $data['email_chauffeur'],
            'numero_permis' => $data['numero_permis_chauffeur'],
            'photo' => $photoChauffeurPath,
            'employe_id'=>$employe->id
        ]);




        // Retourner le message de succès et afficher le chauffeur créé
        return [
            "message" => "chauffeur créé avec succès",
            "data"=>response()->json($chauffeur)
        ];
    }



    // editer les informations d'un chauffeur
    public function edit (Request $request, $id){


        // récupération du chauffeur
        $chauffeur = Chauffeur::findOrFail($id);


        $data = $request->validate([
            'nom' => 'string|max:255',
            'telephone' => 'string|max:20',
            'adresse' => 'string|max:255',
            'email' => 'email|max:255',
            'numero_permis' => 'string|max:255',
            'photo' => 'nullable|image|max:2048'
        ]);


        // traiter la photo 
        if ($request->hasFile("photo")){

            if ($chauffeur->photo && Storage::disk("public")->exists($chauffeur->photo)){
                Storage::disk("public")->delete($chauffeur->photo);
            }
            
            $data["photo"] = $request->file("photo")->store("chauffeurs", "public");
        }



        // Supprimer les champs vides
        $data = array_filter($data, function ($value) {
            return $value !== null && $value !== '';
        });


        // on trouve le chauffeur et on le met à jour
        $chauffeur->update($data);


    
        return response()->json([
            "message"=>"Chauffeur mis à jour",
            "status_code"=>200
        ]);
    }


    // supprimer un chauffeur
    public function destroy($id)
    {
        // récupération
        $chauffeur = Chauffeur::findOrFail($id);

        // récupération du véhicule associé et on le rend inactif
        $equipe_id = $chauffeur->equipe_id;

        
        // on récupère l'id de l'équipe
        $equipe = Equipe::where('id', $equipe_id)->first();
        if ($equipe) {
            
            $vehicule = Vehicule::where('id', $equipe->vehicule_id)->first();
            
            if ($vehicule) {
                $vehicule->statut = "inactif";
                $vehicule->save();
            }

        }


        // on passe l'id de l'équipe à null
        $chauffeur->equipe_id = null;
        $chauffeur->save();

        // on supprime le chauffeur (soft delete si activé)
        $chauffeur->delete();

        // on retourne un message
        return response()->json("Chauffeur supprimé avec succès");
    }


}
