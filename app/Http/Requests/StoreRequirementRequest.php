<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequirementRequest extends FormRequest
{
    public function authorize(): bool
    {
        // La vérification "la procédure appartient au recruteur" se fait
        // dans le contrôleur, car elle dépend de la procédure de la route.
        return true;
    }

    public function rules(): array
    {
        return [
            'libelle'         => ['required', 'string', 'max:255'],
            'description'     => ['nullable', 'string', 'max:500'],
            'est_obligatoire' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'libelle.required' => 'Le libellé de la pièce est obligatoire.',
            'description.max'  => 'La description ne peut pas dépasser 500 caractères.',
        ];
    }
}
