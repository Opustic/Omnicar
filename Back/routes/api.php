<?php

use App\Http\Controllers\Api\ArretController;
use App\Http\Controllers\Api\ChauffeurController;
use App\Http\Controllers\Api\ControleurController;
use App\Http\Controllers\Api\DepenseController;
use App\Http\Controllers\Api\EmployeController;
use App\Http\Controllers\Api\EquipeController;
use App\Http\Controllers\Api\MecanicienController;
use App\Http\Controllers\Api\ReparationController;
use App\Http\Controllers\Api\VehiculeController;
use App\Http\Controllers\Api\VersementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




// Groupe de Routes pour les équipes
Route::prefix("/equipe")-> group(function(){

    // Liste de toutes les équipes
    Route::get("/", [EquipeController::class, "index"]);


    // Récupérer une équipe en particulier
    Route::get("/{id}", [EquipeController::class, "getEquipe"]);


    // Ajouter une équipe
    Route::post("/create", [EquipeController::class, "store"]);


    // Modifier une équipe
    Route::put("/{id}", [EquipeController::class, "update"]);


    // Assigner un véhicule à une équipe
    Route::put("/{id}/assigner/{vehiculeid}", [EquipeController::class, "assigner"]);


    // créer équipe à assigner
    Route::post("/{vehicule_id}/create", [EquipeController::class, "createToAssign"]);

});



// Groupe des routes pour les chauffeurs
Route::prefix("/chauffeur")->group(function(){

    // récupérer tous les chauffeurs
    Route::get("/", [ChauffeurController::class, 'index']);


    // ajouter un nouveau chauffeur 
    Route::post ("/create", [ChauffeurController::class, "store"]);


    // modifier les informations d'un chauffeur
    Route::put("/{id}/edit", [ChauffeurController::class, "edit"]);


    // suppression
    Route::delete("/{id}/delete", [ChauffeurController::class, "destroy"]);
    
});



// Groupe de routes pour les controleurs
Route::prefix("/controleur")->group(function(){


    // Pour récupérer tous les controleurs
    Route::get("/", [ControleurController::class, "index"]);



    // Pour insérer un controleur
    Route::post("/create", [ControleurController::class, "store"]);

    

    //Pour modifier les informations d'un controleur
    Route::put("/{id}/edit", [ControleurController::class, "edit"]);


    // suppression
    Route::delete("/{id}/delete", [ControleurController::class, "destroy"]);

});



// groupe de routes pour les mécaniciens
Route::prefix("/mecanicien")->group(function(){

    // récupérer tous les mécaniciens
    Route::get("/", [MecanicienController::class, "index"]);
    
    
    // ajouter un nouveau mécanicien
    Route::post("/create", [MecanicienController::class, "store"]);


    // récupérer un mécanicien par son id
    Route::get("/{id}", [MecanicienController::class, "getMecanicien"]);
    

    // modifier les informations d'un mécanicien
    Route::put("/{id}", [MecanicienController::class, "update"]);


    // virer un mécanicien
    Route::delete("/{id}/delete", [MecanicienController::class, "destroy"]);
    
});





// Groupe de routes pour les vehicules
Route::prefix("/vehicule")->group(function(){


    // Récupérer les vehicules
    Route::get("/", [VehiculeController::class, "index"]);


    
    // récupérer un véhicule particulier
    Route::get("/{id}", [VehiculeController::class, "getVehicule"]);



    // ajouter un nouveau vehicule
    Route::post ("/create", [VehiculeController::class, "store"]);


    // Modifier un vehicule
    Route::put("/{id}", [VehiculeController::class, "update"]);


    // Changer le statut du vehicule
    Route::put("{id}/changerStatut", [VehiculeController::class, "changerStatut"]);


});


// groupe de routes pour les employés
Route::prefix("/employe")->group(function(){


    // récupérer tous les employés
    Route::get("/", [EmployeController::class, "index"]);


    // récupérer un employé particulier
    Route::get("/{id}", [EmployeController::class, "getEmploye"]);

});



// groupe de routes pour les réparations
Route::prefix("/reparation")->group(function(){

    // récupérer les réparations d'un vehicule particulier
    Route::get("/{id}", [ReparationController::class,"getPanneByVehicule"]);


    // total par motif
    Route::get("/{id}/total-par-motif", [ReparationController::class, "total_par_motif"]);


    // ajouter une nouvelle réparation
    Route::post("/{id}", [ReparationController::class,"store"]);


    // modifier le statut d'une réparation
    Route::put ("/{reparation_id}/update", [ReparationController::class, "updateRepairStatus"]);

});



// groupe de routes pour les mises à arrêt
Route::prefix("/arret")->group(function(){

    // tous les arrêts par vehicule
    Route::get("/{id}", [ArretController::class, "getArretByVehicule"]);

    // total par motif
    Route::get("/{id}/total-par-motif", [ArretController::class, "total_par_motif"]);


    // émettre un arrêt
    Route::post("/{id}", [ArretController::class,"store"]);
    
});



// groupe de routes pour les versements
Route::prefix("/versement")->group(function (){


    // non défini encore
    Route::get("/", [VersementController::class, "index"]);


    // modifier un versement
    Route::put("/{id}", [VersementController::class, "update"]);

    // faire un nouveau versement
    Route::post("{vehicule_id}/{chauffeur_id}/{controleur_id}/insert", [VersementController::class, "insert"]);


    // somme des versements du vehicule durant le mois actuel
    Route::get("/{vehicule_id}/total-versement", [VersementController::class, "total_versement_vehicule_mois_actuel"]);



    // historique de versements d'un chauffeur particulier
    Route::get("/{chauffeur_id}/historique-chauffeur", [VersementController::class, "historique_chauffeur"]);


    // evolution des versement sur l'année d'un chauffeur
    Route::get("/{chauffeur_id}/evolution-chauffeur/{annee?}", [VersementController::class, "evolution_versements_chauffeur"]);


    // evolution des versements sur l'année d'un controleur
    Route::get("/{controleur_id}/evolution-controleur/{annee?}", [VersementController::class, "evolution_versements_controleur"]);


    // historique des versements d'un controleur particulier
    Route::get("/{controleur_id}/historique-controleur", [VersementController::class, "historique_controleur"]);

    
    // somme des versements du vehicule pour un chauffeur (mois courant)
    Route::get("/{chauffeur_id}/total-versement-chauffeur", [VersementController::class, "total_versement_chauffeur"]);


    // somme des versements du vehicule pour un controleur (mois courant)
    Route::get("/{controleur_id}/total-versement-controleur", [VersementController::class, "total_versement_controleur"]);

    // versements du jour
    Route::get("/todays-versements", [VersementController::class, "todays_versements"]);

    
    // récupérer les versements d'un vehicule particulier
    Route::get("/{vehicule_id}/getVersementForVehicle", [VersementController::class, "getVersementForVehicle"]);
    
    
    // top 5 des chauffeurs (ASC & DESC)
    Route::get("/topchauffeurs", [VersementController::class, "topchauffeurs"]);
    Route::get("/derniers-chauffeurs", [VersementController::class, "derniers_chauffeurs"]);
    
    

    // top 5 des controleurs (ASC & DESC)
    Route::get("/topcontroleurs", [VersementController::class, "topcontroleurs"]);
    Route::get("/derniers-controleurs", [VersementController::class, "derniers_controleurs"]);
    


    // top 5 des vehicules (ASC & DESC)
    Route::get("/top-vehicules", [VersementController::class, "top_vehicules"]);
    Route::get("/derniers-vehicules", [VersementController::class, "derniers_vehicules"]);


    
    // total des versements par chauffeur
    Route::get("/totalVersementsParChauffeur", [VersementController::class, "totalVersementsParChauffeur"]);
    
    
    // total des versements par controleur
    Route::get("/totalVersementsParControleur", [VersementController::class, "totalVersementsParControleur"]);
    
    // total des versemnents par véhicule
    Route::get("/totalVersementsParVehicule", [VersementController::class, "totalVersementsParVehicule"]);
    
    // récupérer le total des versements du jour
    Route::get("/today", [VersementController::class, "today"]);
    

    // récupérer les totaux mensuels de tout le parc
    Route::get("/totaux-mensuels/{annee?}", [VersementController::class, "totaux_mensuels"]);


    // les cumuls de tout le parc
    Route::get("/totaux-cumules/{annee?}", [VersementController::class, "totaux_cumules"]);



    // récupérer les totaux mensuels pour un véhicule particulier
    Route::get("/vehicule/{vehicule_id}/totaux-mensuels/{annee?}", [VersementController::class, "totaux_mensuels_par_vehicule"]);

    // récupérer les cumuls pour un véhicule particulier
    Route::get("/vehicule/{vehicule_id}/totaux-cumules/{annee?}", [VersementController::class, "totaux_cumules_par_vehicule"]);



    // objectifs
    Route::get("/objectifs/{annee?}/{mois?}", [VersementController::class, "comparaison_objectifs"]);
    

    // objectifs d'un vehicule
    Route::get("/vehicule/{vehicule_id}/objectifs/{annee?}/{mois?}", [VersementController::class, "comparaison_objectifs_vehicule"]);

    
    // stats par bus
    Route::get("/stats-par-vehicule", [VersementController::class, "stats_par_vehicule"]);


    // évolution des versements sur l'année
    Route::get("/evolution/{annee?}", [VersementController::class, "evolution"]);



    // évolution des versements sur les 7 derniers jours
    Route::get("/evolution-derniers-jours", [VersementController::class, "evolution_derniers_jours"]);


    // total journalier pour les chauffeurs
    Route::get("/total-journalier-chauffeurs", [VersementController::class, "total_journalier_chauffeurs"]);


    // total journalier pour les controleurs
    Route::get("/total-journalier-controleurs", [VersementController::class, "total_journalier_controleurs"]);


    // total de la semaine pour les chauffeurs
    Route::get("/total-semaine-chauffeurs", [VersementController::class, "total_semaine_chauffeurs"]);


    // total de la semaine pour les controleurs
    Route::get("/total-semaine-controleurs", [VersementController::class, "total_semaine_controleurs"]);


    // total mensuel pour les chauffeurs
    Route::get("/total-mensuel-chauffeurs", [VersementController::class, "total_mensuel_chauffeurs"]);


    // total mensuel pour les controleurs
    Route::get("/total-mensuel-controleurs", [VersementController::class, "total_mensuel_controleurs"]);


    
    

});



// groupe de routes pour les dépenses
Route::prefix("/depense")->group(function () {

    // récupération de toutes les dépenses
    Route::get("/todaysDepenses", [DepenseController::class, "todaysDepenses"]);

    // récupération des dépenses du mois courant
    Route::get("/thisMonth", [DepenseController::class, "thisMonth"]);

    // récupérer les dépenses de chaque mois par catégorie
    Route::get("/stats/byCategory/{year}/{month}", [DepenseController::class, "totalByCategoryAndMonth"]);


    // récupération du total des dépenses du jour
    Route::get("/today", [DepenseController::class, "totalToday"]);


    // comparaison mensuelle
    Route::get("/monthly-comparison/{year?}", [DepenseController::class, "monthlyComparison"]);


    // evolution sur les 7 derniers jours
    Route::get("/evolution/derniers-jours", [DepenseController::class, "derniers_jours"]);


    // total annuel par catégorie
    Route::get("/total-annuel/{year?}", [DepenseController::class, "total_annuel_par_categorie"]);


    // historique des salaires d'un employé particulier
    Route::get("/historique-salaire/{employe_id}", [DepenseController::class, "historique_salaire"]);


    // faire une nouvelle dépense
    Route::post("/create", [DepenseController::class, "store"]);



});