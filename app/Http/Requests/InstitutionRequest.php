<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstitutionRequest extends FormRequest
{
    public function authorize(): bool
    {
        // La vérification "propriétaire" se fait dans le contrôleur via
        // InstitutionPolicy, car elle nécessite l'instance déjà chargée.
        return true;
    }

    public function rules(): array
    {
        return [
            'nom'            => ['required', 'string', 'max:255'],
            'type'           => ['required', 'in:ministere,mairie,prefecture,direction,presidence,entreprise_privee,particulier'],
            'ville'          => ['required', 'string', 'max:100'],
            'adresse'        => ['required', 'string', 'max:255'],
            'contact_public' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required'     => 'Le nom de l\'institution est obligatoire.',
            'type.required'    => 'Veuillez sélectionner un type d\'institution.',
            'type.in'          => 'Le type d\'institution sélectionné n\'est pas valide.',
            'ville.required'   => 'La ville est obligatoire.',
            'adresse.required' => 'L\'adresse est obligatoire.',
        ];
    }
}
