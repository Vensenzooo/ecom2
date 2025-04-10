<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Vérifier si le compte est restreint avant l'authentification
        $user = User::where('email', $request->email)->first();
        
        if ($user && $user->is_restricted) {
            // Stocker la raison de restriction dans la session
            session(['restriction_reason' => $user->restriction_reason]);
            session(['restricted_at' => $user->restricted_at ? $user->restricted_at->format('d/m/Y H:i') : 'Inconnue']);
            
            // Rediriger vers la page de restriction sans authentifier
            return redirect()->route('restricted.account');
        }

        $request->authenticate();

        $request->session()->regenerate();

        // Utiliser la nouvelle méthode pour déterminer où rediriger
        return redirect()->intended(RouteServiceProvider::redirectTo($request, $request->user()));
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
