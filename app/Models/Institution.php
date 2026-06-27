<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    public function procedures(): HasMany
    {
        return $this->hasMany(Procedure::class);
    }

    public function jobOffers(): HasMany
    {
        return $this->hasMany(JobOffer::class);
    }
}
