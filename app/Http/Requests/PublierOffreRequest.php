<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PublierOffreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // L'accès est déjà restreint par le middleware role:admin sur la route.
        return true;
    }

    public function rules(): array
    {
        return [
            'statut' => ['required', 'in:publie,expire,en_attente'],
        ];
    }

    public function messages(): array
    {
        return [
            'statut.required' => 'Veuillez sélectionner un statut.',
            'statut.in'       => 'Le statut sélectionné n\'est pas valide.',
        ];
    }
}
