<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incident;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $total_incendies = Incident::count();
        $incendies_resolus = Incident::where('status', 'résolu')->count();
        $total_users = User::count();

        // Récupérer les incidents par mois et convertir en tableau
        $incendies_par_mois = Incident::selectRaw('MONTH(created_at) as mois, COUNT(*) as total')
            ->groupBy('mois')
            ->orderBy('mois')
            ->pluck('total', 'mois')
            ->toArray();  // ✅ Convertir en tableau

        // Générer les noms des mois correspondants et les convertir en tableau
        $mois_labels = [];
        foreach ($incendies_par_mois as $mois => $total) {
            $mois_labels[$mois] = Carbon::create()->month($mois)->translatedFormat('F');
        }

        return view('dashboard', [
            'total_incendies' => $total_incendies,
            'incendies_resolus' => $incendies_resolus,
            'total_users' => $total_users,
            'incendies_par_mois' => array_values($incendies_par_mois), // ✅ Conversion correcte
            'mois_labels' => array_values($mois_labels), // ✅ Conversion correcte
        ]);
    }
}
