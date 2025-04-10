<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlertController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the user explicitly using the User model
        $user = User::find(Auth::id());
        
        // Get all alerts
        $alerts = $user->alerts()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get unread alerts separately
        $unreadAlerts = $user->alerts()
            ->whereNull('read_at')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Determine if we are in admin mode
        $isAdmin = $user->roles()->where('nom', 'admin')->exists();
        $isEditor = $user->roles()->where('nom', 'editeur')->exists();
        $isManager = $user->roles()->where('nom', 'gestionnaire')->exists();
        
        if ($isAdmin || $isEditor || $isManager) {
            // Admin/Editor/Manager view
            return view('alerts.admin.index', compact('alerts', 'unreadAlerts'));
        } else {
            // Client view
            return view('alerts.index', compact('alerts', 'unreadAlerts'));
        }
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
        // Vérifier que l'utilisateur actuel est bien le destinataire de l'alerte
        if ($alert->user_id !== Auth::id()) {
            abort(403, "Vous n'êtes pas autorisé à effectuer cette action");
        }
        
        $alert->update(['read_at' => now()]);
        
        return redirect()->route('client.alerts.index')
            ->with('success', 'Alerte marquée comme lue');
    }

    /**
     * Mark all alerts as read
     */
    public function markAllAsRead()
    {
        $user = User::find(Auth::id());
        $user->alerts()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        return redirect()->route('client.alerts.index')
            ->with('success', 'Toutes les alertes ont été marquées comme lues');
    }
}
