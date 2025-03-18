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
    $total_users = User::count();

    // Vérifier si l'utilisateur est admin
    $isAdmin = auth()->user()->role === 'admin';

    // Si utilisateur normal, il ne voit que ses propres incidents
    $query_signalés = Incident::query();
    $query_resolus = Incident::where('status', 'résolu');
    $query_en_attente = Incident::where('status', 'en attente');

    if (!$isAdmin) {
        $query_signalés->where('user_id', auth()->id());
        $query_resolus->where('user_id', auth()->id());
        $query_en_attente->where('user_id', auth()->id());
    }

    // Comptage des incendies
    $total_incendies = $query_signalés->count();
    $incendies_resolus = $query_resolus->count();
    $incendies_en_attente = $query_en_attente->count();

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

    if ($year !== 'all') {
        $query_signalés->whereYear('created_at', $year);
        $query_resolus->whereYear('created_at', $year);
        $query_en_attente->whereYear('created_at', $year);
    }

    if ($time === 'current_month') {
        $query_signalés->whereMonth('created_at', Carbon::now()->month);
        $query_resolus->whereMonth('created_at', Carbon::now()->month);
        $query_en_attente->whereMonth('created_at', Carbon::now()->month);
    } elseif ($time === 'last_6_months') {
        $query_signalés->whereBetween('created_at', [Carbon::now()->subMonths(6), Carbon::now()]);
        $query_resolus->whereBetween('created_at', [Carbon::now()->subMonths(6), Carbon::now()]);
        $query_en_attente->whereBetween('created_at', [Carbon::now()->subMonths(6), Carbon::now()]);
    }

    // Initialiser les tableaux avec des valeurs à zéro pour tous les mois
    $incendies_par_mois = array_fill(0, 12, 0);
    $incendies_resolus_par_mois = array_fill(0, 12, 0);
    $incendies_en_attente_par_mois = array_fill(0, 12, 0);

    // Récupération des statistiques
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

    $incidents_en_attente = $query_en_attente->selectRaw('MONTH(created_at) as mois, COUNT(*) as total')
        ->groupBy('mois')
        ->orderBy('mois', 'asc')
        ->pluck('total', 'mois')
        ->toArray();

    // Remplissage des tableaux
    foreach ($incidents_signalés as $mois => $total) {
        $incendies_par_mois[$mois - 1] = $total;
    }

    foreach ($incidents_resolus as $mois => $total) {
        $incendies_resolus_par_mois[$mois - 1] = $total;
    }

    foreach ($incidents_en_attente as $mois => $total) {
        $incendies_en_attente_par_mois[$mois - 1] = $total;
    }

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
        'incendies_en_attente',
        'total_users',
        'incendies_par_mois',
        'incendies_resolus_par_mois',
        'incendies_en_attente_par_mois',
        'available_years',
        'mois_labels'
    ));
}

}
