<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCandidatureStatutRequest extends FormRequest
{
    public function authorize(): bool
    {
        // La vérification "le recruteur possède bien l'offre concernée" se
        // fait dans le contrôleur, car elle dépend de la candidature chargée.
        return true;
    }

    public function rules(): array
    {
        return [
            'statut_candidature' => ['required', 'in:en_discussion,acceptee,refusee'],
        ];
    }

    public function messages(): array
    {
        return [
            'statut_candidature.required' => 'Veuillez sélectionner un statut.',
            'statut_candidature.in'       => 'Le statut sélectionné n\'est pas valide.',
        ];
    }
}
