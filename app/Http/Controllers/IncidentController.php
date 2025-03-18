<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incident;

class IncidentController extends Controller
{
    public function index(Request $request)
{
    $query = Incident::query();

    if ($request->has('search')) {
        $query->where('description', 'LIKE', "%{$request->search}%");
    }

    $incidents = $query->paginate(10); // ✅ Remplace get() par paginate(10)

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

        Incident::create($request->all());

        return redirect()->route('incidents.index')->with('success', 'Incident ajouté avec succès.');
    }

    public function edit(Incident $incident)
    {
        return view('incidents.edit', compact('incident'));
    }

    public function update(Request $request, Incident $incident)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'status' => 'required|in:en attente,résolu',
        ]);

        $incident->update($request->all());

        return redirect()->route('incidents.index')->with('success', 'Incident mis à jour.');
    }

    public function destroy(Incident $incident)
    {
        $incident->delete();

        return redirect()->route('incidents.index')->with('success', 'Incident supprimé.');
    }
}
