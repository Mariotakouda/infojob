@props(['name', 'accept' => '.pdf,.doc,.docx'])

@php
    $uid = 'file-' . $name . '-' . uniqid();
@endphp

<div
    class="file-drop relative border border-dashed border-gray-300 rounded-lg px-3 py-3 transition-all duration-200 hover:border-primary/50 hover:bg-primary/[0.02]"
    id="{{ $uid }}-wrapper"
>
    <input
        type="file"
        name="{{ $name }}"
        id="{{ $uid }}"
        accept="{{ $accept }}"
        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
    >

    <div class="file-drop-empty flex items-center gap-2.5 pointer-events-none">
        <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-gray-100 text-gray-400 flex items-center justify-center transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
        </div>
        <div class="text-xs leading-tight">
            <p class="font-medium text-gray-600">Cliquez ou glissez un fichier ici</p>
            <p class="text-gray-400 mt-0.5">PDF, DOC ou DOCX — 5 Mo max</p>
        </div>
    </div>

    <div class="file-drop-filled hidden items-center justify-between gap-2.5">
        <div class="flex items-center gap-2.5 min-w-0">
            <div class="flex-shrink-0 w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v2.625a3.375 3.375 0 01-3.375 3.375h-1.5A1.125 1.125 0 0113.5 19.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.625a1.125 1.125 0 01-1.125-1.125v-1.5A3.375 3.375 0 004.125 8.25H2.25m6-6h7.5c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-7.5A1.125 1.125 0 016.375 14.625v-9.75c0-.621.504-1.125 1.125-1.125z" />
                </svg>
            </div>
            <div class="text-xs leading-tight min-w-0">
                <p class="file-drop-name font-medium text-gray-800 truncate"></p>
                <p class="file-drop-size text-gray-400 mt-0.5"></p>
            </div>
        </div>
        <button type="button" class="file-drop-remove relative z-10 flex-shrink-0 w-6 h-6 rounded-full text-gray-400 hover:text-red-500 hover:bg-red-50 flex items-center justify-center transition-colors duration-200" title="Retirer le fichier">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>

@once
    @push('scripts')
    <script>
        (function () {
            function formatSize(bytes) {
                if (bytes < 1024) return bytes + ' o';
                if (bytes < 1024 * 1024) return Math.round(bytes / 1024) + ' Ko';
                return (bytes / (1024 * 1024)).toFixed(1) + ' Mo';
            }

            function initFileDrop(wrapper) {
                const input  = wrapper.querySelector('input[type="file"]');
                const empty  = wrapper.querySelector('.file-drop-empty');
                const filled = wrapper.querySelector('.file-drop-filled');
                const name   = wrapper.querySelector('.file-drop-name');
                const size   = wrapper.querySelector('.file-drop-size');
                const remove = wrapper.querySelector('.file-drop-remove');

                if (!input || input.dataset.boundFileDrop) return;
                input.dataset.boundFileDrop = '1';

                const renderFile = () => {
                    const file = input.files && input.files[0];
                    if (file) {
                        name.textContent = file.name;
                        size.textContent = formatSize(file.size);
                        empty.classList.add('hidden');
                        filled.classList.remove('hidden');
                        filled.classList.add('flex');
                        wrapper.classList.add('border-primary/60', 'bg-primary/[0.03]');
                    } else {
                        empty.classList.remove('hidden');
                        filled.classList.add('hidden');
                        filled.classList.remove('flex');
                        wrapper.classList.remove('border-primary/60', 'bg-primary/[0.03]');
                    }
                };

                input.addEventListener('change', renderFile);

                remove.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    input.value = '';
                    renderFile();
                });

                ['dragenter', 'dragover'].forEach((evt) => {
                    input.addEventListener(evt, () => wrapper.classList.add('border-primary', 'bg-primary/5'));
                });
                ['dragleave', 'drop'].forEach((evt) => {
                    input.addEventListener(evt, () => wrapper.classList.remove('border-primary', 'bg-primary/5'));
                });
            }

            document.querySelectorAll('.file-drop').forEach(initFileDrop);

            // Au cas où un formulaire est inséré dynamiquement plus tard.
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.file-drop').forEach(initFileDrop);
            });
        })();
    </script>
    @endpush
@endonce
