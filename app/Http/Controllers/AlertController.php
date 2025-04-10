<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlertController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alerts = Alert::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('alerts.index', compact('alerts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Assurons-nous de récupérer l'ID de l'utilisateur
        $userId = $request->user_id;
        $user = User::findOrFail($userId);
        
        return view('alerts.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,danger',
        ]);
        
        $validated['created_by'] = Auth::id();
        
        Alert::create($validated);
        
        return redirect()->route('users.show', $validated['user_id'])
            ->with('success', 'Alerte envoyée avec succès');
    }

    /**
     * Mark alert as read
     */
    public function markAsRead(Alert $alert)
    {
        // Vérifier que l'utilisateur connecté est bien le destinataire de l'alerte
        if (Auth::id() != $alert->user_id) {
            abort(403);
        }
        
        $alert->update(['read_at' => now()]);
        
        return redirect()->back()->with('success', 'Alerte marquée comme lue');
    }

    /**
     * Mark all alerts as read
     */
    public function markAllAsRead()
    {
        Alert::where('user_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
            
        return redirect()->back()->with('success', 'Toutes les alertes ont été marquées comme lues');
    }
}
