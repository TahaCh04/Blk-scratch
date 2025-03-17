@extends('layouts.master')

@section('title', 'Ajouter un Incident')

@section('content')

<style>
/* CSS intégré directement dans la vue Blade */

/* Palette de couleurs */
:root {
    --primary-color: #277AAF;
    --primary-color-dark: #216897;
    --body-bg-color: #f5f5f5;
    --form-bg-color: #ffffff;
    --text-color: #333;
    --border-color: #ccc;
}

/* Style global */
body {
    background-color: var(--body-bg-color);
    font-family: sans-serif;
    margin: 0;
    padding: 0;
}

/* Style du container */
.container {
    max-width: 600px;
    margin: 2rem auto;
    background-color: var(--form-bg-color);
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

/* Titre du formulaire */
.container h2 {
    text-align: center;
    color: var(--primary-color);
    margin-bottom: 1.5rem;
}

/* Label du formulaire */
.container label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: bold;
    color: var(--text-color);
}

/* Champs de formulaire */
.container input[type="text"],
.container select {
    width: 100%;
    padding: 0.6rem;
    margin-bottom: 1.2rem;
    border: 1px solid var(--border-color);
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 1rem;
}

/* Effet lors de la sélection des champs */
.container input[type="text"]:focus,
.container select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(39,122,175,0.3);
    outline: none;
}

/* Bouton de soumission */
.container button {
    display: block;
    width: 100%;
    padding: 0.7rem;
    background-color: var(--primary-color);
    color: #fff;
    border: none;
    border-radius: 4px;
    font-size: 1rem;
    cursor: pointer;
    transition: background-color 0.3s;
}

/* Effet bouton hover et focus */
.container button:hover,
.container button:focus {
    background-color: var(--primary-color-dark);
}

/* Responsive mobile */
@media (max-width: 600px) {
    .container {
        width: 90%;
        padding: 1.2rem;
    }
}
</style>

<div class="container">
    <h2>Ajouter un Incident</h2>
    <form action="{{ route('incidents.store') }}" method="POST">
        @csrf
        <label>Description :</label>
        <input type="text" name="description" placeholder="Décrivez l'incident..." required>

        <label>Statut :</label>
        <select name="status" required>
            <option value="en attente">En attente</option>
            <option value="résolu">Résolu</option>
        </select>

        <button type="submit">Ajouter</button>
    </form>
</div>

@endsection
