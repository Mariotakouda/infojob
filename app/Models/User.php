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
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
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
