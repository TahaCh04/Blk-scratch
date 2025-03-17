@extends('layouts.master')

@section('title', 'Accueil')

@section('content')
<style>
    body, html {
        font-family: 'Times New Roman', serif;
        background-color: #f8f9fa;
        color: #333;
        margin: 0;
        padding: 0;
        height: 100%;
        overflow: hidden;
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
    .main-section {
        background: url('https://www.sqli.com/sites/default/files/styles/xxl_agencies_image/public/2021-11/SQLI%20Rabat.jpg?h=c673cd1c&itok=CEmCIDfi') center center / cover no-repeat;
        color: white;
        text-align: center;
        padding: 0 20px;
        height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        padding-top: 15vh;
        width: 100%;
    }
    .main-section h2 {
        font-size: 32px;
        font-weight: bold;
        text-transform: uppercase;
        text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3);
    }
    .main-section p.lead {
        font-size: 18px;
        max-width: 700px;
        line-height: 1.5;
        margin-top: 10px;
    }
    .btn-container {
        margin-top: 20px;
        display: flex;
        gap: 15px;
    }
    .btn {
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 6px;
        font-size: 16px;
        display: inline-block;
        font-weight: bold;
        transition: all 0.3s ease-in-out;
    }
    .btn-primary {
        background: #003d80;
        color: white;
        border: none;
        box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2);
    }
    .btn-primary:hover {
        background: #002146;
        transform: scale(1.05);
    }
    .btn-outline {
        background: #eaeaea;
        color: #333;
        border: 1px solid #ccc;
        box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2);
    }
    .btn-outline:hover {
        background: #d6d6d6;
        transform: scale(1.05);
    }
    footer {
        background: #002146;
        color: white;
        text-align: center;
        padding: 20px 0;
        position: absolute;
        width: 100%;
        bottom: 0;
    }
</style>

<header>
    <div class="header-logo">
        <img src="{{ asset('images/logo1.svg') }}" alt="Logo">
    </div>
    <span class="header-title">Système de Déclaration d'Incendies</span>
</header>

<main class="main-section">
    <h2 class="mb-4">Bienvenue sur la Plateforme de Déclaration des Incendies</h2>
    <p class="lead">Signalez rapidement les incendies et contribuez à une intervention efficace en temps réel. Ensemble, aidons à protéger notre environnement.</p>
    <div class="btn-container">
        <a href="{{ route('login.show') }}" class="btn btn-primary">Se connecter</a>
        <a href="{{ route('register.show') }}" class="btn btn-outline">S'inscrire</a>
    </div>
</main>

<footer>
    <p>&copy; {{ date('Y') }} Système de Déclaration d'Incendies - Tous droits réservés.</p>
</footer>
@endsection
