<?php

namespace App\Http\Controllers;

use App\Models\GiftList;
use App\Models\Book;
use App\Models\FriendInvitation;
use App\Mail\GiftListInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class GiftListController extends Controller
{
    /**
     * Afficher les listes de cadeaux de l'utilisateur
     */
    public function index()
    {
        $giftLists = GiftList::where('user_id', Auth::id())
            ->withCount('items')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('gift-lists.index', compact('giftLists'));
    }

    /**
     * Afficher le formulaire de création
     */
    public function create()
    {
        return view('gift-lists.create');
    }

    /**
     * Enregistrer une nouvelle liste
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_evenement' => 'nullable|date',
        ]);
        
        $validated['user_id'] = Auth::id();
        $validated['code_partage'] = Str::random(10);
        
        $giftList = GiftList::create($validated);
        
        return redirect()->route('client.gift-lists.show', $giftList)
            ->with('success', 'Liste de cadeaux créée avec succès');
    }

    /**
     * Afficher une liste de cadeaux
     */
    public function show(GiftList $giftList)
    {
        // Vérifier manuellement si l'utilisateur est autorisé
        if (Auth::id() !== $giftList->user_id) {
            abort(403, 'Vous n\'êtes pas autorisé à voir cette liste');
        }
        
        $giftList->load(['items.book', 'items.reserver', 'invitations']);
        
        return view('gift-lists.show', compact('giftList'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(GiftList $giftList)
    {
        // Vérifier manuellement si l'utilisateur est autorisé
        if (Auth::id() !== $giftList->user_id) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette liste');
        }
        
        return view('gift-lists.edit', compact('giftList'));
    }

    /**
     * Mettre à jour une liste
     */
    public function update(Request $request, GiftList $giftList)
    {
        // Vérifier manuellement si l'utilisateur est autorisé
        if (Auth::id() !== $giftList->user_id) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette liste');
        }
        
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_evenement' => 'nullable|date',
            'active' => 'boolean',
        ]);
        
        $giftList->update($validated);
        
        return redirect()->route('client.gift-lists.show', $giftList)
            ->with('success', 'Liste de cadeaux mise à jour avec succès');
    }

    /**
     * Supprimer une liste
     */
    public function destroy(GiftList $giftList)
    {
        // Vérifier manuellement si l'utilisateur est autorisé
        if (Auth::id() !== $giftList->user_id) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer cette liste');
        }
        
        $giftList->delete();
        
        return redirect()->route('client.gift-lists.index')
            ->with('success', 'Liste de cadeaux supprimée avec succès');
    }

    /**
     * Ajouter un livre à la liste
     */
    public function addBook(Request $request, GiftList $giftList)
    {
        // Vérifier manuellement si l'utilisateur est autorisé
        if (Auth::id() !== $giftList->user_id) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette liste');
        }
        
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantite' => 'required|integer|min:1',
        ]);
        
        // Vérifier si le livre est déjà dans la liste
        $existingItem = $giftList->items()->where('book_id', $validated['book_id'])->first();
        
        if ($existingItem) {
            $existingItem->update([
                'quantite' => $existingItem->quantite + $validated['quantite'],
            ]);
        } else {
            $giftList->items()->create($validated);
        }
        
        return redirect()->route('client.gift-lists.show', $giftList)
            ->with('success', 'Livre ajouté à la liste avec succès');
    }

    /**
     * Supprimer un livre de la liste
     */
    public function removeBook(GiftList $giftList, $itemId)
    {
        // Vérifier manuellement si l'utilisateur est autorisé
        if (Auth::id() !== $giftList->user_id) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette liste');
        }
        
        $item = $giftList->items()->findOrFail($itemId);
        $item->delete();
        
        return redirect()->route('client.gift-lists.show', $giftList)
            ->with('success', 'Livre retiré de la liste avec succès');
    }

    /**
     * Inviter des amis à voir la liste
     */
    public function inviteFriends(Request $request, GiftList $giftList)
    {
        // Vérifier manuellement si l'utilisateur est autorisé
        if (Auth::id() !== $giftList->user_id) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette liste');
        }
        
        $validated = $request->validate([
            'emails' => 'required|string',
            'message' => 'nullable|string',
        ]);
        
        $emails = array_map('trim', explode(',', $validated['emails']));
        $message = $validated['message'];
        
        $user = Auth::user();
        
        foreach ($emails as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                // Créer l'invitation
                $invitation = $giftList->invitations()->create([
                    'email' => $email,
                    'token' => Str::random(32),
                ]);
                
                // Envoyer l'email - corrigé pour utiliser un cast explicite ou vérification que l'utilisateur est bien un User
                if ($user instanceof \App\Models\User) {
                    Mail::to($email)->send(new GiftListInvitation($giftList, $invitation, $user, $message));
                }
                
                // Marquer comme envoyé
                $invitation->update(['sent_at' => now()]);
            }
        }
        
        return redirect()->route('client.gift-lists.show', $giftList)
            ->with('success', 'Invitations envoyées avec succès');
    }

    /**
     * Affiche une liste de cadeaux partagée
     */
    public function shared($code)
    {
        $giftList = GiftList::where('code_partage', $code)
            ->where('active', true)
            ->firstOrFail();
            
        $giftList->load(['items.book', 'user']);
        
        return view('gift-lists.shared', compact('giftList'));
    }

    /**
     * Réserver un article
     */
    public function reserveItem(Request $request, $code, $itemId)
    {
        $giftList = GiftList::where('code_partage', $code)
            ->where('active', true)
            ->firstOrFail();
            
        $item = $giftList->items()->findOrFail($itemId);
        
        // Vérifier si l'article n'est pas déjà réservé
        if ($item->reserve) {
            return back()->with('error', 'Cet article est déjà réservé');
        }
        
        // Si l'utilisateur est connecté, enregistrer qui a réservé
        $reservedBy = Auth::check() ? Auth::id() : null;
        
        $item->update([
            'reserve' => true,
            'reserved_by' => $reservedBy,
        ]);
        
        return back()->with('success', 'Article réservé avec succès');
    }
}
