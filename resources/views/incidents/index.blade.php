@extends('layouts.master')

@section('title', 'Gestion des Incendies')

@section('content')
<style>
    .container {
        max-width: 900px;
        margin: auto;
        padding: 20px;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    .table th, .table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }
    .btn {
        padding: 8px 12px;
        text-decoration: none;
        border-radius: 5px;
        font-size: 14px;
        font-weight: bold;
    }
    .btn-edit {
        background: #003d80;
        color: white;
    }
    .btn-delete {
        background: #b30000;
        color: white;
        border: none;
    }
</style>

<div class="container">
    <h2>Liste des Incendies</h2>
    <a href="{{ route('incidents.create') }}" class="btn btn-edit">Ajouter un Incendie</a>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Description</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($incidents as $incident)
            <tr>
                <td>{{ $incident->id }}</td>
                <td>{{ $incident->description }}</td>
                <td>{{ ucfirst($incident->status) }}</td>
                <td>
                    <a href="{{ route('incidents.edit', $incident) }}" class="btn btn-edit">Modifier</a>
                    <form action="{{ route('incidents.destroy', $incident) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete">Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
