<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class GoogleAuthController extends Controller
{
    /**
     * Redirige l'utilisateur vers Google pour l'authentification.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Gère le callback de Google après l'authentification.
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Rechercher un utilisateur existant avec ce mail
            $user = User::where('email', $googleUser->getEmail())->first();

            // S'il n'existe pas, le créer
            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                ]);
            }

            // Connecter l'utilisateur
            Auth::login($user);

            // Rediriger vers l'accueil ou dashboard
            return redirect('/');

        } catch (\Exception $e) {
            // Gestion des erreurs : redirection ou log
            return redirect('/login')->with('error', 'Erreur lors de la connexion avec Google.');
        }
    }
}
