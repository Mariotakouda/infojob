<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Candidature extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'job_offer_id',
        'note_motivation',
        'statut_candidature',
    ];

    // ─── Scopes ─────────────────────────────────────────────────────────────

    public function scopeAcceptees(Builder $query): Builder
    {
        return $query->where('statut_candidature', 'acceptee');
    }

    public function scopeEnDiscussion(Builder $query): Builder
    {
        return $query->where('statut_candidature', 'en_discussion');
    }

    // ─── Helpers ────────────────────────────────────────────────────────────

    public function statutLabel(): string
    {
        return match ($this->statut_candidature) {
            'recue'         => 'Reçue',
            'en_discussion' => 'En discussion',
            'acceptee'      => 'Acceptée',
            'refusee'       => 'Refusée',
            default         => $this->statut_candidature,
        };
    }

    public function statutBadgeClass(): string
    {
        return match ($this->statut_candidature) {
            'recue'         => 'badge-secondary',
            'en_discussion' => 'badge-warning',
            'acceptee'      => 'badge-success',
            'refusee'       => 'badge-danger',
            default         => 'badge-secondary',
        };
    }

    // ─── Relations ──────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jobOffer(): BelongsTo
    {
        return $this->belongsTo(JobOffer::class);
    }
}
