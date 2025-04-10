<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Handle a login attempt.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Check if the account is restricted before attempting authentication
        $user = User::where('email', $credentials['email'])->first();
        
        if ($user && $user->is_restricted) {
            // Store the restriction reason in the session
            session(['restriction_reason' => $user->restriction_reason]);
            session(['restricted_at' => $user->restricted_at ? $user->restricted_at->format('d/m/Y H:i') : 'Unknown']);
            
            // Redirect to the restricted account page
            return redirect()->route('restricted.account');
        }

        // Proceed with normal authentication attempt
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }
}