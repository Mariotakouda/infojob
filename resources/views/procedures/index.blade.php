@extends('layouts.app')

@section('title', 'Démarches administratives')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Démarches administratives</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $procedures->total() }} démarche(s) répertoriée(s)</p>
    </div>
    @auth
        @if(auth()->user()->isRecruteur())
            <a href="{{ route('procedures.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg text-sm hover:bg-primary-dark transition-colors">
                + Ajouter une démarche
            </a>
        @endif
    @endauth
</div>

{{-- Filtres --}}
<form method="GET" class="flex flex-wrap gap-3 mb-6">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Rechercher une démarche..."
        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary flex-1 min-w-[200px]">
    <select name="institution" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
        <option value="">Toutes les institutions</option>
        @foreach($institutions as $inst)
            <option value="{{ $inst->id }}" {{ request('institution') == $inst->id ? 'selected' : '' }}>{{ $inst->nom }}</option>
        @endforeach
    </select>
    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg text-sm hover:bg-primary-dark transition-colors">Filtrer</button>
    @if(request()->hasAny(['q','institution']))
        <a href="{{ route('procedures.index') }}" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-50 transition-colors">Réinitialiser</a>
    @endif
</form>

{{-- Liste --}}
@forelse($procedures as $procedure)
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-4 hover:border-primary transition-colors">
        <div class="flex items-start justify-between gap-4">
            <div class="flex-1">
                <p class="text-xs text-gray-400 mb-1">{{ $procedure->institution->nom }} · {{ $procedure->institution->ville }}</p>
                <h2 class="font-semibold text-gray-900 text-lg">
                    <a href="{{ route('procedures.show', $procedure) }}" class="hover:text-primary transition-colors">
                        {{ $procedure->titre }}
                    </a>
                </h2>
                <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ Str::limit($procedure->description, 150) }}</p>
            </div>
            <div class="text-right flex-shrink-0">
                @if($procedure->cout)
                    <p class="font-semibold text-primary text-sm">{{ $procedure->coutFormate() }}</p>
                @else
                    <p class="text-sm text-green-600 font-medium">Gratuit</p>
                @endif
                @if($procedure->delai)
                    <p class="text-xs text-gray-400 mt-1">⏱ {{ $procedure->delai }}</p>
                @endif
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between">
            <span class="text-xs text-gray-400">{{ $procedure->requirements->count() ?? 0 }} pièce(s) requise(s)</span>
            <a href="{{ route('procedures.show', $procedure) }}" class="text-sm font-medium text-primary hover:underline">
                Voir la démarche →
            </a>
        </div>
    </div>
@empty
    <div class="text-center py-16 text-gray-400">
        <p class="text-lg font-medium text-gray-500">Aucune démarche disponible</p>
        <p class="text-sm mt-1">Modifiez vos critères ou revenez plus tard.</p>
    </div>
@endforelse

<div class="mt-6">{{ $procedures->links() }}</div>

@endsection