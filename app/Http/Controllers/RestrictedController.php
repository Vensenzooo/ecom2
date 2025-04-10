<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RestrictedController extends Controller
{
    /**
     * Afficher la page de compte restreint
     */
    public function index()
    {
        // Récupérer les informations de restriction de la session
        $restrictionReason = session('restriction_reason') ?? 'Violation des conditions d\'utilisation';
        $restrictedAt = session('restricted_at') ?? 'Date inconnue';
        
        return view('auth.restricted', compact('restrictionReason', 'restrictedAt'));
    }
}
