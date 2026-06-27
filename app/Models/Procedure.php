<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Procedure extends Model
{
    use HasFactory;

    protected $fillable = [
        'institution_id',
        'titre',
        'description',
        'cout',
        'delai',
        'lieu_depot',
        'lien_en_ligne',
        'slug',
    ];

    protected function casts(): array
    {
        return [
            'cout' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Procedure $procedure) {
            if (empty($procedure->slug)) {
                $procedure->slug = Str::slug($procedure->titre) . '-' . Str::random(6);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // Formate le coût en F CFA lisible
    public function coutFormate(): string
    {
        return number_format($this->cout, 0, ',', ' ') . ' F CFA';
    }

    // ─── Relations ──────────────────────────────────────────────────────────

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function requirements(): HasMany
    {
        return $this->hasMany(Requirement::class);
    }

    public function obligatoires(): HasMany
    {
        return $this->hasMany(Requirement::class)->where('est_obligatoire', true);
    }
}
