@extends('layouts.app')

@section('title', 'Modifier — ' . $institution->nom)

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('institutions.show', $institution) }}" class="group inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-primary transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 transition-transform duration-200 group-hover:-translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            {{ $institution->nom }}
        </a>
    </div>

    {{-- Statut de vérification --}}
    <div class="mb-5 flex items-center gap-2.5 bg-white border border-gray-200 rounded-xl px-4 py-3">
        <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $institution->statutVerificationBadgeClass() }}">
            {{ $institution->statutVerificationLabel() }}
        </span>
        @if($institution->estRejetee() && $institution->motif_rejet)
            <p class="text-xs text-red-600 flex-1">Motif : {{ $institution->motif_rejet }}</p>
        @elseif($institution->estEnAttente())
            <p class="text-xs text-gray-500 flex-1">Un administrateur va examiner votre justificatif.</p>
        @elseif($institution->estVerifiee())
            <p class="text-xs text-gray-500 flex-1">Vérifiée le {{ $institution->verifiee_at?->format('d/m/Y') }}.</p>
        @endif
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6">
        <h1 class="text-xl font-bold text-gray-900 mb-1">Modifier l'institution</h1>
        <p class="text-sm text-gray-500 mb-6">
            @if($institution->estPublique())
                Modifier le nom, le type ou le numéro d'identification remettra l'institution en attente de vérification.
            @else
                Toute institution privée ou particulière est également vérifiée manuellement. Modifier votre identité
                remettra l'institution en attente de vérification.
            @endif
        </p>

        <form method="POST" action="{{ route('institutions.update', $institution) }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom de l'institution <span class="text-red-500">*</span></label>
                <input type="text" name="nom" value="{{ old('nom', $institution->nom) }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('nom') border-red-400 @enderror">
                @error('nom')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                <select id="type-select" name="type" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    <optgroup label="Institution publique">
                        @foreach(['ministere' => 'Ministère','mairie' => 'Mairie','prefecture' => 'Préfecture','presidence' => 'Présidence','direction' => 'Direction'] as $val => $label)
                            <option value="{{ $val }}" data-public="1" {{ old('type', $institution->type) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Entité privée">
                        @foreach(['entreprise_privee' => 'Entreprise privée / Startup','particulier' => 'Particulier'] as $val => $label)
                            <option value="{{ $val }}" data-public="0" data-particulier="{{ $val === 'particulier' ? '1' : '0' }}" {{ old('type', $institution->type) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </optgroup>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ville <span class="text-red-500">*</span></label>
                    <input type="text" name="ville" value="{{ old('ville', $institution->ville) }}" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact public</label>
                    <input type="text" name="contact_public" value="{{ old('contact_public', $institution->contact_public) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Adresse complète <span class="text-red-500">*</span></label>
                <input type="text" name="adresse" value="{{ old('adresse', $institution->adresse) }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            </div>

            {{-- ── Vérification (tous les types) ───────────────────────────── --}}
            <div id="verification-section" class="border-t border-gray-100 pt-5">
                <h2 class="text-sm font-semibold text-gray-900 mb-4">Justificatif de vérification</h2>

                <div>
                    <label id="numero-label" class="block text-sm font-medium text-gray-700 mb-1">
                        {{ $institution->numeroIdentificationLabel() }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="numero-input" name="numero_identification" value="{{ old('numero_identification', $institution->numero_identification) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('numero_identification') border-red-400 @enderror">
                    @error('numero_identification')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mt-4">
                    <label id="doc-label" class="block text-sm font-medium text-gray-700 mb-1">
                        Document justificatif
                        <span class="text-gray-400 font-normal">(laissez vide pour garder le document actuel)</span>
                    </label>

                    @if($institution->aDocumentJustificatif())
                        <a href="{{ route('institutions.download-justificatif', $institution) }}"
                            class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1.5 rounded-lg border border-gray-200 text-gray-600 hover:border-primary hover:text-primary hover:bg-primary/5 transition-all duration-200 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Document actuel : {{ $institution->document_justificatif_nom_original }}
                        </a>
                    @endif

                    <x-file-input name="document_justificatif" accept=".pdf,.jpg,.jpeg,.png" />
                    @error('document_justificatif')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors">
                    Enregistrer les modifications
                </button>
                <a href="{{ route('institutions.show', $institution) }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    (function () {
        const select       = document.getElementById('type-select');
        const numeroInput  = document.getElementById('numero-input');
        const numeroLabel  = document.getElementById('numero-label');
        const docLabel     = document.getElementById('doc-label');
        const fileInput    = document.getElementById('verification-section').querySelector('input[type="file"]');

        const LABELS = {
            public: { numero: "Référence de l'acte / de la décision de nomination", doc: 'Document justificatif' },
            entreprise: { numero: 'Numéro RCCM ou NIF', doc: "Registre de commerce (RCCM) ou numéro d'identification fiscale (NIF)" },
            particulier: { numero: "Numéro de la pièce d'identité", doc: "Pièce d'identité (CNI ou passeport)" },
        };

        function refresh() {
            const opt = select.options[select.selectedIndex];
            const estPublique = !!(opt && opt.dataset.public === '1');
            const estParticulier = !!(opt && opt.dataset.particulier === '1');
            const l = estPublique ? LABELS.public : (estParticulier ? LABELS.particulier : LABELS.entreprise);

            numeroLabel.innerHTML = l.numero + ' <span class="text-red-500">*</span>';
            docLabel.innerHTML = l.doc + ' <span class="text-gray-400 font-normal">(laissez vide pour garder le document actuel)</span>';

            // Toujours requis en modification : chaque type doit avoir une
            // référence à jour. Le fichier, lui, reste optionnel (on garde
            // l'ancien si rien n'est renvoyé).
            numeroInput.required = true;
            if (fileInput) {
                fileInput.required = false;
            }
        }

        select.addEventListener('change', refresh);
        refresh();
    })();
</script>

@endsection
