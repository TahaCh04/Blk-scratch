@extends('layouts.master')

@section('title', 'Inscription')

@section('content')
<style>
    body {
        font-family: 'Times New Roman', serif;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }
    .container {
        background: white;
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
        text-align: center;
    }
    h2 {
        color: #003d80;
        font-size: 24px;
        margin-bottom: 20px;
    }
    form {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    label {
        font-weight: bold;
    }
    input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
    }
    button {
        width: 100%;
        padding: 10px;
        background: #003d80;
        color: white;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    button:hover {
        background: #002146;
    }
    .alert {
        background: #ffcccc;
        color: #b30000;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 10px;
    }
</style>

<div class="container">
    <h2>Inscription</h2>
    @if ($errors->any())
        <div class="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('register') }}" method="POST">
        @csrf
        <label>Nom :</label>
        <input type="text" name="name" required>
        <label>Email :</label>
        <input type="email" name="email" required>
        <label>Mot de passe :</label>
        <input type="password" name="password" required>
        <label>Confirmer le mot de passe :</label>
        <input type="password" name="password_confirmation" required>
        <button type="submit">S'inscrire</button>
    </form>
    <a href="{{ route('login.show') }}">Déjà inscrit ? Se connecter</a>
</div>
@endsection
