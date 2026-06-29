<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCandidatureRequest extends FormRequest
{
    public function authorize(): bool
    {
        // La vérification "offre active / pas déjà postulé" se fait dans le
        // contrôleur, car elle dépend de l'offre déjà chargée.
        return true;
    }

    public function rules(): array
    {
        return [
            'note_motivation' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'note_motivation.max' => 'Votre note de motivation ne peut pas dépasser 2000 caractères.',
        ];
    }
}
