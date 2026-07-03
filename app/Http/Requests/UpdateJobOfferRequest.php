<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobOfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        // La vérification Gate::authorize('manage', ...) se fait dans le
        // contrôleur, car elle porte sur l'institution liée à l'offre.
        return true;
    }

    public function rules(): array
    {
        return [
            'titre'           => ['required', 'string', 'max:255'],
            'description'     => ['required', 'string'],
            'type_contrat'    => ['required', 'in:CDI,CDD,Stage,Prestation_Artisanale'],
            'metier'          => ['required', 'string', 'max:255'],
            'lieu'            => ['required', 'string', 'max:255'],
            'budget_salaire'  => ['nullable', 'integer', 'min:0'],
            'date_expiration' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'titre.required'           => 'Le titre de l\'offre est obligatoire.',
            'description.required'     => 'La description est obligatoire.',
            'type_contrat.required'    => 'Veuillez sélectionner un type de contrat.',
            'type_contrat.in'          => 'Le type de contrat sélectionné n\'est pas valide.',
            'metier.required'          => 'Veuillez sélectionner le métier / la profession concerné(e).',
            'lieu.required'            => 'Le lieu est obligatoire.',
            'budget_salaire.integer'   => 'Le budget/salaire doit être un nombre entier.',
            'budget_salaire.min'       => 'Le budget/salaire ne peut pas être négatif.',
            'date_expiration.required' => 'La date d\'expiration est obligatoire.',
            'date_expiration.date'     => 'La date d\'expiration n\'est pas valide.',
        ];
    }
}
