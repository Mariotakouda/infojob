<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModererProfilRequest extends FormRequest
{
    public function authorize(): bool
    {
        // L'accès est déjà restreint par le middleware role:admin sur la route.
        return true;
    }

    public function rules(): array
    {
        return [
            'statut_moderation' => ['required', 'in:approuve,rejete'],
        ];
    }

    public function messages(): array
    {
        return [
            'statut_moderation.required' => 'Veuillez sélectionner une décision.',
            'statut_moderation.in'       => 'La décision sélectionnée n\'est pas valide.',
        ];
    }
}
