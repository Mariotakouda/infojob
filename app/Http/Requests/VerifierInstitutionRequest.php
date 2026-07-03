<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifierInstitutionRequest extends FormRequest
{
    public function authorize(): bool
    {
        // L'accès est déjà restreint par le middleware role:admin sur la route.
        return true;
    }

    public function rules(): array
    {
        return [
            'statut_verification' => ['required', 'in:verifiee,rejetee,en_attente'],
            'motif_rejet'          => ['required_if:statut_verification,rejetee', 'nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'statut_verification.required' => 'Veuillez sélectionner une décision.',
            'statut_verification.in'       => 'La décision sélectionnée n\'est pas valide.',
            'motif_rejet.required_if'      => 'Veuillez indiquer le motif du refus.',
        ];
    }
}
