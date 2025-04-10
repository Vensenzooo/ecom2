<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Afficher la page de profil de l'utilisateur
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Mettre à jour les informations du profil
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Accéder à l'utilisateur via le modèle User pour s'assurer d'avoir les méthodes Eloquent
        $userModel = User::find($user->id);
        $userModel->name = $validated['name'];
        $userModel->save();

        return redirect()->route('profile.show')
            ->with('success', 'Votre profil a été mis à jour avec succès.');
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Mettre à jour le mot de passe
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('Le mot de passe actuel est incorrect.');
                }
            }],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Accéder à l'utilisateur via le modèle User pour s'assurer d'avoir les méthodes Eloquent
        $userModel = User::find($user->id);
        $userModel->password = Hash::make($validated['password']);
        $userModel->save();

        return redirect()->route('profile.show')
            ->with('success', 'Votre mot de passe a été modifié avec succès.');
    }

    /**
     * Mettre à jour les préférences de thème
     */
    public function updateTheme(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'theme' => 'required|string|in:light,dark,auto',
        ]);

        // Stocker la préférence de thème dans la session
        session(['theme' => $validated['theme']]);

        // Si vous avez une table pour les préférences utilisateur, vous pouvez l'utiliser ici
        // UserPreference::updateOrCreate(['user_id' => $user->id], ['theme' => $validated['theme']]);

        return redirect()->route('profile.show')
            ->with('success', 'Votre thème d\'affichage a été modifié avec succès.');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
