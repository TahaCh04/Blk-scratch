@extends('layouts.master')

@section('title', 'Gestion des Incendies')

@section('content')
<style>
    body {
        font-family: 'Times New Roman', serif;
        background-color: #f8f9fa;
    }
    header {
        background: white;
        padding: 20px;
        border-bottom: 2px solid #ddd;
        display: flex;
        align-items: center;
        justify-content: space-between;
        max-width: 1200px;
        margin: auto;
        position: relative;
        flex-wrap: wrap;
    }
    .header-logo {
        display: flex;
        align-items: center;
    }
    .header-logo img {
        height: 50px;
        margin-right: 15px;
    }
    .header-title {
        font-size: 22px;
        font-weight: bold;
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        text-align: center;
        color: #033b7a;
        font-family: 'Georgia', serif;
        letter-spacing: 1px;
        white-space: nowrap;
    }
    .logout-btn {
        background: #b30000;
        color: white;
        padding: 8px 15px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
        white-space: nowrap;
    }
    .logout-btn:hover {
        background: #800000;
    }
    .nav-header {
        background: #003d80;
        color: white;
        display: flex;
        justify-content: space-around;
        padding: 7px 0;
    }
    .nav-header a {
        color: white;
        text-decoration: none;
        font-size: 16px;
        font-weight: bold;
        padding: 8px 15px;
    }
    .nav-header a:hover {
        background: #002146;
        border-radius: 5px;
    }
    .container {
        max-width: 1000px;
        margin: auto;
        padding: 20px;
    }
    h2 {
        text-align: center;
        color: #003d80;
        margin-bottom: 20px;
    }
    .btn-container {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 15px;
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
        cursor: pointer;
    }
    .btn-edit:hover {
        background: #002146;
    }
    .btn-delete:hover {
        background: #800000;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background: white;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }
    .table th, .table td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: center;
    }
    .table th {
        background: #003d80;
        color: white;
    }
    .table td {
        background: #f9f9f9;
    }
    .actions {
        display: flex;
        justify-content: center;
        gap: 10px;
    }
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
        justify-content: center;
        align-items: center;
    }
    .modal-content {
        background-color: white;
        padding: 25px;
        border-radius: 10px;
        width: 400px;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        position: relative;
    }
    .close {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 24px;
        cursor: pointer;
        color: red;
        font-weight: bold;
    }
    .close:hover {
        color: darkred;
    }
    .modal label {
        display: flex;
        align-items: center;
        font-weight: bold;
        margin: 10px 0 5px;
    }
    .modal label i {
        margin-right: 8px;
    }
    .modal textarea, .modal select {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }
    .btn-save {
        background: #003d80;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        margin-top: 10px;
        cursor: pointer;
    }
    .btn-save:hover {
        background: #002146;
    }
    @media (max-width: 768px) {
        header {
            flex-direction: row;
            justify-content: space-between;
            padding: 10px;
        }
        .header-title {
            display: none;
        }
        .header-logo img {
            height: 40px;
        }
        .logout-btn {
            font-size: 14px;
            padding: 5px 10px;
        }
        .nav-header {
            padding: 10px;
        }
        .nav-header a {
            padding: 8px 0;
        }
    }
</style>

<header>
    <div class="header-logo">
        <img src="{{ asset('images/logo1.svg') }}" alt="Logo">
    </div>
    <span class="header-title">Système de Déclaration d'Incendies</span>
    <a href="{{ route('logout') }}" class="logout-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Déconnexion</a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</header>

<nav class="nav-header">
    <a href="{{ route('dashboard') }}">Tableau de Bord</a>
    <a href="{{ route('incidents.index') }}">Gestion des Incendies</a>
    <a href="#">Utilisateurs</a>
    <a href="#">Paramètres</a>
</nav>

<div class="container">
    <h2>Liste des Incendies</h2>
    <form method="GET" action="{{ route('incidents.index') }}">
        <input type="text" name="search" placeholder="Rechercher un incendie..." value="{{ request('search') }}" style="padding: 8px; border-radius: 5px; width: 300px; margin-bottom: 10px;">
        <button type="submit" class="btn btn-edit">Rechercher</button>
    </form>
    <div class="btn-container">
        <button class="btn btn-edit" onclick="openModal()">Ajouter un Incident</button>
    </div>
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
            @forelse ($incidents as $incident)
            <tr>
                <td>{{ $incident->id }}</td>
                <td>{{ $incident->description }}</td>
                <td>
                    <span style="color: {{ $incident->status == 'résolu' ? 'green' : 'red' }};">
                        {{ ucfirst($incident->status) }}
                    </span>
                </td>
                <td class="actions">
                    @if(auth()->user()->role === 'admin' || auth()->id() === $incident->user_id)
                        <button class="btn btn-edit" onclick="openEditModal({{ $incident->id }}, '{{ $incident->description }}', '{{ $incident->status }}')">Modifier</button>
                            <form action="{{ route('incidents.destroy', $incident) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cet incident ?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete">Supprimer</button>
                            </form>
                    @endif
                </td>
               
                
            </tr>
            @empty
            <tr>
                <td colspan="4">Aucun incident trouvé.</td>
            </tr>
            @endforelse
        </tbody>
        
    </table>
    {{ $incidents->links() }}
</div>


<!-- Modale d'ajout -->
<div id="incidentModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('incidentModal')">&times;</span>
        <h3>Ajouter un Incident</h3>
        <form action="{{ route('incidents.store') }}" method="POST">
            @csrf
            <label><i class="fas fa-align-left"></i>Description:</label>
            <textarea name="description" required></textarea>
            <label><i class="fas fa-flag"></i>Statut:</label>
            <select name="status">
                <option value="en attente">En attente</option>
                <option value="résolu">Résolu</option>
            </select>
            <button type="submit" class="btn-save" onclick="closeModal('incidentModal')">Enregistrer</button>
        </form>
    </div>
</div>

<!-- Modale de modification -->
<div id="editIncidentModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('editIncidentModal')">&times;</span>
        <h3>Modifier l'Incident</h3>
        <form id="editIncidentForm" method="POST">
            @csrf
            @method('PUT')
            <label><i class="fas fa-align-left"></i>Description:</label>
            <textarea name="description" id="editDescription" required></textarea>
            <label><i class="fas fa-flag"></i>Statut:</label>
            <select name="status" id="editStatus">
                <option value="en attente">En attente</option>
                <option value="résolu">Résolu</option>
            </select>
            <button type="submit" class="btn-save" onclick="closeModal('editIncidentModal')">Mettre à jour</button>
        </form>
    </div>
</div>

<script>
    function openModal() {
        document.getElementById('incidentModal').style.display = 'flex';
    }
    function openEditModal(id, description, status) {
        document.getElementById('editIncidentForm').action = `/incidents/${id}`;
        document.getElementById('editDescription').value = description;
        document.getElementById('editStatus').value = status;
        document.getElementById('editIncidentModal').style.display = 'flex';
    }
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }
</script>
@endsection
