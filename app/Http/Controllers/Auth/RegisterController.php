<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255', 'unique:users'],
            'telephone' => ['nullable', 'string', 'max:20'],
            'role'      => ['required', 'in:citoyen,recruteur'],
            'password'  => ['required', 'confirmed', Password::min(8)],
        ], [
            'name.required'     => 'Le nom est obligatoire.',
            'email.required'    => 'L\'adresse email est obligatoire.',
            'email.unique'      => 'Cette adresse email est déjà utilisée.',
            'role.in'           => 'Le rôle sélectionné est invalide.',
            'password.min'      => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'telephone' => $validated['telephone'] ?? null,
            'role'      => $validated['role'],
            'password'  => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Compte créé avec succès. Bienvenue !');
    }
}
