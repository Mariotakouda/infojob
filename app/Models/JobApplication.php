<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'titre_profil',
        'secteur_activite',
        'cv_ou_portfolio_path',
        'competences',
        'disponibilite',
        'ville',
        'statut_moderation',
    ];

    protected function casts(): array
    {
        return [
            'disponibilite' => 'boolean',
        ];
    }

    // ─── Scopes ─────────────────────────────────────────────────────────────

    public function scopeApprouves(Builder $query): Builder
    {
        return $query->where('statut_moderation', 'approuve');
    }

    public function scopeDisponibles(Builder $query): Builder
    {
        return $query->where('disponibilite', true);
    }

    public function scopeParVille(Builder $query, string $ville): Builder
    {
        return $query->where('ville', $ville);
    }

    public function scopeParSecteur(Builder $query, string $secteur): Builder
    {
        return $query->where('secteur_activite', $secteur);
    }

    // ─── Helpers ────────────────────────────────────────────────────────────

    public function statutLabel(): string
    {
        return match ($this->statut_moderation) {
            'en_attente' => 'En attente',
            'approuve'   => 'Approuvé',
            'rejete'     => 'Rejeté',
            default      => $this->statut_moderation,
        };
    }

    public function competencesArray(): array
    {
        return array_map('trim', explode(',', $this->competences));
    }

    // ─── Relations ──────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
