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
    header {
        background: white;
        padding: 10px;
        border-bottom: 2px solid #ddd;
        display: flex;
        align-items: center;
        justify-content: space-between;
        max-width: 1200px;
        margin: auto;
        position: relative;
    }
    .header-logo {
        display: flex;
        align-items: center;
    }
    .header-logo img {
        height: 60px;
        margin-right: 15px;
    }
    .header-title {
        font-size: 26px;
        font-weight: bold;
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        text-align: center;
        width: max-content;
        color: #033b7a;
        font-family: 'Georgia', serif;
        letter-spacing: 1px;
    }
    .dashboard-container {
        max-width: 1000px;
        margin: auto;
        padding: 20px;
    }
    .header {
        background: rgb(252, 238, 211);
        padding: 10px;
        border-bottom: 2px solid #ddd;
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        position: relative;
    }
    .filters {
        margin-top: 10px;
        display: flex;
        justify-content: space-between;
    }
    .filters select {
        padding: 10px;
        font-size: 16px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }
    .chart-container {
        margin-top: 15px;
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
    }
    canvas {
        width: 100% !important;
        height: 400px !important;
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
    .logout-btn {
        background: #b30000;
        color: white;
        padding: 8px 15px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: bold;
    }
    .logout-btn:hover {
        background: #800000;
    }
    .stats-container {
         display: flex;
         justify-content: space-between;
         margin-top: 20px;
     }
     .stat-box {
         background: white;
         padding: 10px;
         border-radius: 8px;
         box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
         text-align: center;
         width: 30%;
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
    <a href="#">Exporter</a>
    <a href="#">Paramètres</a>
</nav>
<div class="dashboard-container">
    <div class="header">
        Bienvenue, {{ auth()->user()->name }} 
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
    <!-- Filtres -->
    <div class="filters">
        <select id="yearFilter">
            <option value="all">Toutes les années</option>
            @foreach ($available_years as $year)
                <option value="{{ $year }}">{{ $year }}</option>
            @endforeach
        </select>

        <select id="timeFilter">
            <option value="all">Toutes les périodes</option>
            <option value="current_month">Mois en cours</option>
            <option value="last_6_months">6 derniers mois</option>
        </select>

        <select id="statusFilter" onchange="applyFilters()">
            <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>Tous les statuts</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
            <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Résolus</option>
        </select>
        
    </div>

    <!-- Graphique -->
    <div class="chart-container">
        <canvas id="incendiesChart"></canvas>
    </div>
</div>

<!-- Import Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var labels = {!! json_encode($mois_labels) !!};
        var signalésData = {!! json_encode($incendies_par_mois) !!};
        var résolusData = {!! json_encode($incendies_resolus_par_mois) !!};
        var enAttenteData = {!! json_encode($incendies_en_attente_par_mois) !!};

        var ctx = document.getElementById('incendiesChart').getContext('2d');
        var incendiesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Incendies Signalés',
                        data: signalésData,
                        backgroundColor: 'rgba(0, 61, 128, 0.7)',
                        borderColor: '#002146',
                        borderWidth: 1,
                        hoverBackgroundColor: 'rgba(0, 61, 128, 1)',
                    },
                    {
                        label: 'Incendies en Attente',
                        data: enAttenteData,
                        backgroundColor: 'rgba(255, 0, 0, 0.7)',
                        borderColor: '#B22222',
                        borderWidth: 1,
                        hoverBackgroundColor: 'rgba(255, 0, 0, 1)',
                    },
                    {
                        label: 'Incendies Résolus',
                        data: résolusData,
                        backgroundColor: 'rgba(40, 167, 69, 0.7)',
                        borderColor: '#1E7E34',
                        borderWidth: 1,
                        hoverBackgroundColor: 'rgba(40, 167, 69, 1)',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1500,
                    easing: 'easeOutBounce'
                },
                plugins: {
                    tooltip: {
                        enabled: true,
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.dataset.label + ': ' + tooltipItem.raw;
                            }
                        }
                    },
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Gestion des filtres
        document.getElementById("yearFilter").addEventListener("change", function() {
            applyFilters();
        });
        document.getElementById("timeFilter").addEventListener("change", function() {
            applyFilters();
        });
        document.getElementById("statusFilter").addEventListener("change", function() {
            applyFilters();
        });

        function applyFilters() {
    let year = document.getElementById("yearFilter").value;
    let time = document.getElementById("timeFilter").value;
    let status = document.getElementById("statusFilter").value;

    // Si "Tous les statuts" est sélectionné, on force "all" dans l'URL
    let url = `?year=${year}&time=${time}`;
    
    if (status !== "all") {
        url += `&status=${status}`;
    }

    window.location.href = url;
}


    });
</script>
@endsection
