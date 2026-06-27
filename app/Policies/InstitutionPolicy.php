<?php

namespace App\Policies;

use App\Models\Institution;
use App\Models\User;

class InstitutionPolicy
{
    /**
     * Les admins peuvent tout faire.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return true; // liste publique
    }

    public function view(User $user, Institution $institution): bool
    {
        return true; // fiche publique
    }

    public function create(User $user): bool
    {
        return $user->isRecruteur();
    }

    /**
     * Le recruteur peut gérer (créer/modifier/supprimer des offres pour) une
     * institution dont il est propriétaire. Utilisé via Gate::authorize('manage', ...).
     */
    public function manage(User $user, Institution $institution): bool
    {
        return $user->isRecruteur() && $institution->user_id === $user->id;
    }

    public function update(User $user, Institution $institution): bool
    {
        return $user->isRecruteur() && $institution->user_id === $user->id;
    }

    public function delete(User $user, Institution $institution): bool
    {
        return $user->isRecruteur() && $institution->user_id === $user->id;
    }

    public function restore(User $user, Institution $institution): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, Institution $institution): bool
    {
        return $user->isAdmin();
    }
}