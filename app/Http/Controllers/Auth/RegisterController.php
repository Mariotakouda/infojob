<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('avatars', 'public');
        }

        $user = User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'telephone' => isset($validated['telephone']) ? '+228 ' . $validated['telephone'] : null,
            'photo'     => $photoPath,
            'role'      => $validated['role'],
            'password'  => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Compte créé avec succès. Bienvenue !');
    }
}
