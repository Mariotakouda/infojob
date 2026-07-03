<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Institution extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nom',
        'type',
        'ville',
        'adresse',
        'contact_public',
        'slug',
        'numero_identification',
        'document_justificatif_path',
        'document_justificatif_nom_original',
        'statut_verification',
        'motif_rejet',
        'verifiee_at',
        'verifiee_par',
    ];

    protected $casts = [
        'verifiee_at' => 'datetime',
    ];

    /**
     * Entités publiques : nécessitent un acte / une décision de nomination
     * comme justificatif (mairie, préfecture, ministère, présidence...).
     */
    public const TYPES_PUBLICS = [
        'ministere',
        'mairie',
        'prefecture',
        'direction',
        'presidence',
    ];

    /**
     * Entités privées : startups, entreprises, particuliers — justificatif
     * attendu = registre de commerce (RCCM) ou numéro d'identification
     * fiscale (NIF).
     */
    public const TYPES_PRIVES = [
        'entreprise_privee',
        'particulier',
    ];

    // Génération automatique du slug à la création
    protected static function booted(): void
    {
        static::creating(function (Institution $institution) {
            if (empty($institution->slug)) {
                $institution->slug = Str::slug($institution->nom) . '-' . Str::random(6);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ─── Catégorisation public / privé ─────────────────────────────────────

    public function estPublique(): bool
    {
        return in_array($this->type, self::TYPES_PUBLICS, true);
    }

    public function estPrivee(): bool
    {
        return in_array($this->type, self::TYPES_PRIVES, true);
    }

    /**
     * Toutes les institutions — publiques comme privées — passent par un
     * contrôle manuel de justificatif avant de pouvoir publier. Seule la
     * nature de la preuve exigée diffère (voir justificatifLabel()).
     */
    public function necessiteVerification(): bool
    {
        return true;
    }

    /**
     * Libellé du document attendu, adapté selon le type exact :
     * - Public : acte de nomination / décision officielle
     * - Entreprise privée : RCCM ou NIF
     * - Particulier : pièce d'identité (CNI ou passeport)
     */
    public function justificatifLabel(): string
    {
        return match (true) {
            $this->estPublique() => 'Acte de nomination, décision ou carte professionnelle',
            $this->type === 'particulier' => 'Pièce d\'identité (CNI ou passeport)',
            default => 'Registre de commerce (RCCM) ou numéro d\'identification fiscale (NIF)',
        };
    }

    public function numeroIdentificationLabel(): string
    {
        return match (true) {
            $this->estPublique() => 'Référence de l\'acte / de la décision de nomination',
            $this->type === 'particulier' => 'Numéro de la pièce d\'identité',
            default => 'Numéro RCCM ou NIF',
        };
    }

    // ─── Vérification ───────────────────────────────────────────────────────

    public function estVerifiee(): bool
    {
        return $this->statut_verification === 'verifiee';
    }

    public function estEnAttente(): bool
    {
        return $this->statut_verification === 'en_attente';
    }

    public function estRejetee(): bool
    {
        return $this->statut_verification === 'rejetee';
    }

    public function aDocumentJustificatif(): bool
    {
        return ! empty($this->document_justificatif_path);
    }

    public function statutVerificationLabel(): string
    {
        return match ($this->statut_verification) {
            'verifiee' => 'Vérifiée',
            'rejetee'  => 'Vérification refusée',
            default    => 'En attente de vérification',
        };
    }

    public function statutVerificationBadgeClass(): string
    {
        return match ($this->statut_verification) {
            'verifiee' => 'bg-green-100 text-green-700',
            'rejetee'  => 'bg-red-100 text-red-700',
            default    => 'bg-amber-100 text-amber-700',
        };
    }

    public function scopeVerifiees(Builder $query): Builder
    {
        return $query->where('statut_verification', 'verifiee');
    }

    public function scopeEnAttenteVerification(Builder $query): Builder
    {
        return $query->where('statut_verification', 'en_attente');
    }

    // ─── Labels lisibles ────────────────────────────────────────────────────

    public function typeLabel(): string
    {
        return match ($this->type) {
            'ministere'       => 'Ministère',
            'mairie'          => 'Mairie',
            'prefecture'      => 'Préfecture',
            'direction'       => 'Direction',
            'presidence'      => 'Présidence',
            'entreprise_privee' => 'Entreprise privée',
            'particulier'     => 'Particulier',
            default           => $this->type,
        };
    }

    // ─── Relations ──────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verificateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verifiee_par');
    }

    public function procedures(): HasMany
    {
        return $this->hasMany(Procedure::class);
    }

    public function jobOffers(): HasMany
    {
        return $this->hasMany(JobOffer::class);
    }
}
