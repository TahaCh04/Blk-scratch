<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incident;
use App\Models\User;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $total_incendies = Incident::count();
        $incendies_resolus = Incident::where('status', 'résolu')->count();
        $incendies_en_attente = Incident::where('status', 'en attente');
        $total_users = User::count();

        // Années disponibles pour les filtres
        $available_years = Incident::selectRaw('YEAR(created_at) as year')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        // Labels des mois
        $mois_labels = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];

        // Filtres
        $year = $request->input('year', Carbon::now()->year);
        $time = $request->input('time', 'all');
        $status = $request->input('status', 'all');

        $query_signalés = Incident::query();
        $query_resolus = Incident::where('status', 'résolu');

        if ($year !== 'all') {
            $query_signalés->whereYear('created_at', $year);
            $query_resolus->whereYear('created_at', $year);
        }

        if ($time === 'current_month') {
            $query_signalés->whereMonth('created_at', Carbon::now()->month);
            $query_resolus->whereMonth('created_at', Carbon::now()->month);
        } elseif ($time === 'last_6_months') {
            $query_signalés->whereBetween('created_at', [Carbon::now()->subMonths(6), Carbon::now()]);
            $query_resolus->whereBetween('created_at', [Carbon::now()->subMonths(6), Carbon::now()]);
        }
        if ($status === 'all') {
            // Ne pas filtrer les incidents et afficher tout
            $incendies_par_mois = Incident::selectRaw('MONTH(created_at) as mois, COUNT(*) as total')
                ->groupBy('mois')
                ->orderBy('mois')
                ->pluck('total', 'mois')
                ->toArray();
        
            $incendies_resolus_par_mois = Incident::where('status', 'résolu')
                ->selectRaw('MONTH(created_at) as mois, COUNT(*) as total')
                ->groupBy('mois')
                ->orderBy('mois')
                ->pluck('total', 'mois')
                ->toArray();
        
            $incendies_en_attente_par_mois = Incident::where('status', 'en attente')
                ->selectRaw('MONTH(created_at) as mois, COUNT(*) as total')
                ->groupBy('mois')
                ->orderBy('mois')
                ->pluck('total', 'mois')
                ->toArray();
        }
        
        // Générer les statistiques après filtres
        $incendies_par_mois = array_fill(0, 12, 0);
        $incendies_resolus_par_mois = array_fill(0, 12, 0);
        $incendies_en_attente_par_mois = array_fill(0, 12, 0);

        $incidents_signalés = $query_signalés->selectRaw('MONTH(created_at) as mois, COUNT(*) as total')
            ->groupBy('mois')
            ->orderBy('mois', 'asc')
            ->pluck('total', 'mois')
            ->toArray();

        $incidents_resolus = $query_resolus->selectRaw('MONTH(created_at) as mois, COUNT(*) as total')
            ->groupBy('mois')
            ->orderBy('mois', 'asc')
            ->pluck('total', 'mois')
            ->toArray();
        
        $incendies_en_attente = $incendies_en_attente->selectRaw('MONTH(created_at) as mois, COUNT(*) as total')
        ->groupBy('mois')
        ->orderBy('mois', 'asc')
        ->pluck('total', 'mois')
        ->toArray();
    
        foreach ($incidents_signalés as $mois => $total) {
            $incendies_par_mois[$mois - 1] = $total;
        }

        foreach ($incidents_resolus as $mois => $total) {
            $incendies_resolus_par_mois[$mois - 1] = $total;
        }

        foreach ($incendies_en_attente as $mois => $total) {
            $incendies_en_attente_par_mois[$mois - 1] = $total;
        }  
        // Vérifier si on affiche uniquement les résolus
        if ($status === 'resolved') {
            $incendies_par_mois = array_fill(0, 12, 0); // Cache les incidents signalés
            $incendies_en_attente_par_mois = array_fill(0, 12, 0); // Cache les incidents en attente
        } elseif ($status === 'pending') {
            $incendies_par_mois = array_fill(0, 12, 0); // Cache les incidents signalés
            $incendies_resolus_par_mois = array_fill(0, 12, 0); // Cache les incidents résolus
        } elseif ($status === 'all') {
            // Affiche tous les incidents sans modifications
        }

        return view('dashboard', compact(
            'total_incendies', 
            'incendies_resolus', 
            'total_users', 
            'incendies_par_mois', 
            'incendies_resolus_par_mois', 
            'incendies_en_attente_par_mois',
            'available_years', 
            'mois_labels'));
    }
}
