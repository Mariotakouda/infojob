<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Affiche la page profil de l'utilisateur connecté.
     */
    public function edit(): View
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    /**
     * Met à jour les informations générales (nom + téléphone).
     */
    public function updateInfo(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'telephone' => ['nullable', 'string', 'max:20'],
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'name.max'      => 'Le nom ne peut pas dépasser 255 caractères.',
        ]);

        $user->update($validated);

        return back()->with('success_info', 'Vos informations ont été mises à jour.');
    }

    /**
     * Met à jour l'adresse email.
     */
    public function updateEmail(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'email'            => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password_confirm' => ['required', 'current_password'],
        ], [
            'email.required'            => 'L\'adresse email est obligatoire.',
            'email.email'               => 'L\'adresse email n\'est pas valide.',
            'email.unique'              => 'Cette adresse email est déjà utilisée.',
            'password_confirm.required' => 'Veuillez confirmer votre mot de passe.',
            'password_confirm.current_password' => 'Mot de passe incorrect.',
        ]);

        $user->update(['email' => $validated['email']]);

        return back()->with('success_email', 'Votre email a été mis à jour.');
    }

    /**
     * Met à jour le mot de passe.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ], [
            'current_password.required'       => 'L\'ancien mot de passe est obligatoire.',
            'current_password.current_password' => 'L\'ancien mot de passe est incorrect.',
            'password.required'               => 'Le nouveau mot de passe est obligatoire.',
            'password.confirmed'              => 'Les mots de passe ne correspondent pas.',
            'password.min'                    => 'Le mot de passe doit contenir au moins 8 caractères.',
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        // Déconnecter les autres sessions actives
        Auth::logoutOtherDevices($request->password);

        return back()->with('success_password', 'Votre mot de passe a été mis à jour. Les autres sessions ont été déconnectées.');
    }
}