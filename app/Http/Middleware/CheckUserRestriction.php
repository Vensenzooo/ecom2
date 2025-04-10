<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRestriction
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->is_restricted) {
            // Stocker la raison de restriction dans la session
            session(['restriction_reason' => Auth::user()->restriction_reason]);
            session(['restricted_at' => Auth::user()->restricted_at ? Auth::user()->restricted_at->format('d/m/Y H:i') : 'Inconnue']);
            
            // Déconnecter l'utilisateur
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            // Rediriger vers la page de restriction
            return redirect()->route('restricted.account');
        }

        return $next($request);
    }
}
