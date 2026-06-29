{{-- Partial rechargé en AJAX par la recherche / le filtre / la pagination --}}
<div class="overflow-x-auto -mx-2 sm:mx-0">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100">
                <th class="text-left py-2 px-2 sm:px-0 sm:pr-4">Utilisateur</th>
                <th class="text-left py-2 px-2 sm:pr-4 hidden sm:table-cell">Email</th>
                <th class="text-left py-2 px-2 sm:pr-4">Rôle</th>
                <th class="text-left py-2 px-2 sm:pr-4 hidden md:table-cell">Inscrit</th>
                <th class="py-2 px-2"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr class="border-t border-gray-50 hover:bg-gray-50/60 transition-colors">
                    <td class="py-3 px-2 sm:px-0 sm:pr-4">
                        <p class="font-medium text-gray-900">{{ $user->name }}</p>
                        <p class="text-xs text-gray-400 sm:hidden">{{ $user->email }}</p>
                    </td>
                    <td class="py-3 px-2 sm:pr-4 text-gray-500 hidden sm:table-cell">{{ $user->email }}</td>
                    <td class="py-3 px-2 sm:pr-4">
                        <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full
                            {{ match($user->role) {
                                'admin'      => 'bg-purple-100 text-purple-700',
                                'recruteur'  => 'bg-blue-100 text-blue-700',
                                default      => 'bg-gray-100 text-gray-600',
                            } }}">
                            <span class="w-1.5 h-1.5 rounded-full
                                {{ match($user->role) {
                                    'admin'      => 'bg-purple-500',
                                    'recruteur'  => 'bg-blue-500',
                                    default      => 'bg-gray-400',
                                } }}"></span>
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="py-3 px-2 sm:pr-4 text-gray-400 text-xs hidden md:table-cell">
                        {{ $user->created_at->diffForHumans() }}
                    </td>
                    <td class="py-3 px-2 text-right">
                        @if(!$user->isAdmin())
                            <form method="POST" action="{{ route('admin.supprimer-user', $user) }}"
                                class="js-delete-user-form" data-user-name="{{ $user->name }}">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="text-xs text-gray-400 hover:text-red-600 hover:bg-red-50 px-2 py-1 rounded transition-colors">
                                    Supprimer
                                </button>
                            </form>
                        @else
                            <span class="text-xs text-gray-300">—</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-10 text-center text-sm text-gray-400">
                        Aucun utilisateur ne correspond à votre recherche.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mt-5 pt-4 border-t border-gray-100">
    <p class="text-xs text-gray-400">
        @if($users->total() > 0)
            Affichage de <span class="font-medium text-gray-600">{{ $users->firstItem() }}–{{ $users->lastItem() }}</span>
            sur <span class="font-medium text-gray-600">{{ $users->total() }}</span> utilisateur{{ $users->total() > 1 ? 's' : '' }}
        @else
            0 résultat
        @endif
    </p>

    <div class="text-sm [&_nav]:flex [&_nav]:justify-end">
        {{ $users->onEachSide(1)->links() }}
    </div>
</div>