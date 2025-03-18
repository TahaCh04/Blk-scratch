<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incident;

class IncidentController extends Controller
{
    public function index()
{
    $user = auth()->user();

    if ($user->role === 'admin') {
        $incidents = Incident::paginate(10); // Admin voit tous les incidents
    } else {
        $incidents = Incident::where('user_id', $user->id)->paginate(10); // User voit seulement ses propres incidents
    }

    return view('incidents.index', compact('incidents'));
}



    public function create()
    {
        return view('incidents.create');
    }

    public function store(Request $request)
{
    $request->validate([
        'description' => 'required|string|max:255',
        'status' => 'required|in:en attente,résolu',
    ]);

    $incident = new Incident();
    $incident->description = $request->description;
    $incident->status = $request->status;
    $incident->user_id = auth()->id(); // Ajout automatique de l'utilisateur connecté
    $incident->save();

    return redirect()->route('incidents.index')->with('success', 'Incident ajouté.');
}


public function edit(Incident $incident)
{
    if (auth()->user()->role !== 'admin' && $incident->user_id !== auth()->id()) {
        return redirect()->route('incidents.index')->with('error', 'Accès refusé.');
    }
    return view('incidents.edit', compact('incident'));
}

public function update(Request $request, Incident $incident)
{
    if (auth()->user()->role !== 'admin' && $incident->user_id !== auth()->id()) {
        return redirect()->route('incidents.index')->with('error', 'Accès refusé.');
    }

    $incident->update($request->all());
    return redirect()->route('incidents.index')->with('success', 'Incident mis à jour.');
}

public function destroy(Incident $incident)
{
    // Si l'utilisateur est admin, il peut tout supprimer
    if (auth()->user()->role === 'admin') {
        $incident->delete();
        return redirect()->route('incidents.index')->with('success', 'Incident supprimé.');
    }

    // Si l'utilisateur est user, il ne peut supprimer que ses propres incidents
    if (auth()->user()->role === 'user' && $incident->user_id === auth()->id()) {
        $incident->delete();
        return redirect()->route('incidents.index')->with('success', 'Votre incident a été supprimé.');
    }

    // Si l'utilisateur tente de supprimer un incident qui ne lui appartient pas
    return redirect()->route('dashboard')->with('error', 'Vous n\'avez pas la permission de supprimer cet incident.');
}


}
