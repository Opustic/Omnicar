<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEquipeRequest;
use App\Models\Chauffeur;
use App\Models\Controleur;
use App\Models\Employe;
use App\Models\Equipe;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class EquipeController extends Controller
{

    // Fonction pour récupérer la liste des équipes
    public function index () {

        // Récupérer les équipes, et leurs membres

        $equipes = Equipe::with(["chauffeurs", "controleurs"])->get();

        $equipes->map(function ($equipe) {
            $equipe->chauffeurs->map(function ($chauffeur) {
                $chauffeur->photo = asset('storage/' . $chauffeur->photo);
            });

            $equipe->controleurs->map(function ($controleur) {
                $controleur->photo = asset('storage/' . $controleur->photo);
            });

            return response()->json($equipe);
        });

        return response()->json($equipes);

    }



    // récupérer une équipe particulière
    public function getEquipe($id) {
        

        // on récupère l'équipe
        $equipe = Equipe::with(["chauffeurs", "controleurs"])->findOrFail($id);

        // on mappe les données
        $equipe->chauffeurs[0]->photo = asset("storage/".$equipe->chauffeurs[0]->photo);
        $equipe->controleurs[0]->photo = asset("storage/".$equipe->controleurs[0]->photo);
        
        // on retourne l'équipe
        return $equipe;
    }


    // Fonction pour ajouter une équipe
    public function store (CreateEquipeRequest $request){


        // Récupération des données validées
        $data = $request->validated();


        // récupération des id du chauffeur et du controleur
        $chauffeur_id = $data["chauffeur_id"];
        $controleur_id = $data["controleur_id"];


        // Création de l'équipe
        $equipe = new Equipe();
        $equipe->save();

        // récupération de l'id de l'équipe
        $equipe_id = $equipe->id;



        // affectation de l'id de l'équipe sur le chauffeur et le controleur
        $chauffeur = Chauffeur::find($chauffeur_id);
        $controleur = Controleur::find($controleur_id);

        $chauffeur->equipe_id = $equipe_id;
        $controleur->equipe_id = $equipe_id;
        
        $chauffeur->save();
        $controleur->save();


        // renvoyer l'équipe créée
        return response()->json([
            'status_code'=>200,
            'message' => 'Équipe enregistrée avec succès',
            'equipe' => $equipe,
        ]);


    }



    // modifier une équipe
    public function update(Request $request, $id)
    {
        // Valider les données, rendre les champs optionnels
        $data = $request->validate([
            'chauffeur_id' => 'nullable|integer|exists:chauffeurs,id',
            'controleur_id' => 'nullable|integer|exists:controleurs,id',
        ]);

        // Vérifier qu'au moins un champ est fourni
        if (!$request->has('chauffeur_id') && !$request->has('controleur_id')) {
            return response()->json([
                'message' => 'Aucun champ à mettre à jour fourni.',
            ], 422);
        }

        // Trouver l'équipe
        $equipe = Equipe::with(['chauffeurs', 'controleurs'])->findOrFail($id);

        try {
            // Utiliser une transaction pour garantir la cohérence
            DB::transaction(function () use ($equipe, $data, $id) {
                // Gérer le chauffeur
                if (isset($data['chauffeur_id'])) {
                    $newChauffeurId = $data['chauffeur_id'];
                    $currentChauffeur = $equipe->chauffeurs->first(); // Récupérer le chauffeur actuel (suppose un seul)

                    if ($currentChauffeur && $currentChauffeur->id == $newChauffeurId) {
                        // Même chauffeur : ne rien faire
                        return;
                    }

                    $newChauffeur = Chauffeur::findOrFail($newChauffeurId);

                    // Vérifier si le nouveau chauffeur est déjà assigné à une autre équipe
                    if ($newChauffeur->equipe_id && $newChauffeur->equipe_id != $id) {
                        throw new \Exception("Le chauffeur est déjà assigné à l'équipe {$newChauffeur->equipe_id}.");
                    }

                    // Dissocier l'ancien chauffeur (s'il existe)
                    if ($currentChauffeur) {
                        $currentChauffeur->equipe_id = null;
                        $currentChauffeur->save();
                    }

                    // Assigner le nouveau chauffeur
                    $newChauffeur->equipe_id = $id;
                    $newChauffeur->save();
                }

                // Gérer le contrôleur
                if (isset($data['controleur_id'])) {
                    $newControleurId = $data['controleur_id'];
                    $currentControleur = $equipe->controleurs->first(); // Récupérer le contrôleur actuel (suppose un seul)

                    if ($currentControleur && $currentControleur->id == $newControleurId) {
                        // Même contrôleur : ne rien faire
                        return;
                    }

                    $newControleur = Controleur::findOrFail($newControleurId);

                    // Vérifier si le nouveau contrôleur est déjà assigné à une autre équipe
                    if ($newControleur->equipe_id && $newControleur->equipe_id != $id) {
                        throw new \Exception("Le contrôleur est déjà assigné à l'équipe {$newControleur->equipe_id}.");
                    }

                    // Dissocier l'ancien contrôleur (s'il existe)
                    if ($currentControleur) {
                        $currentControleur->equipe_id = null;
                        $currentControleur->save();
                    }

                    // Assigner le nouveau contrôleur
                    $newControleur->equipe_id = $id;
                    $newControleur->save();
                }
            });

            // Recharger l'équipe avec ses relations (si nécessaire)
            $equipe->refresh()->load(['chauffeurs', 'controleurs']);

            return response()->json([
                'message' => 'Équipe mise à jour avec succès.',
                'equipe' => $equipe
            ], 200);
        } catch (\Exception $e) {
            // Logger l'erreur pour le débogage
            \Log::error('Erreur lors de la mise à jour de l\'équipe : ' . $e->getMessage());

            return response()->json([
                'message' => 'Erreur lors de la mise à jour de l\'équipe.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    // Assigner une véhicule à une équipe
    public function assigner ($id, $vehiculeid){


        // on récupère l'équipe et le vehicule
        $equipe = Equipe::findOrFail($id);
        $vehicule = Vehicule::findOrFail($vehiculeid);



        // on change les id puis on sauvegarde
        $equipe->vehicule_id = $vehicule->id;
        $equipe->save();

        $vehicule->equipe_id = $equipe->id;
        $vehicule->save();


        return response()->json([
            "message"=>"Equipe assignée avec succès"
        ]);
    }


    
    // créer et assigner une équipe
    public function createToAssign (Request $request, $vehicule_id) {

        $data = $request->validate([
            'nom_chauffeur' => 'required|string|max:255',
            'telephone_chauffeur' => 'required|string|max:20',
            'adresse_chauffeur' => 'required|string|max:255',
            'email_chauffeur' => 'nullable|email|max:255',
            'numero_permis_chauffeur' => 'required|string|max:255',
            'photo_chauffeur' => 'required|image|max:2048',

            'nom_controleur' => 'required|string|max:255',
            'telephone_controleur' => 'required|string|max:20',
            'adresse_controleur' => 'required|string|max:255',
            'email_controleur' => 'nullable|email|max:255',
            'photo_controleur' => 'required|image|max:2048'
        ]);


        // Création de l'équipe
        $equipe = new Equipe();
        $equipe->save();


        // création de l'employé associé au chauffeur
        $employe_1 = Employe::create([
            "salaire"=>0
        ]);


        // photo du chauffeur
        $photoChauffeurPath = $request->file("photo_chauffeur")->store("chauffeurs", "public");


        // Création du chauffeur
        $chauffeur = Chauffeur::create([
            'nom' => $data['nom_chauffeur'],
            'telephone' => $data['telephone_chauffeur'],
            'adresse' => $data['adresse_chauffeur'],
            'email' => $data['email_chauffeur'],
            'numero_permis' => $data['numero_permis_chauffeur'],
            'photo' => $photoChauffeurPath,
            'employe_id'=>$employe_1->id,
            'equipe_id'=>$equipe->id
        ]);



        // récupération et stockage de la photo du controleur
        $photoControleurPath = $request->file("photo_controleur")->store("controleurs", "public");


        // création de l'employé associé au controleur
        $employe_2 = Employe::create([
            "salaire"=>0
        ]);


        // Création du contrôleur
        $controleur = Controleur::create([
            'nom' => $data['nom_controleur'],
            'telephone' => $data['telephone_controleur'],
            'adresse' => $data['adresse_controleur'],
            'email' => $data['email_controleur'],
            'photo' => $photoControleurPath,
            'employe_id'=>$employe_2->id,
            'equipe_id'=>$equipe->id
        ]);


        // Création de l'équipe
        $equipe->vehicule_id = $vehicule_id;
        $equipe->save();


        // on retourne le succès
        return response()->json([
            "message"=>"créé avec succès",
            "data"=>$equipe
        ]);

    }

}
