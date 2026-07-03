<?php

namespace App\Http\Requests;

use App\Models\Institution;
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
        // Tous les types (public, entreprise privée, particulier) doivent
        // fournir un justificatif — seule la nature attendue diffère
        // (voir Institution::justificatifLabel()). Aucun type n'est dispensé.
        $requis = 'required';

        // Le justificatif est en plus obligatoire uniquement à la création
        // (pas de re-upload forcé à chaque modification).
        $justificatifRule = $this->isMethod('post')
            ? $requis
            : 'nullable';

        return [
            'nom'                    => ['required', 'string', 'max:255'],
            'type'                   => ['required', 'in:ministere,mairie,prefecture,direction,presidence,entreprise_privee,particulier'],
            'ville'                  => ['required', 'string', 'max:100'],
            'adresse'                => ['required', 'string', 'max:255'],
            'contact_public'         => ['nullable', 'string', 'max:255'],
            'numero_identification'  => [$requis, 'string', 'max:100'],
            'document_justificatif'  => [$justificatifRule, 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required'                        => 'Le nom de l\'institution est obligatoire.',
            'type.required'                        => 'Veuillez sélectionner un type d\'institution.',
            'type.in'                              => 'Le type d\'institution sélectionné n\'est pas valide.',
            'ville.required'                       => 'La ville est obligatoire.',
            'adresse.required'                     => 'L\'adresse est obligatoire.',
            'numero_identification.required'       => 'Ce numéro / cette référence est obligatoire.',
            'document_justificatif.required'       => 'Un justificatif officiel est obligatoire.',
            'document_justificatif.file'           => 'Le justificatif doit être un fichier valide.',
            'document_justificatif.mimes'          => 'Le justificatif doit être au format PDF, JPG ou PNG.',
            'document_justificatif.max'            => 'Le justificatif ne doit pas dépasser 5 Mo.',
        ];
    }
}
