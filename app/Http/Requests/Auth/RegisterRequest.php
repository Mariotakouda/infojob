<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255', 'unique:users'],
            'telephone' => ['nullable', 'digits:8'],
            'role'      => ['required', 'in:citoyen,recruteur'],
            'password'  => ['required', 'confirmed', Password::min(8)],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'      => 'Le nom est obligatoire.',
            'email.required'     => 'L\'adresse email est obligatoire.',
            'email.email'        => 'Veuillez saisir une adresse email valide.',
            'email.unique'       => 'Cette adresse email est déjà utilisée.',
            'telephone.digits'   => 'Le numéro doit contenir exactement 8 chiffres (sans l\'indicatif).',
            'role.required'      => 'Veuillez sélectionner un rôle.',
            'role.in'            => 'Le rôle sélectionné est invalide.',
            'password.required'  => 'Le mot de passe est obligatoire.',
            'password.min'       => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ];
    }
}
