<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobOfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'institution_id'  => ['required', 'exists:institutions,id'],
            'titre'           => ['required', 'string', 'max:255'],
            'description'     => ['required', 'string'],
            'affiche'         => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:4096'],
            'type_contrat'    => ['required', 'in:CDI,CDD,Stage,Prestation_Artisanale'],
            'metier'          => ['required', 'string', 'max:255'],
            'lieu'            => ['required', 'string', 'max:255'],
            'budget_salaire'  => ['nullable', 'integer', 'min:0'],
            'date_expiration' => ['required', 'date', 'after:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'institution_id.required'  => 'Veuillez sélectionner une institution.',
            'institution_id.exists'    => 'L\'institution sélectionnée n\'existe pas.',
            'titre.required'           => 'Le titre de l\'offre est obligatoire.',
            'description.required'     => 'La description est obligatoire.',
            'affiche.image'            => 'Le fichier doit être une image.',
            'affiche.mimes'            => 'Formats acceptés : JPG, PNG, WEBP.',
            'affiche.max'              => 'L\'affiche ne doit pas dépasser 4 Mo.',
            'type_contrat.required'    => 'Veuillez sélectionner un type de contrat.',
            'type_contrat.in'          => 'Le type de contrat sélectionné n\'est pas valide.',
            'metier.required'          => 'Veuillez sélectionner le métier / la profession concerné(e).',
            'lieu.required'            => 'Le lieu est obligatoire.',
            'budget_salaire.integer'   => 'Le budget/salaire doit être un nombre entier.',
            'budget_salaire.min'       => 'Le budget/salaire ne peut pas être négatif.',
            'date_expiration.required' => 'La date d\'expiration est obligatoire.',
            'date_expiration.date'     => 'La date d\'expiration n\'est pas valide.',
            'date_expiration.after'    => 'La date d\'expiration doit être postérieure à aujourd\'hui.',
        ];
    }
}
