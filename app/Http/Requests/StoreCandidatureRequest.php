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
            'note_motivation'  => ['nullable', 'string', 'max:2000'],
            'cv'               => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
            'lettre_motivation' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'note_motivation.max'     => 'Votre note de motivation ne peut pas dépasser 2000 caractères.',
            'cv.file'                 => 'Le CV doit être un fichier valide.',
            'cv.mimes'                => 'Le CV doit être au format PDF, DOC ou DOCX.',
            'cv.max'                  => 'Le CV ne doit pas dépasser 5 Mo.',
            'lettre_motivation.file'  => 'La lettre de motivation doit être un fichier valide.',
            'lettre_motivation.mimes' => 'La lettre de motivation doit être au format PDF, DOC ou DOCX.',
            'lettre_motivation.max'   => 'La lettre de motivation ne doit pas dépasser 5 Mo.',
        ];
    }
}
