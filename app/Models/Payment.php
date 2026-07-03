<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Builder;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payable_type',
        'payable_id',
        'identifier',
        'tx_reference',
        'montant',
        'moyen_paiement',
        'telephone',
        'statut',
        'motif',
        'paye_a',
    ];

    protected function casts(): array
    {
        return [
            'montant' => 'integer',
            'paye_a'  => 'datetime',
        ];
    }

    // ─── Tarifs de référence (F CFA) ────────────────────────────────────────
    // Centralisés ici pour ne pas avoir de "montants magiques" dispersés
    // dans les contrôleurs.

    public const PRIX_BOOST_OFFRE = 2000; // 7 jours de mise en avant

    // ─── Scopes ─────────────────────────────────────────────────────────────

    public function scopeReussis(Builder $query): Builder
    {
        return $query->where('statut', 'reussi');
    }

    public function scopeEnAttente(Builder $query): Builder
    {
        return $query->where('statut', 'en_attente');
    }

    // ─── Helpers ────────────────────────────────────────────────────────────

    public function estReussi(): bool
    {
        return $this->statut === 'reussi';
    }

    public function montantFormate(): string
    {
        return number_format($this->montant, 0, ',', ' ') . ' F CFA';
    }

    public function statutLabel(): string
    {
        return match ($this->statut) {
            'en_attente' => 'En attente',
            'reussi'     => 'Payé',
            'echoue'     => 'Échoué',
            'expire'     => 'Expiré',
            'annule'     => 'Annulé',
            default      => $this->statut,
        };
    }

    // ─── Relations ──────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }
}
