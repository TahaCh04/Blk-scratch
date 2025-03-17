@extends('layouts.master')

@section('title', 'Tableau de Bord')

@section('content')
<style>
    body {
        font-family: 'Times New Roman', serif;
        background-color: #f8f9fa;
        margin: 0;
        padding: 0;
    }
    .dashboard-container {
        max-width: 1000px;
        margin: auto;
        padding: 20px;
    }
    .header {
        background: #003d80;
        color: white;
        padding: 15px;
        text-align: center;
        border-radius: 8px;
        font-size: 24px;
    }
    .stats-container {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }
    .stat-box {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        text-align: center;
        width: 30%;
    }
    .chart-container {
        margin-top: 30px;
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }
    .btn-logout {
        display: block;
        margin-top: 20px;
        padding: 10px 20px;
        background: #b30000;
        color: white;
        text-align: center;
        text-decoration: none;
        border-radius: 6px;
        font-size: 16px;
        font-weight: bold;
    }
    .btn-logout:hover {
        background: #800000;
    }
</style>

<div class="dashboard-container">
    <div class="header">
        Bienvenue, {{ auth()->user()->name }} 👋
    </div>

    <!-- Statistiques -->
    <div class="stats-container">
        <div class="stat-box">
            <h3>Incendies Signalés</h3>
            <p><strong>{{ $total_incendies }}</strong></p>
        </div>
        <div class="stat-box">
            <h3>Incendies Résolus</h3>
            <p><strong>{{ $incendies_resolus }}</strong></p>
        </div>
        <div class="stat-box">
            <h3>Utilisateurs Inscrits</h3>
            <p><strong>{{ $total_users }}</strong></p>
        </div>
    </div>

    <!-- Graphique -->
    <div class="chart-container">
        <canvas id="incendiesChart"></canvas>
    </div>

    <!-- Déconnexion -->
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn-logout">Se Déconnecter</button>
    </form>
</div>

<!-- Import Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('incendiesChart').getContext('2d');
    var incendiesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_values($mois_labels)) !!},
            datasets: [{
                label: 'Incendies Signalés',
                data: {!! json_encode(array_values($incendies_par_mois)) !!},
                backgroundColor: '#003d80',
                borderColor: '#002146',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endsection
