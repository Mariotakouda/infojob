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
        'affiche',
        'type_contrat',
        'metier',
        'lieu',
        'budget_salaire',
        'date_expiration',
        'statut',
        'est_boostee',
        'boost_expire_at',
    ];

    /**
     * Liste de référence des métiers / professions proposés au filtre.
     * Utilisée à la fois pour la publication d'une offre et pour le filtre
     * de recherche sur la page « Offres d'emploi & chantiers ».
     */
    public const METIERS = [
        'Maçonnerie & BTP',
        'Plomberie',
        'Électricité',
        'Menuiserie & Ébénisterie',
        'Soudure & Métallerie',
        'Peinture & Décoration',
        'Mécanique automobile',
        'Couture & Mode',
        'Coiffure & Esthétique',
        'Informatique & Numérique',
        'Comptabilité & Finance',
        'Commerce & Vente',
        'Logistique & Transport',
        'Hôtellerie & Restauration',
        'Agriculture & Élevage',
        'Santé & Social',
        'Éducation & Formation',
        'Administration & Secrétariat',
        'Sécurité & Gardiennage',
        'Autre',
    ];

    protected function casts(): array
    {
        return [
            'date_expiration' => 'date',
            'budget_salaire'  => 'integer',
            'est_boostee'     => 'boolean',
            'boost_expire_at' => 'datetime',
        ];
    }

    // ─── Scopes ─────────────────────────────────────────────────────────────

    public function scopeBoostees(Builder $query): Builder
    {
        return $query->where('est_boostee', true)
                     ->where('boost_expire_at', '>=', Carbon::now());
    }

    public function scopePubliees(Builder $query): Builder
    {
        return $query->where('statut', 'publie')
                     ->where('date_expiration', '>=', Carbon::today())
                     ->orderByRaw('est_boostee AND boost_expire_at >= ? DESC', [Carbon::now()]);
    }

    public function scopeExpirees(Builder $query): Builder
    {
        return $query->where('date_expiration', '<', Carbon::today());
    }

    public function scopeParType(Builder $query, string $type): Builder
    {
        return $query->where('type_contrat', $type);
    }

    public function scopeParMetier(Builder $query, string $metier): Builder
    {
        return $query->where('metier', $metier);
    }

    // ─── Helpers ────────────────────────────────────────────────────────────

    public function estExpiree(): bool
    {
        return $this->date_expiration->isPast();
    }

    public function estActivementBoostee(): bool
    {
        return $this->est_boostee
            && $this->boost_expire_at
            && $this->boost_expire_at->isFuture();
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

    public function metierLabel(): string
    {
        return $this->metier ?: 'Non précisé';
    }

    /**
     * URL publique de l'affiche de l'offre, ou null si aucune n'a été fournie.
     */
    public function afficheUrl(): ?string
    {
        return $this->affiche ? \Illuminate\Support\Facades\Storage::disk('public')->url($this->affiche) : null;
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

    public function payments(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }
}
