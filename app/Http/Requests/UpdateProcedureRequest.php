<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProcedureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre'         => ['required', 'string', 'max:255'],
            'description'   => ['required', 'string'],
            'cout'          => ['nullable', 'integer', 'min:0'],
            'delai'         => ['nullable', 'string', 'max:100'],
            'lieu_depot'    => ['nullable', 'string', 'max:255'],
            'lien_en_ligne' => ['nullable', 'url', 'max:255'],

            // Pièces & documents requis
            'pieces'                   => ['nullable', 'array', 'max:30'],
            'pieces.*.libelle'         => ['nullable', 'string', 'max:255'],
            'pieces.*.est_obligatoire' => ['nullable'],
        ];
    }

    public function messages(): array
    {
        return [
            'titre.required'           => 'Le titre de la démarche est obligatoire.',
            'description.required'     => 'La description est obligatoire.',
            'cout.integer'             => 'Le coût doit être un nombre entier.',
            'cout.min'                 => 'Le coût ne peut pas être négatif.',
            'lien_en_ligne.url'        => 'Le lien doit être une adresse internet valide.',
            'pieces.max'               => 'Vous ne pouvez pas ajouter plus de 30 pièces.',
            'pieces.*.libelle.max'     => "Le libellé d'une pièce ne peut pas dépasser 255 caractères.",
        ];
    }

    /**
     * Un coût non renseigné est considéré comme gratuit (0 F CFA).
     */
    protected function passedValidation(): void
    {
        $this->merge([
            'cout' => (int) ($this->input('cout') ?? 0),
        ]);
    }
}