<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login.show');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            Alert::success('Connexion réussie !', 'Bienvenue sur votre tableau de bord.');
            return redirect()->route('dashboard');
        }

        Alert::error('Erreur', 'Identifiants incorrects.');
        return back();
    }
}
