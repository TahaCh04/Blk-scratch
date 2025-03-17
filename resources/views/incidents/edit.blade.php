@extends('layouts.master')

@section('title', 'Modifier un Incident')

@section('content')
<div class="container">
    <h2>Modifier l'Incident</h2>
    <form action="{{ route('incidents.update', $incident) }}" method="POST">
        @csrf
        @method('PUT')
        <label>Description :</label>
        <input type="text" name="description" value="{{ $incident->description }}" required>
        <label>Statut :</label>
        <select name="status" required>
            <option value="en attente" {{ $incident->status == 'en attente' ? 'selected' : '' }}>En attente</option>
            <option value="résolu" {{ $incident->status == 'résolu' ? 'selected' : '' }}>Résolu</option>
        </select>
        <button type="submit">Mettre à Jour</button>
    </form>
</div>
@endsection
