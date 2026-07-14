<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'telephone',
        'photo',
        'role',
        'two_factor_code',
        'two_factor_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_code',
    ];

    protected function casts(): array
    {
        return [
            'password'              => 'hashed',
            'two_factor_expires_at' => 'datetime',
        ];
    }

    // ─── Helpers de rôle ────────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isRecruteur(): bool
    {
        return $this->role === 'recruteur';
    }

    public function isCitoyen(): bool
    {
        return $this->role === 'citoyen';
    }

    /**
     * URL publique de la photo de profil, ou null si l'utilisateur n'en a pas.
     */
    public function photoUrl(): ?string
    {
        return $this->photo ? \Illuminate\Support\Facades\Storage::disk('public')->url($this->photo) : null;
    }

    // ─── Relations ──────────────────────────────────────────────────────────

    public function institutions(): HasMany
    {
        return $this->hasMany(Institution::class);
    }

    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function candidatures(): HasMany
    {
        return $this->hasMany(Candidature::class);
    }
}