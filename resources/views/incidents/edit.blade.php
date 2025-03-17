@extends('layouts.master')

@section('title', 'Modifier un Incident')

@section('content')
<style>
    :root {
        --primary-color: #216897;
        --primary-color-dark: #1a537a;
        --body-bg-color: #f5f5f5;
        --form-bg-color: #ffffff;
        --text-color: #333;
        --border-color: #ccc;
    }

    body {
        background-color: var(--body-bg-color);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .container {
        max-width: 600px;
        margin: 50px auto;
        background-color: var(--form-bg-color);
        padding: 30px;
        border-radius: 6px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .container h2 {
        text-align: center;
        color: var(--text-color);
        margin-bottom: 1.5rem;
    }

    .container label {
        font-weight: 600;
        color: var(--text-color);
    }

    .container input[type="text"],
    .container select {
        width: 100%;
        padding: 0.7rem;
        margin-bottom: 1rem;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        font-size: 1rem;
        box-sizing: border-box;
    }

    .container button {
        width: 100%;
        padding: 0.8rem;
        background-color: #216897;
        color: #ffffff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1rem;
        transition: background-color 0.3s;
    }

    .container button:hover {
        background-color: #1a5276;
    }

    label {
        font-weight: 600;
        color: var(--text-color);
    }
</style>


<div class="container">
    <h2>Modifier l'Incident</h2>
    <form action="{{ route('incidents.update', $incident) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Description :</label>
        <input type="text" name="description" value="{{ $incident->description }}" required>

        <label>Status :</label>
        <select name="status" required>
            <option value="en attente" {{ $incident->status == 'en attente' ? 'selected' : '' }}>En attente</option>
            <option value="résolu" {{ $incident->status == 'résolu' ? 'selected' : '' }}>Résolu</option>
        </select>

        <button type="submit">Mettre à Jour</button>
    </form>
</div>
@endsection
