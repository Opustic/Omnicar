<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employe;
use Illuminate\Http\Request;

class EmployeController extends Controller
{
    //

    public function index () {


        $employes = Employe::with(["chauffeurs", "controleurs", "mecaniciens"])->get();


        // on retourne les employés
        return response()->json($employes);
    }


    // récupérer un employé particulier
    public function getEmploye($id) {   

        $employe = Employe::where("id", $id)->with(["chauffeurs", "controleurs", "mecaniciens"]);


        // on retourne l'employé
        return response()->json($employe);

    }

}
