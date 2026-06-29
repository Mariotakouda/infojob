<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // La vérification "propriétaire" se fait dans le contrôleur via
        // authorizeOwner(), car elle compare au profil déjà chargé.
        return true;
    }

    public function rules(): array
    {
        return [
            'titre_profil'     => ['required', 'string', 'max:150'],
            'secteur_activite' => ['required', 'string', 'max:100'],
            'competences'      => ['required', 'string', 'max:1000'],
            'ville'            => ['required', 'string', 'max:100'],
            'disponibilite'    => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'titre_profil.required'     => 'Le titre du profil est obligatoire.',
            'secteur_activite.required' => 'Le secteur d\'activité est obligatoire.',
            'competences.required'      => 'Veuillez décrire vos compétences.',
            'competences.max'           => 'La description des compétences ne peut pas dépasser 1000 caractères.',
            'ville.required'            => 'La ville est obligatoire.',
        ];
    }
}
