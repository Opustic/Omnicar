<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chauffeur;
use App\Models\Controleur;
use App\Models\Vehicule;
use App\Models\Versement;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VersementController extends Controller
{
    
    
    //
    public function index () {
        return "<h1>Tu viens de faire un versement</h1>";
    }


    // modifier un versement
    public function update(Request $request, $id) {
        
        // validation des données
        $data = $request->validate([
            "montant"=>"numeric|min:0",
        ]);

        // récupération du versement
        $versement = Versement::findOrFail($id);


        // Supprimer les champs vides
        $data = array_filter($data, function ($value) {
            return $value !== null && $value !== '';
        });

        // mise à jour du versement
        $versement->update($data);
        


        return response()->json([
            "message"=>"modifié avec succès",
            "data"=>$versement->fresh()
        ]);

    }


    // Faire un versement
    public function insert(Request $request, $vehicule_id, $chauffeur_id, $controleur_id)
    {
        $data = $request->validate([
            "montant_chauffeur" => "required|numeric|min:0",
            "montant_controleur" => "required|numeric|min:0",
            "date_versement" => "nullable|date"
        ]);

        // Déterminer la date du versement
        $dateVersement = $data['date_versement'] ?? now()->toDateString();

        // Vérification de l'existence du vehicule, du chauffeur ou du controleur
        $vehicule = Vehicule::findOrFail($vehicule_id);
        $chauffeur = Chauffeur::findOrFail($chauffeur_id);
        $controleur = Controleur::findOrFail($controleur_id);

        // Vérifier si un versement pour ce véhicule, ce chauffeur et cette date existe déjà
        $existsChauffeur = Versement::where('vehicule_id', $vehicule_id)
            ->where('employe_id', $chauffeur_id)
            ->where('role', 'chauffeur')
            ->whereDate('date_versement', $dateVersement)
            ->exists();

        if ($existsChauffeur) {
            return response()->json([
                'message' => "Un versement pour ce chauffeur et ce véhicule existe déjà à la date {$dateVersement}."
            ], 422);
        }

        // Vérifier si un versement pour ce véhicule, ce controleur et cette date existe déjà
        $existsControleur = Versement::where('vehicule_id', $vehicule_id)
            ->where('employe_id', $controleur_id)
            ->where('role', 'controleur')
            ->whereDate('date_versement', $dateVersement)
            ->exists();

        if ($existsControleur) {
            return response()->json([
                'message' => "Un versement pour ce controleur et ce véhicule existe déjà à la date {$dateVersement}."
            ], 422);
        }

        // Versement du chauffeur
        $versementChauffeur = Versement::create([
            "vehicule_id" => $vehicule_id,
            "employe_id" => $chauffeur_id,
            "role" => "chauffeur",
            "montant" => $data["montant_chauffeur"],
            "date_versement" => $dateVersement
        ]);

        // Versement du controleur
        $versementControleur = Versement::create([
            "vehicule_id" => $vehicule_id,
            "employe_id" => $controleur_id,
            "role" => "controleur",
            "montant" => $data["montant_controleur"],
            "date_versement" => $dateVersement
        ]);

        // On retourne la réponse
        return response()->json([
            "message" => "Versement effectué avec succès",
            "data" => [
                "versement du chauffeur" => $versementChauffeur,
                "versement du controleur" => $versementControleur,
                "total versement" => $versementChauffeur->montant + $versementControleur->montant
            ]
        ]);
    }



    // versements effectués aujourd'hui
    public function todays_versements()
    {
        $today = Carbon::today();

        $versements = DB::table('versements')
            ->join('vehicules', 'versements.vehicule_id', '=', 'vehicules.id')
            ->join('employes', 'versements.employe_id', '=', 'employes.id')
            ->select(
                'vehicules.id as vehicule_id',
                'vehicules.immatriculation',
                'vehicules.photo as vehicule_photo',
                DB::raw("SUM(CASE WHEN role = 'chauffeur' THEN montant ELSE 0 END) as montant_chauffeur"),
                DB::raw("SUM(CASE WHEN role = 'controleur' THEN montant ELSE 0 END) as montant_controleur"),
                DB::raw("SUM(montant) as total")
            )
            ->whereDate('versements.created_at', $today)
            ->groupBy('vehicules.id', 'vehicules.immatriculation', 'vehicules.photo')
            ->get();

        // Maintenant on va chercher les infos chauffeur / contrôleur associés
        $result = $versements->map(function ($item) use ($today) {
            // Chauffeur
            $chauffeur = DB::table('versements')
                ->join('chauffeurs', 'versements.employe_id', '=', 'chauffeurs.id')
                ->where('versements.vehicule_id', $item->vehicule_id)
                ->whereDate('versements.created_at', $today)
                ->where('versements.role', 'chauffeur')
                ->select('chauffeurs.nom', 'chauffeurs.photo')
                ->first();

            // Contrôleur
            $controleur = DB::table('versements')
                ->join('controleurs', 'versements.employe_id', '=', 'controleurs.id')
                ->where('versements.vehicule_id', $item->vehicule_id)
                ->whereDate('versements.created_at', $today)
                ->where('versements.role', 'controleur')
                ->select('controleurs.nom', 'controleurs.photo')
                ->first();

            return [
                'vehicule' => [
                    'immatriculation' => $item->immatriculation,
                    'photo' => $item->vehicule_photo ? asset("storage/".$item->vehicule_photo) : null,
                ],
                'chauffeur' => [
                    'nom' => $chauffeur->nom ?? null,
                    'photo' => $chauffeur->photo ? asset("storage/".$chauffeur->photo) : null,
                    'montant' => (float) $item->montant_chauffeur,
                ],
                'controleur' => [
                    'nom' => $controleur->nom ?? null,
                    'photo' => $controleur->photo ? asset("storage/".$controleur->photo) : null,
                    'montant' => (float) $item->montant_controleur,
                ],
                'total' => (float) $item->total,
            ];
        });

        return response()->json($result);
    }


    // Récupérer les versements d'un véhicule particulier
    public function getVersementForVehicle($vehicule_id)
    {
        // Vérifier que le véhicule existe
        $vehicule = Vehicule::findOrFail($vehicule_id);

        // Récupérer les versements groupés par date
        $versements = Versement::select(
            DB::raw('DATE(COALESCE(date_versement, created_at)) as date_versement'),
            DB::raw('SUM(montant) as total')
        )
        ->where('vehicule_id', $vehicule_id)
        ->groupBy(DB::raw('DATE(COALESCE(date_versement, created_at))'))
        ->orderBy(DB::raw('DATE(COALESCE(date_versement, created_at))'), 'desc')
        ->get();

        // Retourner la réponse JSON
        return response()->json($versements);
    }


    // Récupérer les versements du jour
    public function today () {

        $total = Versement::whereDate('created_at', Carbon::today())
            ->sum('montant');

        return response()->json([
            'date' => Carbon::today()->toDateString(),
            'total_versements' => $total
        ]);
    }



    // récupérer le total des versements par chauffeur
    public function totalVersementsParChauffeur() {


        // requête
        $chauffeurs = Chauffeur::select('id', 'nom', 'photo')
        ->withSum('versements as total_versements', 'montant') // calcule la somme
        ->orderByDesc("total_versements")
        ->get()
        ->map(function ($c) {
            // sécurité : s'il n'a jamais versé, on force à 0
            $c->total_versements = (float) ($c->total_versements ?? 0);
            return $c;
        });


        // mapper la fonction pour le storage
        $chauffeurs = $chauffeurs->map(function ($chauffeur){

            $chauffeur->photo = asset("storage/".$chauffeur->photo);

            return $chauffeur;

        });

        return response()->json($chauffeurs);
    }


    // récupérer le total des versements par controleur
    public function totalVersementsParControleur() {


        // requête
        $controleurs = Controleur::select('id', 'nom' , 'photo')
        ->withSum('versements as total_versements', 'montant') // calcule la somme
        ->orderByDesc("total_versements")
        ->get()
        ->map(function ($c) {
            // sécurité : s'il n'a jamais versé, on force à 0
            $c->total_versements = (float) ($c->total_versements ?? 0);
            return $c;
        });


        // mapper la fonction pour le storage
        $chauffeurs = $controleurs->map(function ($controleur){

            $controleur->photo = asset("storage/".$controleur->photo);

            return $controleur;

        });

        return response()->json($controleurs);
    }



    // total des versements par vehicule
    public function totalVersementsParVehicule() {

        // requête
        $vehicules = Vehicule::select('id', 'immatriculation', 'photo')
        ->withSum('versements as total_versements', 'montant') // calcule la somme
        ->orderByDesc("total_versements")
        ->get()
        ->map(function ($v) {
            // sécurité : s'il n'a jamais versé, on force à 0
            $v->total_versements = (float) ($v->total_versements ?? 0);
            return $v;
        });

        // mapper la fonction pour le storage
        $vehicules = $vehicules->map(function ($vehicule){

            $vehicule->photo = asset("storage/".$vehicule->photo);

            return $vehicule;

        });


        // on retourne la réponse
        return response()->json($vehicules);
    }


    // récupérer le total des versements par chauffeur (semaine en cours)
    public function topchauffeurs()
    {
        $startOfWeek = Carbon::now()->startOfWeek(); // lundi
        $endOfWeek   = Carbon::now()->endOfWeek();   // dimanche

        $chauffeurs = Chauffeur::select('id', 'nom', 'photo')
            ->withSum(['versements as total_versements' => function ($query) use ($startOfWeek, $endOfWeek) {
                $query->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
            }], 'montant')
            ->orderBy ("total_versements", "desc")
            ->take(5)
            ->get()
            ->map(function ($c) {
                $c->total_versements = (float) ($c->total_versements ?? 0);
                $c->photo = asset("storage/" . $c->photo);
                return $c;
            });

        return response()->json($chauffeurs);
    }


    // récupérer le total des versements par chauffeur (semaine en cours)
    public function derniers_chauffeurs()
    {
        $startOfWeek = Carbon::now()->startOfWeek(); // lundi
        $endOfWeek   = Carbon::now()->endOfWeek();   // dimanche

        $chauffeurs = Chauffeur::select('id', 'nom', 'photo')
            ->withSum(['versements as total_versements' => function ($query) use ($startOfWeek, $endOfWeek) {
                $query->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
            }], 'montant')
            ->orderBy ("total_versements", "asc")
            ->take(5)
            ->get()
            ->map(function ($c) {
                $c->total_versements = (float) ($c->total_versements ?? 0);
                $c->photo = asset("storage/" . $c->photo);
                return $c;
            });

        return response()->json($chauffeurs);
    }


    // récupérer le total des versements par controleur (semaine en cours)
    public function topcontroleurs()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek   = Carbon::now()->endOfWeek();

        $controleurs = Controleur::select('id', 'nom', 'photo')
            ->withSum(['versements as total_versements' => function ($query) use ($startOfWeek, $endOfWeek) {
                $query->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
            }], 'montant')
            ->orderBy("total_versements", "desc")
            ->take(5)
            ->get()
            ->map(function ($c) {
                $c->total_versements = (float) ($c->total_versements ?? 0);
                $c->photo = asset("storage/" . $c->photo);
                return $c;
            });

        return response()->json($controleurs);
    }


    // récupérer le total des versements par controleur (semaine en cours)
    public function derniers_controleurs()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek   = Carbon::now()->endOfWeek();

        $controleurs = Controleur::select('id', 'nom', 'photo')
            ->withSum(['versements as total_versements' => function ($query) use ($startOfWeek, $endOfWeek) {
                $query->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
            }], 'montant')
            ->orderBy("total_versements", "asc")
            ->take(5)
            ->get()
            ->map(function ($c) {
                $c->total_versements = (float) ($c->total_versements ?? 0);
                $c->photo = asset("storage/" . $c->photo);
                return $c;
            });

        return response()->json($controleurs);
    }


    // récupérer le top 3 des vehicules (semaine en cours)
    public function top_vehicules()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek   = Carbon::now()->endOfWeek();

        $data = DB::table("vehicules")
            ->leftJoin("versements", function ($join) use ($startOfWeek, $endOfWeek) {
                $join->on("versements.vehicule_id", "=", "vehicules.id")
                    ->whereBetween("versements.created_at", [$startOfWeek, $endOfWeek]);
            })
            ->select(
                "vehicules.immatriculation as immatriculation",
                "vehicules.photo",
                DB::raw("COALESCE(SUM(versements.montant), 0) as total")
            )
            ->groupBy("vehicules.id", "vehicules.immatriculation", "vehicules.photo")
            ->orderBy("total", "desc")
            ->take(5)
            ->get()
            ->map(function ($item) {
                $item->photo = $item->photo ? asset('storage/' . $item->photo) : null;
                return $item;
            });

        return response()->json($data);
    }


    // récupérer les 3 derniers vehicules
    public function derniers_vehicules () {

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek   = Carbon::now()->endOfWeek();

        $data = DB::table("vehicules")
            ->leftJoin("versements", function ($join) use ($startOfWeek, $endOfWeek) {
                $join->on("versements.vehicule_id", "=", "vehicules.id")
                    ->whereBetween("versements.created_at", [$startOfWeek, $endOfWeek]);
            })
            ->select(
                "vehicules.immatriculation as immatriculation",
                "vehicules.photo",
                DB::raw("COALESCE(SUM(versements.montant), 0) as total")
            )
            ->groupBy("vehicules.id", "vehicules.immatriculation", "vehicules.photo")
            ->orderBy("total", "asc")
            ->take(5)
            ->get()
            ->map(function ($item) {
                $item->photo = $item->photo ? asset('storage/' . $item->photo) : null;
                return $item;
            });

        return response()->json($data);

    }



    // totaux de chaque mois pour l'année sélectionnée
    public function totaux_mensuels($annee = null)
    {
        // annee
        $annee = $annee ?? Carbon::now()->year;

        // exécution de la requête (mois qui existent en DB)
        $totaux = DB::table("versements")
            ->selectRaw("strftime('%m', created_at) as mois, SUM(montant) as total")
            ->whereRaw("strftime('%Y', created_at) = ?", [(string) $annee])
            ->groupBy("mois")
            ->orderBy("mois")
            ->get()
            ->keyBy("mois"); // pour un accès direct par numéro de mois

        // créer une liste de 12 mois
        $result = collect(range(1, 12))->map(function ($numMois) use ($totaux) {
            $moisStr = str_pad($numMois, 2, "0", STR_PAD_LEFT); // ex: "01"
            $total = $totaux[$moisStr]->total ?? 0;

            return [
                "mois" => ucfirst(Carbon::create()->month($numMois)->locale('fr')->monthName),
                "total" => (float) $total
            ];
        });

        // retour JSON
        return $result;
    }



    // totaux cumulés
    public function totaux_cumules($annee = null)
    {
        $annee = $annee ?? Carbon::now()->year;

        // on récupère les totaux mensuels d'abord
        $totaux = DB::table("versements")
            ->selectRaw("strftime('%m', created_at) as mois, SUM(montant) as total")
            ->whereRaw("strftime('%Y', created_at) = ?", [(string) $annee])
            ->groupBy("mois")
            ->orderBy("mois")
            ->get()
            ->keyBy('mois');

        // construire la liste des 12 mois
        $result = [];
        $cumul = 0;

        for ($i = 1; $i <= 12; $i++) {
            $totalMois = isset($totaux[str_pad($i, 2, '0', STR_PAD_LEFT)]) 
                ? $totaux[str_pad($i, 2, '0', STR_PAD_LEFT)]->total 
                : 0;

            $cumul += $totalMois;

            $result[] = [
                "mois" => ucfirst(Carbon::create()->month($i)->locale('fr')->monthName),
                "cumul" => (float) $cumul
            ];
        }

        return $result;
    }


    // comparaison des objectifs et des réalisations
    public function comparaison_objectifs ($annee=null, $mois=null) {


        // formatage du mois et de l'année
        $annee = $annee ?? Carbon::now()->year;
        $mois = $mois ?? Carbon::now()->month;


        // nombre total de véhicules
        $nb_bus = DB::table("vehicules")->count();
        $objectif_par_bus = Vehicule::OBJECTIF_JOURNALIER;



        // objectif et réalisation journaliers
        $objectif_journalier = $nb_bus * $objectif_par_bus;

        $realise_journalier = DB::table("versements")
        ->whereDate('created_at', Carbon::today())
        ->sum('montant');



        // objectif et réalisation hebdo
        $objectif_hebdo = $objectif_journalier * 7;

        $debut_semaine = Carbon::today()->startOfWeek();
        $fin_semaine = Carbon::today()->endOfWeek();


        $realise_hebdo = DB::table("versements")
        ->whereBetween("created_at", [$debut_semaine, $fin_semaine])
        ->sum('montant');



        // objectif et réalisation mensuel
        $nbre_jours_mois = Carbon::create($annee, $mois)->daysInMonth();
        $objectif_mensuel = $objectif_journalier * $nbre_jours_mois;

        $realise_mensuel = DB::table("versements")
        ->whereYear('created_at', $annee)
        ->whereMonth('created_at', $mois)
        ->sum('montant');




        // reponse json
        return response()->json([
            "journalier" => [
                "objectif" => $objectif_journalier,
                "realise"  => $realise_journalier,
                "ecart"    => $realise_journalier - $objectif_journalier
            ],
            "hebdomadaire" => [
                "objectif" => $objectif_hebdo,
                "realise"  => $realise_hebdo,
                "ecart"    => $realise_hebdo - $objectif_hebdo
            ],
            "mensuel" => [
                "objectif" => $objectif_mensuel,
                "realise"  => $realise_mensuel,
                "ecart"    => $realise_mensuel - $objectif_mensuel
            ]
        ]);

    }


    // comparaison objectif VS réalisation d'un véhicule (avec % d'atteinte)
    public function comparaison_objectifs_vehicule($vehicule_id, $annee = null, $mois = null)
    {
        // formatage du mois et de l'année
        $annee = $annee ?? Carbon::now()->year;
        $mois = $mois ?? Carbon::now()->month;

        
        // Objectif journalier
        $objectif_journalier = Vehicule::OBJECTIF_JOURNALIER;

        $realise_journalier = DB::table("versements")
            ->where("vehicule_id", $vehicule_id)
            ->whereDate('created_at', Carbon::today())
            ->sum('montant');

        $taux_journalier = $objectif_journalier > 0 
            ? round(($realise_journalier / $objectif_journalier) * 100, 2) 
            : 0;

        // Objectif hebdomadaire
        $objectif_hebdo = $objectif_journalier * 7;

        $debut_semaine = Carbon::today()->startOfWeek();
        $fin_semaine = Carbon::today()->endOfWeek();

        $realise_hebdo = DB::table("versements")
            ->where("vehicule_id", $vehicule_id)
            ->whereBetween("created_at", [$debut_semaine, $fin_semaine])
            ->sum('montant');

        $taux_hebdo = $objectif_hebdo > 0 
            ? round(($realise_hebdo / $objectif_hebdo) * 100, 2) 
            : 0;

        // Objectif mensuel
        $nbre_jours_mois = Carbon::create($annee, $mois)->daysInMonth();
        $objectif_mensuel = $objectif_journalier * $nbre_jours_mois;

        $realise_mensuel = DB::table("versements")
            ->where("vehicule_id", $vehicule_id)
            ->whereYear('created_at', $annee)
            ->whereMonth('created_at', $mois)
            ->sum('montant');

        $taux_mensuel = $objectif_mensuel > 0 
            ? round(($realise_mensuel / $objectif_mensuel) * 100, 2) 
            : 0;

        // réponse json
        return response()->json([
            "journalier" => [
                "objectif" => $objectif_journalier,
                "realise"  => $realise_journalier,
                "taux"     => $taux_journalier // %
            ],
            "hebdomadaire" => [
                "objectif" => $objectif_hebdo,
                "realise"  => $realise_hebdo,
                "taux"     => $taux_hebdo // %
            ],
            "mensuel" => [
                "objectif" => $objectif_mensuel,
                "realise"  => $realise_mensuel,
                "taux"     => $taux_mensuel // %
            ]
        ]);
    }



    // stats par bus
    public function stats_par_vehicule()
    {

        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();



        // Jour
        $jour = DB::table("versements")
            ->join("vehicules", "versements.vehicule_id", "=", "vehicules.id")
            ->select("vehicules.immatriculation as immatriculation", "vehicules.photo"  , DB::raw("SUM(versements.montant) as total"))
            ->whereDate("versements.created_at", $today)
            ->groupBy("vehicules.id", "vehicules.immatriculation")
            ->orderBy("total", "desc")
            ->get()
            ->map(function($item) {
                $item->photo = $item->photo ? asset('storage/'.$item->photo) : null;
                return $item;
            });




        // Semaine
        $semaine = DB::table("versements")
            ->join("vehicules", "versements.vehicule_id", "=", "vehicules.id")
            ->select("vehicules.immatriculation as immatriculation",   "vehicules.photo as photo" , DB::raw("SUM(versements.montant) as total"))
            ->whereBetween("versements.created_at", [$startOfWeek, $endOfWeek])
            ->groupBy("vehicules.id", "vehicules.immatriculation")
            ->orderBy("total", "desc")
            ->get()
            ->map(function($item) {
                $item->photo = $item->photo ? asset('storage/'.$item->photo) : null;
                return $item;
            });




        // Mois
        $mois = DB::table("versements")
            ->join("vehicules", "versements.vehicule_id", "=", "vehicules.id")
            ->select("vehicules.immatriculation as immatriculation", "vehicules.photo as photo", DB::raw("SUM(versements.montant) as total"))
            ->whereBetween("versements.created_at", [$startOfMonth, $endOfMonth])
            ->groupBy("vehicules.id", "vehicules.immatriculation")
            ->orderBy("total", "desc")
            ->get()
            ->map(function($item) {
                $item->photo = $item->photo ? asset('storage/'.$item->photo) : null;
                return $item;
            });



        
        return response()->json([
            "jour" => $jour,
            "semaine" => $semaine,
            "mois" => $mois
        ]);
    }


    // evolution des versements
    public function evolution($annee = null)
    {
        $annee = $annee ?? date("Y");

        $data = Versement::selectRaw("CAST(strftime('%m', created_at) AS INTEGER) as mois, SUM(montant) as total")
            ->whereRaw("strftime('%Y', created_at) = ?", [$annee])
            ->groupBy("mois")
            ->orderBy("mois")
            ->get();

        // normaliser sur 12 mois
        $mois = collect(range(1, 12))->map(function($m) use ($data) {
            $val = $data->firstWhere("mois", $m);
            return [
                "mois" => $m,
                "total" => $val ? $val->total : 0
            ];
        });

        return response()->json([
            "annee" => $annee,
            "evolution" => $mois
        ]);
    }



     // Evolution des versements sur les 7 derniers jours
    public function evolution_derniers_jours()
    {
        $startDate = Carbon::now()->subDays(6)->startOfDay(); // il y a 6 jours
        $endDate   = Carbon::now()->endOfDay();               // aujourd'hui

        $versements = DB::table('versements')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(montant) as total')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        // on complète les jours manquants avec total=0
        $data = collect();
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->subDays(6 - $i)->format('Y-m-d');
            $total = $versements->firstWhere('date', $date)->total ?? 0;
            $data->push([
                'date' => $date,
                'total' => (float) $total
            ]);
        }

        return response()->json($data);
    }



    // total des versement d'un vehicule pour le mois courant
    public function total_versement_vehicule_mois_actuel($vehicule_id) {

        
        // on vérifie si le vehicule existe
        $vehicule = Vehicule::findOrFail($vehicule_id);

        // récupération du mois et de l'annee
        $mois = Carbon::now()->month;
        $annee = Carbon::now()->year;


        // Calculer le total des versements pour ce véhicule sur le mois courant
        $total = Versement::where('vehicule_id', $vehicule_id)
            ->whereYear('created_at', $annee)
            ->whereMonth('created_at', $mois)
            ->sum('montant');



        // on renvoie les data
        return response()->json($total);

    }



    // total des versements d'un chauffeur pour le mois courant
    public function total_versement_chauffeur($chauffeur_id) {


        // on vérifie que le chauffeur existe
        $chauffeur = Chauffeur::findOrFail($chauffeur_id);

        // récupération du mois et de l'annee
        $mois = Carbon::now()->month;
        $annee = Carbon::now()->year;


        // Calculer le total des versements pour ce véhicule sur le mois courant
        $total = Versement::where('employe_id', $chauffeur_id)
            ->where("role", "chauffeur")
            ->whereYear('created_at', $annee)
            ->whereMonth('created_at', $mois)
            ->sum('montant');



        // on renvoie les data
        return response()->json($total);


    }


    // total des versements d'un controleur pour le mois courant
    public function total_versement_controleur ($controleur_id) {

        
        // vérification
        $controleur = Controleur::findOrFail($controleur_id);

        // récupération du mois et de l'annee
        $mois = Carbon::now()->month;
        $annee = Carbon::now()->year;


        // Calculer le total des versements pour ce véhicule sur le mois courant
        $total = Versement::where('employe_id', $controleur_id)
            ->where("role", "controleur")
            ->whereYear('created_at', $annee)
            ->whereMonth('created_at', $mois)
            ->sum('montant');



        // on renvoie les data
        return response()->json($total);


    }



    // historique de versements d'un chauffeur particulier
    public function historique_chauffeur($chauffeur_id) {
        
        // vérification
        $chauffeur = Chauffeur::findOrFail($chauffeur_id);


        $data = Versement::where("employe_id", $chauffeur_id)
        ->where("role", "chauffeur")
        ->orderBy("date_versement", "desc")
        ->get();



        // on retourne les données
        return response()->json($data);


    }



    // historique de versements d'un controleur particulier
    public function historique_controleur($controleur_id) {

        // verification
        $controleur = Controleur::findOrFail($controleur_id);

        
        $data = Versement::where("employe_id", $controleur_id)
        ->where("role", "controleur")
        ->orderBy("date_versement", "desc")
        ->get();


        return response()->json($data);

    }


    // evolution des versements d'un chauffeur sur une année
    public function evolution_versements_chauffeur($chauffeur_id, $annee = null)
    {
        $annee = $annee ?? date("Y");

        $data = Versement::selectRaw("CAST(strftime('%m', created_at) AS INTEGER) as mois, SUM(montant) as total")
            ->where("employe_id", $chauffeur_id)
            ->where("role", "chauffeur")
            ->whereRaw("strftime('%Y', created_at) = ?", [$annee])
            ->groupBy("mois")
            ->orderBy("mois")
            ->get();

        // normaliser sur 12 mois
        $mois = collect(range(1, 12))->map(function($m) use ($data) {
            $val = $data->firstWhere("mois", $m);
            return [
                "mois" => $m,
                "total" => $val ? (int) $val->total : 0
            ];
        });

        return response()->json($mois);

    }



    // evolution des versements d'un controleur sur une année
    public function evolution_versements_controleur($controleur_id, $annee=null) {

        $annee = $annee ?? date("Y");

        $data = Versement::selectRaw("CAST(strftime('%m', created_at) AS INTEGER) as mois, SUM(montant) as total")
            ->where("employe_id", $controleur_id)
            ->where("role", "controleur")
            ->whereRaw("strftime('%Y', created_at) = ?", [$annee])
            ->groupBy("mois")
            ->orderBy("mois")
            ->get();

        // normaliser sur 12 mois
        $mois = collect(range(1, 12))->map(function($m) use ($data) {
            $val = $data->firstWhere("mois", $m);
            return [
                "mois" => $m,
                "total" => $val ? (int) $val->total : 0
            ];
        });

        return response()->json($mois);


    }


    // totaux mensuels pour un véhicule donné
    public function totaux_mensuels_par_vehicule($vehicule_id, $annee = null)
    {
        // année courante si non fournie
        $annee = $annee ?? Carbon::now()->year;

        // exécution de la requête (mois qui existent en DB)
        $totaux = DB::table("versements")
            ->selectRaw("strftime('%m', created_at) as mois, SUM(montant) as total")
            ->where("vehicule_id", $vehicule_id)
            ->whereRaw("strftime('%Y', created_at) = ?", [(string) $annee])
            ->groupBy("mois")
            ->orderBy("mois")
            ->get()
            ->keyBy("mois");

        // créer une liste de 12 mois
        $result = collect(range(1, 12))->map(function ($numMois) use ($totaux) {
            $moisStr = str_pad($numMois, 2, "0", STR_PAD_LEFT); // ex: "01"
            $total = $totaux[$moisStr]->total ?? 0;

            return [
                "mois" => ucfirst(Carbon::create()->month($numMois)->locale('fr')->monthName),
                "total" => (float) $total
            ];
        });

        // retour JSON
        return $result;
    }



    // totaux cumulés pour un véhicule donné
    public function totaux_cumules_par_vehicule($vehicule_id, $annee = null)
    {
        $annee = $annee ?? Carbon::now()->year;

        // on récupère les totaux mensuels d'abord
        $totaux = DB::table("versements")
            ->selectRaw("strftime('%m', created_at) as mois, SUM(montant) as total")
            ->where("vehicule_id", $vehicule_id)
            ->whereRaw("strftime('%Y', created_at) = ?", [(string) $annee])
            ->groupBy("mois")
            ->orderBy("mois")
            ->get()
            ->keyBy('mois');

        // construire la liste des 12 mois
        $result = [];
        $cumul = 0;

        for ($i = 1; $i <= 12; $i++) {
            $totalMois = isset($totaux[str_pad($i, 2, '0', STR_PAD_LEFT)]) 
                ? $totaux[str_pad($i, 2, '0', STR_PAD_LEFT)]->total 
                : 0;

            $cumul += $totalMois;

            $result[] = [
                "mois" => ucfirst(Carbon::create()->month($i)->locale('fr')->monthName),
                "cumul" => (float) $cumul
            ];
        }

        return $result;
    }


    // Total journalier pour les chauffeurs
    public function total_journalier_chauffeurs()
    {
        $total = Versement::where('role', 'chauffeur')
            ->whereDate('created_at', Carbon::today())
            ->sum('montant');

        return response()->json(['total' => $total]);
    }

    // Total journalier pour les contrôleurs
    public function total_journalier_controleurs()
    {
        $total = Versement::where('role', 'controleur')
            ->whereDate('created_at', Carbon::today())
            ->sum('montant');

        return response()->json(['total' => $total]);
    }

    // Total de la semaine pour les chauffeurs
    public function total_semaine_chauffeurs()
    {
        $total = Versement::where('role', 'chauffeur')
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('montant');

        return response()->json(['total' => $total]);
    }

    // Total de la semaine pour les contrôleurs
    public function total_semaine_controleurs()
    {
        $total = Versement::where('role', 'controleur')
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('montant');

        return response()->json(['total' => $total]);
    }

    // Total mensuel pour les chauffeurs
    public function total_mensuel_chauffeurs()
    {
        $total = Versement::where('role', 'chauffeur')
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('montant');

        return response()->json(['total' => $total]);
    }

    // Total mensuel pour les contrôleurs
    public function total_mensuel_controleurs()
    {
        $total = Versement::where('role', 'controleur')
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('montant');

        return response()->json(['total' => $total]);
    }
    


}
