<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employe;
use Illuminate\Http\Request;
use App\Models\Mecanicien;
use Illuminate\Support\Facades\Storage;


class MecanicienController extends Controller
{

    // récupérer tous les mécaniciens
    public function index() {
        $mecaniciens = Mecanicien::all();

        $mecaniciens = $mecaniciens->map(function($mecanicien){
            $mecanicien->photo = asset("storage/".$mecanicien->photo);
            return $mecanicien;
        });


        return response()->json($mecaniciens);
    }



    // récupérer un mécanicien par son id
    public function getMecanicien($id) {
        $mecanicien = Mecanicien::findOrFail($id);

        $mecanicien->photo = asset("storage/".$mecanicien->photo);

        return response()->json($mecanicien);
    }
    
    

    // ajouter une nouvelle dépense
    public function store(Request $request) {
        $data = $request->validate([
            "nom_mecanicien" => "required|string|max:255",
            "telephone_mecanicien" => "required|string|max:255",
            "adresse_mecanicien" => "required|string|max:255",
            "email_mecanicien" => "nullable|email|max:255",
            "photo_mecanicien" => "required|image|max:2048" // max 2Mo
        ]);
    
        // stocker la photo
        $photoMecanicienPath = $request->file("photo_mecanicien")->store("mecaniciens", "public");

        
        $employe = Employe::create([
            "salaire"=>0
        ]);

    
        // créer le mécanicien
        $mecanicien = Mecanicien::create([
            "nom" => $data["nom_mecanicien"],
            "telephone" => $data["telephone_mecanicien"],
            "adresse" => $data["adresse_mecanicien"],
            "email" => $data["email_mecanicien"],
            "photo" => $photoMecanicienPath,
            "employe_id"=>$employe->id
        ]);
    
        // ajouter une URL complète pour la réponse
        $mecanicien->photo_url = asset("storage/" . $mecanicien->photo);
    
        return response()->json($mecanicien, 201);
    }
    



    // mettre à jour les infos d'un mécano
    public function update(Request $request, $id) {

        // récupérer le mécanicien
        $mecanicien = Mecanicien::findOrFail($id);

        // validation des données
        $data = $request->validate([
            "nom" => "required|string|max:255",
            "telephone" => "required|string|max:255",
            "adresse" => "required|string|max:255",
            "email" => "required|email|max:255",
            "photo" => "nullable|image|max:2048"
        ]);


        // traitement de la photo
        if ($request->hasFile("photo")){

            if ($mecanicien->photo && Storage::disk("public")->exists($mecanicien->photo)) {
                Storage::disk("public")->delete($mecanicien->photo);
            }

            $data["photo"] = $request->file("photo")->store("mecaniciens", "public");
        }



        $data = array_filter($data, function ($value) {
            return $value !== null && $value !== '';
        });


        // mise à jour du mécanicien
        $mecanicien->update($data);


        
        return response()->json([
            "message"=>"mécanicien mis à jour",
            "status_code"=>200
        ]);

    }


    // supprimer un mécanicien
    public function destroy ($id) {

        // récupération
        $mecanicien = Mecanicien::findOrFail($id);

        $mecanicien->delete();

        return "Mecanicien supprimé avec succès";

    }




}
