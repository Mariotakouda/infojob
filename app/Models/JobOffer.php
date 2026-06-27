<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class JobOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_id',
        'titre',
        'description',
        'type_contrat',
        'lieu',
        'budget_salaire',
        'date_expiration',
        'statut',
    ];

    protected function casts(): array
    {
        return [
            'date_expiration' => 'date',
            'budget_salaire'  => 'integer',
        ];
    }

    // ─── Scopes ─────────────────────────────────────────────────────────────

    public function scopePubliees(Builder $query): Builder
    {
        return $query->where('statut', 'publie')
                     ->where('date_expiration', '>=', Carbon::today());
    }

    public function scopeExpirees(Builder $query): Builder
    {
        return $query->where('date_expiration', '<', Carbon::today());
    }

    public function scopeParType(Builder $query, string $type): Builder
    {
        return $query->where('type_contrat', $type);
    }

    // ─── Helpers ────────────────────────────────────────────────────────────

    public function estExpiree(): bool
    {
        return $this->date_expiration->isPast();
    }

    public function budgetFormate(): string
    {
        if (! $this->budget_salaire) {
            return 'Non précisé';
        }
        return number_format($this->budget_salaire, 0, ',', ' ') . ' F CFA';
    }

    public function statutLabel(): string
    {
        return match ($this->statut) {
            'en_attente' => 'En attente',
            'publie'     => 'Publié',
            'expire'     => 'Expiré',
            default      => $this->statut,
        };
    }

    public function typeContratLabel(): string
    {
        return match ($this->type_contrat) {
            'CDI'                   => 'CDI',
            'CDD'                   => 'CDD',
            'Stage'                 => 'Stage',
            'Prestation_Artisanale' => 'Prestation artisanale',
            default                 => $this->type_contrat,
        };
    }

    // ─── Relations ──────────────────────────────────────────────────────────

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function candidatures(): HasMany
    {
        return $this->hasMany(Candidature::class);
    }
}
