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
            'telephone' => ['nullable', 'digits:8'],
            'photo'     => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:2048'],
        ], [
            'name.required'    => 'Le nom est obligatoire.',
            'name.max'         => 'Le nom ne peut pas dépasser 255 caractères.',
            'telephone.digits' => 'Le numéro doit contenir exactement 8 chiffres (sans l\'indicatif).',
            'photo.image'      => 'Le fichier doit être une image.',
            'photo.mimes'      => 'Formats acceptés : JPG, PNG, WEBP.',
            'photo.max'        => 'L\'image ne doit pas dépasser 2 Mo.',
        ]);

        if (isset($validated['telephone'])) {
            $validated['telephone'] = '+228 ' . $validated['telephone'];
        }

        if ($request->hasFile('photo')) {
            if ($user->photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->photo);
            }
            $validated['photo'] = $request->file('photo')->store('avatars', 'public');
        }

        $user->update($validated);

        return back()->with('success_info', 'Vos informations ont été mises à jour.');
    }

    /**
     * Supprime la photo de profil de l'utilisateur connecté.
     */
    public function destroyPhoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->photo);
            $user->update(['photo' => null]);
        }

        return back()->with('success_info', 'Photo de profil supprimée.');
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