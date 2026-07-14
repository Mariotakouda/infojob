@extends('layouts.app')

@section('title', 'Créer une institution')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="group inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-primary transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 transition-transform duration-200 group-hover:-translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Tableau de bord
        </a>
    </div>

    <div class="bg-white border border-gray-200 rounded-xl p-6">
        <h1 class="text-xl font-bold text-gray-900 mb-1">Ajouter une institution</h1>
        <p class="text-sm text-gray-500 mb-6">
            Toute institution - publique ou privée - est vérifiée manuellement par un administrateur avant de
            pouvoir publier des offres ou des démarches. La nature du justificatif demandé dépend simplement du type choisi.
        </p>

        <form method="POST" action="{{ route('institutions.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom de l'institution <span class="text-red-500">*</span></label>
                <input type="text" name="nom" value="{{ old('nom') }}" required
                    placeholder="Ex : Mairie du Golfe 1"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('nom') border-red-400 @enderror">
                @error('nom')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                <select id="type-select" name="type" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('type') border-red-400 @enderror">
                    <option value="">— Sélectionner —</option>
                    <optgroup label="Institution publique">
                        <option value="ministere" data-public="1" {{ old('type') === 'ministere' ? 'selected' : '' }}>Ministère</option>
                        <option value="mairie" data-public="1" {{ old('type') === 'mairie' ? 'selected' : '' }}>Mairie</option>
                        <option value="prefecture" data-public="1" {{ old('type') === 'prefecture' ? 'selected' : '' }}>Préfecture</option>
                        <option value="presidence" data-public="1" {{ old('type') === 'presidence' ? 'selected' : '' }}>Présidence</option>
                        <option value="direction" data-public="1" {{ old('type') === 'direction' ? 'selected' : '' }}>Direction</option>
                        <option value="assemblee" data-public="1" {{ old('type') === 'assemblee' ? 'selected' : '' }}>Assemblée nationale</option>
                        <option value="conseil_regional" data-public="1" {{ old('type') === 'conseil_regional' ? 'selected' : '' }}>Conseil régional</option>
                        <option value="conseil_municipal" data-public="1" {{ old('type') === 'conseil_municipal' ? 'selected' : '' }}>Conseil municipal</option>
                        <option value="tribunal" data-public="1" {{ old('type') === 'tribunal' ? 'selected' : '' }}>Tribunal</option>
                        <option value="cour_supreme" data-public="1" {{ old('type') === 'cour_supreme' ? 'selected' : '' }}>Cour suprême</option>
                        <option value="cour_constitutionnelle" data-public="1" {{ old('type') === 'cour_constitutionnelle' ? 'selected' : '' }}>Cour constitutionnelle</option>
                        <option value="universite_publique" data-public="1" {{ old('type') === 'universite_publique' ? 'selected' : '' }}>Université publique</option>
                        <option value="ecole_publique" data-public="1" {{ old('type') === 'ecole_publique' ? 'selected' : '' }}>École publique</option>
                        <option value="hopital" data-public="1" {{ old('type') === 'hopital' ? 'selected' : '' }}>Hôpital public</option>
                        <option value="centre_sante" data-public="1" {{ old('type') === 'centre_sante' ? 'selected' : '' }}>Centre de santé</option>
                        <option value="police" data-public="1" {{ old('type') === 'police' ? 'selected' : '' }}>Police nationale</option>
                        <option value="gendarmerie" data-public="1" {{ old('type') === 'gendarmerie' ? 'selected' : '' }}>Gendarmerie</option>
                        <option value="douane" data-public="1" {{ old('type') === 'douane' ? 'selected' : '' }}>Douanes</option>
                        <option value="impots" data-public="1" {{ old('type') === 'impots' ? 'selected' : '' }}>Direction des impôts</option>
                        <option value="poste" data-public="1" {{ old('type') === 'poste' ? 'selected' : '' }}>La Poste</option>
                    </optgroup>

                    <optgroup label="Entité privée">
                        <option value="entreprise_privee" data-public="0" {{ old('type') === 'entreprise_privee' ? 'selected' : '' }}>Entreprise privée / Startup</option>
                        <option value="particulier" data-public="0" data-particulier="1" {{ old('type') === 'particulier' ? 'selected' : '' }}>Particulier</option>
                    </optgroup>
                </select>
                @error('type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ville <span class="text-red-500">*</span></label>
                    <input type="text" name="ville" value="{{ old('ville') }}" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('ville') border-red-400 @enderror">
                    @error('ville')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contact public</label>
                    <input type="text" name="contact_public" value="{{ old('contact_public') }}" placeholder="email ou téléphone"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Adresse complète <span class="text-red-500">*</span></label>
                <input type="text" name="adresse" value="{{ old('adresse') }}" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('adresse') border-red-400 @enderror">
                @error('adresse')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            {{-- ── Vérification (tous les types) ───────────────────────────── --}}
            <div id="verification-section" class="border-t border-gray-100 pt-5 hidden">
                <div class="flex items-center gap-2 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                    </svg>
                    <h2 class="text-sm font-semibold text-gray-900">Justificatif de vérification</h2>
                </div>
                <p id="verif-help" class="text-xs text-gray-500 mb-4 -mt-2">
                    Obligatoire : votre demande sera examinée par un administrateur avant que vous puissiez publier.
                </p>

                <div>
                    <label id="numero-label" class="block text-sm font-medium text-gray-700 mb-1">
                        Référence de l'acte / de la décision de nomination <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="numero-input" name="numero_identification" value="{{ old('numero_identification') }}"
                        placeholder="Ex : Arrêté n° 0100/2025/MATDCL-SG-DDCL"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('numero_identification') border-red-400 @enderror">
                    @error('numero_identification')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mt-4">
                    <label id="doc-label" class="block text-sm font-medium text-gray-700 mb-1">
                        Document justificatif <span class="text-red-500">*</span>
                        <span class="text-gray-400 font-normal">(PDF, JPG ou PNG — 5 Mo max)</span>
                    </label>
                    <x-file-input name="document_justificatif" accept=".pdf,.jpg,.jpeg,.png" />
                    @error('document_justificatif')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    <p id="doc-help" class="text-xs text-gray-400 mt-1.5">
                        Joignez l'acte de nomination, la décision officielle ou une carte professionnelle.
                    </p>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-primary-dark transition-colors">
                    Créer l'institution
                </button>
                <a href="{{ route('dashboard') }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition-colors">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    (function () {
        const select       = document.getElementById('type-select');
        const verifSection = document.getElementById('verification-section');
        const numeroInput  = document.getElementById('numero-input');
        const numeroLabel  = document.getElementById('numero-label');
        const docLabel     = document.getElementById('doc-label');
        const docHelp      = document.getElementById('doc-help');
        const verifHelp    = document.getElementById('verif-help');
        const fileInput    = verifSection.querySelector('input[type="file"]');

        const LABELS = {
            public: {
                numero: "Référence de l'acte / de la décision de nomination",
                numeroPlaceholder: 'Ex : Arrêté n° 0100/2025/MATDCL-SG-DDCL',
                doc: 'Document justificatif',
                docHelp: "Joignez l'acte de nomination, la décision officielle ou une carte professionnelle.",
                verifHelp: 'Obligatoire pour une institution publique : votre demande sera examinée par un administrateur avant que vous puissiez publier.',
            },
            entreprise: {
                numero: 'Numéro RCCM ou NIF',
                numeroPlaceholder: 'Ex : TG-LOM-01-2025-B12-00001',
                doc: 'Registre de commerce (RCCM) ou numéro d\'identification fiscale (NIF)',
                docHelp: 'Joignez votre registre de commerce ou votre attestation NIF.',
                verifHelp: 'Obligatoire pour une entreprise privée : votre demande sera examinée par un administrateur avant que vous puissiez publier.',
            },
            particulier: {
                numero: "Numéro de la pièce d'identité",
                numeroPlaceholder: 'Ex : CNI n° 0123456789',
                doc: "Pièce d'identité (CNI ou passeport)",
                docHelp: 'Joignez une copie recto-verso de votre pièce d\'identité.',
                verifHelp: 'Obligatoire pour un particulier : votre demande sera examinée par un administrateur avant que vous puissiez publier.',
            },
        };

        function refresh() {
            const opt = select.options[select.selectedIndex];
            const aChoisi = !!(opt && opt.value);
            const estPublique = !!(opt && opt.dataset.public === '1');
            const estParticulier = !!(opt && opt.dataset.particulier === '1');

            const l = estPublique ? LABELS.public : (estParticulier ? LABELS.particulier : LABELS.entreprise);

            numeroLabel.innerHTML = l.numero + ' <span class="text-red-500">*</span>';
            numeroInput.placeholder = l.numeroPlaceholder;
            docLabel.innerHTML = l.doc + ' <span class="text-red-500">*</span> <span class="text-gray-400 font-normal">(PDF, JPG ou PNG — 5 Mo max)</span>';
            docHelp.textContent = l.docHelp;
            verifHelp.textContent = l.verifHelp;

            // La section reste visible dès qu'un type est choisi : tous les
            // types demandent désormais un justificatif, seule sa nature change.
            verifSection.classList.toggle('hidden', !aChoisi);
            numeroInput.required = aChoisi;
            if (fileInput) {
                fileInput.required = aChoisi;
            }
        }

        select.addEventListener('change', refresh);
        refresh();
    })();
</script>

@endsection
