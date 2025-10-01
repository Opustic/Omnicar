<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateControleurRequest;
use App\Models\Controleur;
use App\Models\Employe;
use App\Models\Equipe;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ControleurController extends Controller
{
    

    // pour récupérer tous les controleurs 
    public function index(){
        $controleurs = Controleur::all()->map(function($controleur){
            $controleur->photo = asset("storage/".$controleur->photo);
            return $controleur;
        });
        return response()->json($controleurs);
    }



    // ajouter un nouveau controleur
    public function store (CreateControleurRequest $request) {


        // Récupération des données validées
        $data = $request->validated();


        // récupération et stockage de la photo du controleur
        $photoControleurPath = $request->file("photo_controleur")->store("controleurs", "public");


        $employe = Employe::create([
            "salaire"=>0
        ]);


        // Création du contrôleur
        $controleur = Controleur::create([
            'nom' => $data['nom_controleur'],
            'telephone' => $data['telephone_controleur'],
            'adresse' => $data['adresse_controleur'],
            'email' => $data['email_controleur'],
            'photo' => $photoControleurPath,
            'employe_id'=>$employe->id
        ]);


        // affichage de message de succès et du controleur créé
        return [
            "message"=>"controleur créé avec succès",
            "data"=>response()->json($controleur)
        ];
    }




    // Modifier les informations du controleur
    public function edit(Request $request, $id){



        // on récupère le controleur
        $controleur = Controleur::findOrFail($id);




        // validation des données
        $data = $request -> validate([
            'nom' => 'string|max:255',
            'telephone' => 'string|max:20',
            'adresse' => 'string|max:255',
            'email' => 'email|max:255',
            'photo' => 'image|max:2048'
        ]);



        // traitement de la photo
        if ($request->hasFile("photo")){

            if ($controleur->photo && Storage::disk("public")->exists($controleur->photo)) {
                Storage::disk("public")->delete($controleur->photo);
            }
            $data["photo"] = $request->file("photo")->store("controleurs", "public");
        }



        // Supprimer les champs vides
        $data = array_filter($data, function ($value) {
            return $value !== null && $value !== '';
        });



        // Mise à jour du controleur
        $controleur->update($data);


        return response()->json([
            "message"=>"controleur mis à jour",
            "status_code"=>200
        ]);


    }



    // supprimer un controleur
    public function destroy ($id) {

        // récupération
        $controleur = Controleur::findOrFail($id);


        // récupération du véhicule associé et on le rend inactif
        $equipe_id = $controleur->equipe_id;


        // on récupère l'id de l'équipe
        $equipe = Equipe::where('id', $equipe_id)->first();
        if ($equipe) {
            
            $vehicule = Vehicule::where('id', $equipe->vehicule_id)->first();
            
            if ($vehicule) {
                $vehicule->statut = "inactif";
                $vehicule->save();
            }

        }

        // récupération du véhicule associé et on le rend inactif
        $vehicule = Vehicule::where('equipe_id', $controleur->equipe_id)->first();
        if ($vehicule) {
            $vehicule->statut = "inactif";
            $vehicule->save();
        }    

        // on passe l'id de l'équipe à null
        $controleur->equipe_id = null;
        $controleur->save();


        // suppression
        $controleur->delete();


        // retourner un message
        return response()->json("Controleur supprimé avec succès");


    }

}
