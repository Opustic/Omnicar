<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Depense;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class DepenseController extends Controller
{


    // récupérer les dépenses du jour
    public function todaysDepenses () {

        $depenses = Depense::whereDate("created_at", Carbon::today())
        ->get();

        return response()->json($depenses);

    }

    // récupérer les dépenses du mois courant
    public function thisMonth () {

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $depenses = Depense::whereBetween("created_at", [$startOfMonth, $endOfMonth])
        ->get();

        return response()->json($depenses);
    }


    // le total d'aujourd'hui
    public function totalToday () {
        
        $total = Depense::whereDate("created_at", Carbon::today())->sum('montant');

        return response()->json([
            'date' => Carbon::today()->toDateString(),
            'total_depenses' => $total
        ]);

    }


    // le total des dépenses par mois et par catégorie
    public function totalByCategoryAndMonth($year, $month)
    {
        $stats = Depense::select("categorie", DB::raw("SUM(montant) as total"))
            ->whereYear("created_at", $year)
            ->whereMonth("created_at", $month)
            ->groupBy("categorie")
            ->get();

        return response()->json($stats);
    }


    // comparaison annuelle et mensuelle
    public function monthlyComparison($year = null)
    {
        $year = $year ?? Carbon::now()->year;
        $lastYear = $year - 1;

        $months = range(1, 12);

        // Totaux pour l'année $year
        $totalsThisYear = Depense::select(DB::raw("strftime('%m', created_at) as month"), DB::raw("SUM(montant) as total"))
            ->whereYear('created_at', $year)
            ->groupBy(DB::raw("strftime('%m', created_at)"))
            ->pluck('total', 'month');

        // Totaux pour l'année $lastYear
        $totalsLastYear = Depense::select(DB::raw("strftime('%m', created_at) as month"), DB::raw("SUM(montant) as total"))
            ->whereYear('created_at', $lastYear)
            ->groupBy(DB::raw("strftime('%m', created_at)"))
            ->pluck('total', 'month');

        // On s'assure que chaque mois a une valeur
        $dataThisYear = [];
        $dataLastYear = [];

        foreach ($months as $month) {
            // Convertir le mois en chaîne avec deux chiffres (ex. "01", "02") pour correspondre à strftime
            $monthKey = sprintf("%02d", $month);
            $dataThisYear[] = $totalsThisYear[$monthKey] ?? 0;
            $dataLastYear[] = $totalsLastYear[$monthKey] ?? 0;
        }

        return response()->json([
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'thisYear' => $dataThisYear,
            'lastYear' => $dataLastYear
        ]);
    }


    // evolution des dépenses sur les 7 derniers jours
    public function derniers_jours() {

        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate   = Carbon::now()->endOfDay();

        $depenses = DB::table('depenses')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(montant) as total')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy(DB::  raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        $data = collect();
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->subDays(6 - $i)->format('Y-m-d');
            $total = $depenses->firstWhere('date', $date)->total ?? 0;
            $data->push([
                'date' => $date,
                'total' => (float) $total
            ]);
        }

        return response()->json($data);

    }



    // total annuel par catégorie
    public function total_annuel_par_categorie($year=null) {
        
        $year = $year ?? Carbon::now()->year;

        $stats = Depense::select("categorie", DB::raw("SUM(montant) as total"))
            ->whereYear("created_at", $year)
            ->groupBy("categorie")
            ->get();

        return response()->json($stats);

    }

    // ajouter une nouvelle dépense
    public function store(Request $request)
    {
        $data = $request->validate([
            "categorie"   => "required|string",
            "vehicule_id" => "nullable|exists:vehicules,id",
            "employe_id"  => "nullable|exists:employes,id",
            "montant"     => "required|integer|min:0"
        ]);

        // Vérification spéciale pour les salaires
        if (strtolower($data['categorie']) === 'salaire') {
            if (empty($data['employe_id'])) {
                return response()->json([
                    "message" => "Un salaire doit être associé à un employé."
                ], 422);
            }

            $dernierSalaire = Depense::where('categorie', 'salaire')
                ->where('employe_id', $data['employe_id'])
                ->where('created_at', '>=', now()->subDays(15))
                ->first();

            if ($dernierSalaire) {
                return response()->json([
                    "message" => "Cet employé a déjà perçu un salaire au cours des 15 derniers jours."
                ], 422);
            }
        }

        // Création de la dépense
        $depense = Depense::create($data);

        // Retourne la dépense
        return $depense;
    }


    // récupérer l'historique des salaires d'un employé
    public function historique_salaire($employe_id) {  
        $salaire_history = Depense::where("employe_id", $employe_id)
            ->where("categorie", "salaire")
            ->orderBy("created_at", "desc")
            ->get();

        return response()->json($salaire_history);  
    }

}
